<?php
	
	// return both data and html
	// need an edit recipe page in addn to insertion page. insert page needs to look nicer too
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if (!isset($base_path)) $base_path = isset($_GET["ajax"]) ? "../" : "../php/";
	if (isset($_GET["is_builder"])) $is_builder = $_GET["is_builder"];
	
	if (isset($_GET["base_path"])) {
	    $base_path = $_GET["base_path"]."php/";
    }

	include_once $base_path . "libraries/db_functions.php";
	include_once $base_path . "libraries/php_helpers.php";
	include_once $base_path . "libraries/recipe_calculators.php";
	include_once $base_path . "libraries/generate_html.php";

	$dietary_map = [
		"alcohol_free" => "No Alcohol",
		"gluten_free" => "No Gluten",
		"sugar_free" => "No Sugar",
		"dairy_free" => "No Dairy",
		"nut_free" => "No Nuts",
		"shellfish_free" => "No Shellfish"
	];

	$view_name = isset($_GET["search_string"]) ? "search_view_" . str_replace(" |%20", "_", $_GET["search_string"]) : "";

	$overall_where = array();
	foreach (display_qualities() as $q) {
		if (isset($_GET[$q])) {
			$overall_where[$q] = $_GET[$q];
		}
	}
	$overall_where = array_filter($overall_where,function($v) {return $v != FALSE;});

	if (count($overall_where) == 0) {
		if (isset($_GET["course"])) {
			$where = " where summ.course = '".$_GET["course"]."'";
		} else {
			$where = "";
		}
	} else {
		$where = " where ";
		$vals = array();
		foreach ($overall_where as $k => $v) {
			array_push($vals," summ.".$k." = 1 ");
		}
		if (isset($_GET["course"])) array_push($vals,"summ.course = '".$_GET["course"]."'");
		$where .= implode(" and ", $vals);
	}

	$open_view = "create or replace view ".$view_name.
		" as".
		" select distinct r.id ".
	    "            from recipe r ".
		"				join step s on s.recipe_id = r.id ".
		"				join material m on m.step_id = s.id ".
		"				join ingredient i on m.ingredient_id = i.id".
		(	isset($_GET["search_string"]) ?
			" where i.singular_name like '%".$_GET["search_string"]."%' or r.name like '%".$_GET["search_string"]."%'" :
			""
		).
		";";

	$search_query = "select * ".

		", ifnull(summ.protein / (summ.protein + summ.fat + summ.carbs),0) protein_proportion".
        ", ifnull(summ.fat / (summ.protein + summ.fat + summ.carbs),0) fat_proportion".
        ", ifnull(summ.carbs / (summ.protein + summ.fat + summ.carbs),0) carbs_proportion".

        " from (select ".
		" r.name recipe_name ".
		" ,r.id recipe_id".
		" ,r.makes_or_serves_amount ".
		" ,r.makes_or_serves_measure_id ".
		" ,r.makes_or_serves ".
		" ,r.course ".

		" ,case when sum(case when i.vegan = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end vegan ".
		"	, case when sum(case when i.vegetarian = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end vegetarian ".
		"	, case when sum(case when i.gluten_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end gluten_free ".
		"	, case when sum(case when i.sugar_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end sugar_free ".
		"	, case when sum(case when i.pork_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end pork_free ".
		"	, case when sum(case when i.alcohol_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end alcohol_free ".
		"	, case when sum(case when i.dairy_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end dairy_free ".
		"	, case when sum(case when i.nut_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end nut_free ".
		"	, case when sum(case when i.fish_free = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end fish_free ".
		"	, case when sum(case when i.shellfish_free = 0 then 1 else 0 end) = 0 ". 
		"		then 1 else 0 end shellfish_free ".
		"	, case when  ".
		"		sum(case when i.pork_free = 0 then 1 else 0 end) = 0  ".
		"			and (sum(case when i.dairy_free = 0 then 1 else 0 end) = 0 or ". 
		"				sum(case when i.vegetarian = 0 then 1 else 0 end) = 0) ".
		"			then 1 else 0 end kosher ".
		"	, case when sum(case when i.fish_free = 1 and shellfish_free = 1 and i.vegetarian = 0 then 1 else 0 end) = 0  ".
		"		then 1 else 0 end pescatarian ".

		" ,SUM(CASE WHEN msr.weight_or_volume = 'volume'".
		"	THEN i.calories_per_cup / 8 * m.amount * msr.oz * m.count_calories".
		"	WHEN msr.weight_or_volume = 'weight' ".
		"		THEN i.calories_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories".
		"	ELSE i.calories_per_cup * i.unit_multiple * m.amount * m.count_calories".
		" END) calories".

        " ,SUM(CASE WHEN msr.weight_or_volume = 'volume' ".
		"	THEN i.protein_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories ".
		"	WHEN msr.weight_or_volume = 'weight'  ".
		"		THEN i.protein_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories ".
		"	ELSE i.protein_grams_per_cup * i.unit_multiple * m.amount * m.count_calories ".
		" END) protein ".

        " ,SUM(CASE WHEN msr.weight_or_volume = 'volume' ".
		"	THEN i.fat_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories ".
		"	WHEN msr.weight_or_volume = 'weight'  ".
		"		THEN i.fat_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories ".
		"	ELSE i.fat_grams_per_cup * i.unit_multiple * m.amount * m.count_calories ".
		" END) fat ".

		" ,SUM(CASE WHEN msr.weight_or_volume = 'volume' ".
		"	THEN i.carb_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories ".
		"	WHEN msr.weight_or_volume = 'weight'  ".
		"		THEN i.carb_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories ".
		"	ELSE i.carb_grams_per_cup * i.unit_multiple * m.amount * m.count_calories ".
		" END) carbs ".

		" from recipe r ".
	    " join step s on s.recipe_id = r.id ".
	    " join material m on m.step_id = s.id ".
	    " join ingredient i on i.id = m.ingredient_id ".
	    " LEFT JOIN measure msr ON m.measure_id = msr.short_name".


	    // elemental where clauses go here
	    (
	    	isset($_GET["search_string"]) ? 
	    	" where r.id in (select * from ".$view_name.")" : 
	    	""
	    ).

	    " group by r.id) summ ".
	    $where.
	    // summative where clauses goes here

	    (
	    	isset($_GET["macro_order"]) ? 
	    	(" order by ifnull(summ.".$_GET["macro_order"]." / (summ.protein + summ.fat + summ.carbs),0) ".$_GET["sort_basis"]) : 
	    	""
	    ).

	    " limit ".(isset($_GET["limit"]) ? $_GET["limit"] : 20).
	    " offset ".(isset($_GET["offset"]) ? $_GET["offset"] : 0).
	    ";";

	open_connection();
	$connect = chained_interval();
	if (isset($_GET["search_string"])) view_instantiation($open_view);
	$search_result = execute_query($search_query);
	$execute = chained_interval();
	close_connection();

	$search_result_html = generate_search_results($search_result);

	$query_stats = json_encode([
		"connect" => $connect,
		"execute" => $execute
	]);






	//functions should be in a separate library

	function generate_search_results($s_r) {

		$results = "";

		foreach ($s_r as $s) {
			$results .= generate_search_result($s);
		}

		return $results;
	}

	function generate_search_result($s) {
		
		global $is_builder;
		
		$n = str_replace("%20","-",str_replace(" ","-",$s["recipe_name"]));
		
		$total =  "<a class='search-result' href='http://raasipe.com/recipes/".$n."' target='_blank' data-recipe-name='".urldecode($s["recipe_name"])."'>".
			($is_builder ? "<div class='builder-checkbox'></div>" : "").
			"<p class='search-result-title'>".ucwords(urldecode($s["recipe_name"]))."</p>".
			"<p class='search-result-calories'>".
				//(round($s["calories"]/10)*10)." cal"." (".
				(round($s["calories"]/10 / $s["makes_or_serves_amount"])*10).
				" / ".($s["makes_or_serves"] == "serves" ? "person" : $s["makes_or_serves_measure_id"]).
				//")".
			"</p>".
			"<div class='search-result-macros'>";

		if ($s["protein_proportion"] + $s["fat_proportion"] + $s["carbs_proportion"] == 0) {
			$total .= "<div class='macros-slider null' style='width: 100%;'></div>";
		} else {
			$total .= "<div class='macros-slider protein' style='width: ".round($s["protein_proportion"]*100,1)."%;'>"."</div>".
			"<div class='macros-slider fat' style='width: ".round($s["fat_proportion"]*100,1)."%;'>"."</div>".
			"<div class='macros-slider carbs' style='width: ".round($s["carbs_proportion"]*100,1)."%;'>"."</div>";
		}
				
		$total .= "</div>".
			"</a>";

		return $total;
	}

	function generate_dietary_ui() {
		$html = "";

		foreach (display_qualities() as $q) {
			$html .= generate_dietary_slider($q);
		}
		return $html;
	}

	function generate_dietary_slider($q) {

		global $_GET;
		global $dietary_map;
		$checked = isset($_GET[$q]) ? $_GET[$q] : FALSE;
		$checked = $checked ? "checked" : "";

		return 	"<div class='slider-wrapper'>".
				"	<label class='switch'>".
				"		<input type='checkbox' ".
				"			class='dietary-checkbox' ".
				"			data-dietary-quality='".$q."' ".
				"			onchange='dietary_search(this)'".
				"			".$checked.
				"			>".
				"		<span class='slider round'></span>".
				"	</label>".
				"	<p>".
					(isset($dietary_map[$q]) ? $dietary_map[$q] : ucwords(str_replace("_", " ", $q))).
					"</p>".
				"</div>";
	}
	
	function generate_course_ui() {
		$html = "";

		foreach (['main','side','soup','salad','beverage','dessert'] as $c) {
			$html .= generate_course_slider($c);
		}
		return $html;
	}

	function generate_course_slider($c) {

		global $_GET;
		$checked = isset($_GET["course"]) ? ($_GET["course"] == $c ? "checked" : "") : "";

		return 	"<div class='slider-wrapper'>".
				"	<label class='switch'>".
				"		<input type='checkbox' ".
				"			class='course-checkbox' ".
				"			data-course='".$c."' ".
				"			onchange='course_search(this)'".
				"			".$checked.
				"			>".
				"		<span class='slider round'></span>".
				"	</label>".
				"	<p>".ucwords($c)."</p>".
				"</div>";
	}
	
	$dietary_ui = generate_dietary_ui();
	$course_ui = generate_course_ui();

	if (isset($_GET["ajax"])) {
		// should really be a well-formatted payload object
		if ($_GET["ajax"] == 1 && ! isset($_GET["ajax_recipes_only"])) echo json_encode([
			"query_stats" => $query_stats,
			"search_result_html" => $search_result_html,
			"data" => $search_result
		]);
	}
?>
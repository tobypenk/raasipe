<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$base_path = "../";
	//echo getcwd();
	if (isset($_GET["ajax"])){
		if ($_GET["ajax"] == 1) {
			$base_path = "../../../";
		}
	}
	
	include_once $base_path."php/libraries/db_functions.php";
	include_once $base_path."php/libraries/php_helpers.php";
	include_once $base_path."php/libraries/generate_html.php";
	
	
	include_once "saved_groceries.php";
	
	
	

	
	if (isset($_GET["saved_recipes"])) {
		$q = $_GET["saved_recipes"];
	} else {
		$q = [];
	}
	



	
	open_connection();
	
	
	$g = execute_query(select_groceries($q));
	$g_r = rationalize_grocery_list($g);
	$g_t = textualize_grocery_list($g_r);
	$g_html = generate_groceries($g_r);
	
	$e = execute_query(select_equipment($q));
	$e_t = textualize_equipment($e);
	$e_html = generate_equipment_badges($e);
	
	close_connection();
	
	
	function select_groceries($recipe_name) {
		
		$recipe_name = str_replace(" ", "-", str_replace("%20", "-", $recipe_name));
		$recipe_name = implode("','", $recipe_name);
		
		$query = "select ".
				" sum(msr.oz * m.amount) oz ".
				" ,i.id ingredient_id".
				" ,m.size ingredient_size".
				" ,sum(m.amount) ingredient_amount ".
				" ,m.measure_id measure_abbreviation".
				" ,replace(replace(i.singular_name,'-',' '),'%20',' ') ingredient_singular ".
				" ,replace(replace(i.supermarket_section,'-',' '),'%20',' ') ingredient_supermarket_section".
			" from recipe r ".
			" join step s on s.recipe_id = r.id ".
			" join material m on m.step_id = s.id ".
			" join ingredient i on m.ingredient_id = i.id".
			" left join measure msr on m.measure_id = msr.short_name".
			" where replace(replace(r.name,' ','-'),'%20','-') in ('".$recipe_name."')".
			" group by ".
				" m.measure_id ".
				" ,i.singular_name ".
				" ,i.supermarket_section ".
			" order by i.singular_name, oz desc;";
			
		return $query;
	}
	
	function select_equipment($recipe_name) {
		
		$recipe_name = str_replace(" ", "-", str_replace("%20", "-", $recipe_name));
		$recipe_name = implode("','", $recipe_name);
		
		$query = "select count(e.id) number_needed".
				" ,replace(replace(e.device_name,'-',' '),'%20',' ') device_name ".
				" ,e.purchase_link".
				" ,e.rich_purchase_link".
				" ,e.native_purchase_link ".
			" from equipment e".
			" join recipe_equipment re on re.equipment_id = e.id ".
			" join recipe r on re.recipe_id = r.id ".
			" where replace(replace(r.name,' ','-'),'%20','-') in ('".$recipe_name."') group by e.id;";
		
		return $query;
	}
	
	function rationalize_grocery_list($g) {
		
		// cases: the measures are all in oz; the measures are all 0-oz; the measures are mixed between oz and 0-oz
		
		//$msr = execute_query("select * from measure;");
		$items = array_unique_better(col_from_arr("ingredient_singular",$g));
		//echo json_encode($items);
		$total = [];
		
		foreach ($items as $item) {
			$entries = array_values(array_filter($g,function($x) use ($item) {return $x["ingredient_singular"] == $item;}));
			//echo json_encode($entries);
			
			$oz_entries = array_reduce(
				array_map(function($x){return $x["oz"] > 0 ? 1 : 0;},$entries),
				function($x,$y){return $x + $y;}
				);
			
			if (count($entries) == 1) {
				//echo "one-entry: ".$item;
				array_push($total,$entries[0]);
			} else if ($oz_entries == count($entries)) {
				// all values are oz
				$total_qty = array_reduce($entries,function($x,$y){return $x+$y["oz"];});
				$entries[0]["ingredient_amount"] *= ($total_qty/$entries[0]["oz"]);
				array_push($total,$entries[0]);
				
			} else if ($oz_entries == 0) {
				// no values are oz
				//echo "no-oz: ".$item;
				$entries[0]["measure_abbreviation"] = "pkg";
				$entries[0]["ingredient_amount"] = "1";
				array_push($total,$entries[0]);
			} else {
				// values are mixed
				$entries[0]["measure_abbreviation"] = "pkg";
				$entries[0]["ingredient_amount"] = "1";
				array_push($total,$entries[0]);
			}
		}
		//echo json_encode($total);
		return $total;
	}
	
	function textualize_grocery_list($g) {
		$sections = array_unique_better(col_from_arr("ingredient_supermarket_section",$g));
		//echo json_encode($sections);
		$str = [];
		
		foreach ($sections as $section) {
			array_push($str," ");
			array_push($str,strtoupper($section)." SECTION");
			
			$ings = array_values(array_filter($g,function($x) use ($section){return $x["ingredient_supermarket_section"] == $section;}));
			foreach ($ings as $ing) {
				array_push($str,grocery_to_text($ing));
			}
		}
		return implode("<br/>",$str);
	}
	
	function grocery_to_text($g) {
		return convert_float_to_fraction($g["ingredient_amount"])." ".
			$g["measure_abbreviation"]." ".
			$g["ingredient_singular"];
	}
	
	
	
	function textualize_equipment($e) {
		$str = "";
		
		foreach ($e as $device) {
			$str .= "<a href='".urldecode($device["purchase_link"])."' target='_blank'>".
				$device["device_name"].
				($device["number_needed"] == 1 ? "" : " X".$device["number_needed"]).
			"</a>";
		}
		return $str;
	}
	

	

	if (isset($_GET["ajax"])) {
		if ($_GET["ajax"] == 1) {
			echo json_encode([
				"groceries" => [
					"html" => count($q) == 0 ? "" : $g_html["html"],
					"data" => count($q) == 0 ? "" : $g_html["data"],
					"text" => count($q) == 0 ? "" : $g_t
				],
				"equipment" => [
					"html" => $e_html,
					"text" => $e_t
				]
			]);
		}
	}
	


	
?>















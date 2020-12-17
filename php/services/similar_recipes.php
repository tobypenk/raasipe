<?php

	$recipe_id = isset($_GET["recipe_id"]) ? $_GET["recipe_id"] : (isset($recipe_id) ? $recipe_id : 5);
	$similar_results = isset($_GET["similar_results"]) ? $_GET["similar_results"] : 10;

	$query = "select ".
	"	case when count(r.id) > 9 then 9 else count(r.id) end + ".
	"		case when r.course=(select course from recipe where id = ".$recipe_id.") then 10 else 0 end overlap".
	"    , r.id recipe_id".
	"    , r.name recipe_name".
	"	 , r.course recipe_course".
	"    from recipe r ".
	"		join step s on s.recipe_id = r.id ".
	"        join material m on m.step_id = s.id ".
	"	where m.ingredient_id in ".
	"		(select m.ingredient_id ingredient_id".
	"			from recipe r ".
	"            join step s on s.recipe_id = r.id ".
	"            join material m on m.step_id = s.id	".
	"            where s.recipe_id = ".$recipe_id.
	"			 and m.ingredient_id not in (2, 11, 34, 38, 65, 59, 464)".
	"		) ".
	"		and s.recipe_id <> ".$recipe_id." ".
	"	group by s.recipe_id ".
	"    order by case when count(r.id) > 9 then 9 else count(r.id) end + ".
	"		case when r.course=(select course from recipe where id = ".$recipe_id.") then 10 else 0 end desc, r.name asc ".
	"	limit ".$similar_results.";";
	
	$similar_recipes = [];

	open_connection();
	$similar_recipes["data"] = execute_query($query);
	close_connection();

 	$similar_recipes["html"] = generate_similar_recipes_html($similar_recipes["data"]);
	function generate_similar_recipes_html($arr) {
		$wrapper = "";
		foreach($arr as $r) {
			$wrapper .= generate_similar_recipe_html($r);
		}
		return $wrapper;
	}

	function generate_similar_recipe_html($r) {
		return "<a class='recipe-link' ".
			"href='https://raasipe.com/recipes/".strtolower(str_replace(" ","-",str_replace("%20", "-", $r["recipe_name"])))."' ".
			"data-recipe-id='".$r["recipe_id"]."' ".
			"data-recipe-name='".str_replace("%20", "-", $r["recipe_name"])."'>".
			ucwords(urldecode($r["recipe_name"])).
		"</a>";
	}

?>





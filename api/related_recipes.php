<?php
	
	include_once "../php/libraries/db_functions.php";
	include "../php/services/similar_recipes.php";
	
	open_connection();
	$base_r = execute_query(
		"select name recipe_name, id recipe_id, course recipe_course from recipe where id = ".$_GET["recipe_id"].";"
	);
	close_connection();
	
	$similar_recipes["base_recipe"] = $base_r[0];
	
	$example_html = "<div class='inline-analysis-results-related-recipes'>".
		"<p class='related-result'>".
			trim(
				"<span class='related-result-component name'>"."recipe"."</span>"." ".
				"<span class='related-result-component course'>"."course"."</span>"." ".
				"<span class='related-result-component id'>"."recipe id"."</span>"
			).
		"</p>";
		
	$example_html .= "<p class='related-result'>".
		trim(
			"<span class='related-result-component name'><strong>searched: ".urldecode($base_r[0]["recipe_name"])."</strong></span>"." ".
			"<span class='related-result-component course'><strong>".$base_r[0]["recipe_course"]."</strong></span>".
			"<span class='related-result-component id'><strong>".$base_r[0]["recipe_id"]."</strong></span>"
		).
	"</p>";
		
	foreach($similar_recipes["data"] as $r) {
			
		$example_html .= "<p class='related-result'>".
			trim(
				"<span class='related-result-component name'>".urldecode($r["recipe_name"])."</span>"." ".
				"<span class='related-result-component course'>".$r["recipe_course"]."</span>".
				"<span class='related-result-component id'>".$r["recipe_id"]."</span>"
			).
		"</p>";
	}
	
	$similar_recipes["example_html"] = $example_html;
	
	if (count($similar_recipes["data"]) == 0) {
		$similar_recipes["example_html"] = "<p class='related-result'>".
			"<strong><em>no recipe found with id = ".$_GET["recipe_id"].".</em></strong>".
		"</p>";
	}
	
	if (isset($_GET["ajax_ingredients_only"])) {
		
	} else {
		echo json_encode([
			"data" => [
				"base_recipe" => $similar_recipes["base_recipe"],
				"matching_recipes" => $similar_recipes["data"]
			]
		]);
	}
	
?>
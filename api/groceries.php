<?php

	//include "../php/services/fetch_recipe.php";
	//include "../php/services/recipe_to_html.php";
	
	include "recipe_base.php";
	
	// to finish: array_values to the lists so they don't rely on i_id
	$api_groceries = $recipe["data"]["groceries"];
	
	foreach ($api_groceries as $k => $v) {
		$api_groceries[$k] = array_values($api_groceries[$k]);
	}
	
	echo json_encode($api_groceries);
	
?>
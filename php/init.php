<?php

	$time = microtime($get_as_float=TRUE);

	$multiplier = isset($_GET["multiplier"]) ? floatval($_GET["multiplier"]) : 1;

	if (isset($_GET["recipe_id"])) {
		$recipe_query_target = [
			"column"=>"r.id",
			"value"=>"'".$_GET["recipe_id"]."'"
		];
	} else if (isset($_GET["recipe_name"])) {
		
		$_GET["recipe_name"] = str_replace("%20"," ",$_GET["recipe_name"]);
		$_GET["recipe_name"] = str_replace("-"," ",$_GET["recipe_name"]);
		$_GET["recipe_name"] = str_replace("+"," ",$_GET["recipe_name"]);
		$_GET["recipe_name"] = str_replace("%27","",$_GET["recipe_name"]);
		
		$recipe_query_target = [
			"column"=>"r.name",
			"value"=> "'".$_GET["recipe_name"]."','".str_replace(" ","%20",$_GET["recipe_name"])."'"
		];
	} else if (isset($recipe_name)) {
		
		$recipe_name = str_replace("%20"," ",$recipe_name);
		$recipe_name = str_replace("-"," ",$recipe_name);
		$recipe_name = str_replace("+"," ",$recipe_name);
		$recipe_name = str_replace("%27","",$recipe_name);
		
		$recipe_query_target = [
			"column"=>"r.name",
			"value"=> "'".$recipe_name."','".str_replace(" ","%20",$recipe_name)."'"
		];
		
	} else {
		$recipe_query_target = [
			"column"=>"r.name",
			"value"=> "'michelada'"
		];
	}
	
	include_once "libraries/db_functions.php";
	include_once "libraries/php_helpers.php";
	include_once "libraries/recipe_calculators.php";
	include_once "libraries/generate_html.php";

	include_once "services/fetch_recipe.php";
	include_once "services/fetch_equipment.php";
	
	include_once "services/similar_recipes.php";

	$query_stats = recipe_query_stats($connect_time,$recipe_query_time,$equipment_query_time);

	$timing = compute_timing($recipe_raw);
	$macros = assess_macros($recipe_raw);
	$dietary = compute_quality_offenders($recipe_raw);
	$yield = compute_yield($recipe_raw,$multiplier);
	$groceries = generate_groceries($recipe_raw);
	$metadata = recipe_metadata($recipe_raw,$yield,$timing);
	$description = trim(urldecode($recipe_raw[0]["recipe_description"]));
	
	$recipe_name = $recipe_raw[0]["recipe_name"];
	
	$title = "<title>".ucwords(urldecode($recipe_raw[0]["recipe_name"]))."</title>";

	include_once "services/recipe_to_html.php";

	if (isset($_GET["ajax"]) && $_GET["ajax"] == 1) {
		echo json_encode($recipe);
	}

	include_once "libraries/json_ld.php";
?>
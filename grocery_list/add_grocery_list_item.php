<?php
	
	$val = $_GET["val"];
	$list_id = $_GET["list_id"];
	
	include_once "../php/libraries/db_functions.php";
	$_GET["ingredient_list"] = $val;
	$_GET["ajax_ingredients_only"] = 1;
	$_GET["base_path"] = "../";
	include_once "../add_recipe/php/services/fuzzy_extract_ingredient.php";
	echo json_encode($ingredients);
	
	$q = "insert into grocery_list_item (`entry`,`list_id`,`guess`,`supermarket_section`) values (".
		"'".$val."'".",".
		"'".$list_id."'".",".
		"'".$ingredients[0]["ingredient_name"]."'".",".
		"'".$ingredients[0]["supermarket_section"]."'".
	");";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
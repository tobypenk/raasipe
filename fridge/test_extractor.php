<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$base_path = "../";
	
	include_once $base_path."php/libraries/db_functions.php";
	
	include_once $base_path."add_recipe/php/libraries/develop_db_payload.php";
	include_once $base_path."add_recipe/php/libraries/constants.php";
    include_once $base_path."add_recipe/php/libraries/html_generators.php";
    include_once $base_path."add_recipe/php/libraries/extractors.php";
    
    include_once $base_path."php/libraries/db_functions.php";
    include_once $base_path."php/libraries/php_helpers.php";
    include_once $base_path."php/libraries/recipe_calculators.php";
    include_once $base_path."php/libraries/generate_html.php";
    
    open_connection();
    $ing_test = $_GET["test_string"];
    $i = guess_nonrecipe_ingredient($ing_test);
    $i["fuzzy"] = fuzzy_extract_structured_ingredient($ing_test);
	close_connection();
	
	echo json_encode($i);
?>
<?php
	
	$ingredient_name = $_GET["ingredient_name"];
	$ingredient_id = $_GET["ingredient_id"];
	$fridge_id = $_GET["fridge_id"];
	
	if (!isset($basepath)) $base_path = "../";
	
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
    $i_guess = guess_nonrecipe_ingredient($ingredient_name);
    
    if (!$i_guess) {
	    $q = "insert into fridge_entry ".
			" (`fridge_id`,`ingredient_name`,`ingredient_id`) ".
			" values ".
			" (".
				"'".$fridge_id."'".
				",'".$ingredient_name."'".
				",'".$ingredient_id."'".
			");";
    } else {
	    $q = "insert into fridge_entry ".
			" (`fridge_id`,`ingredient_name`,`ingredient_id`) ".
			" values ".
			" (".
				"'".$fridge_id."'".
				",'".$i_guess["singular_name"]."'".
				",'".$i_guess["id"]."'".
			");";
    }
    
    //echo $q;
    
	
	
	
	
	$fridge_entry_id = execute_insertion_and_return_insert_id($q,"fridge_entry");
	close_connection();
	
?>






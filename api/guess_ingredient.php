<?php
	
	// take an array of strings
	// return an array of (singular_name, i_id) or (given_string, NULL) depending on
		// successful guess
		
	$ings = $_GET["ingredients"];
	
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
    
    $total = [];
	
	foreach ($ings as $i) {
		echo $i;
/*
		$guess = guess_nonrecipe_ingredient($i);
		if (!$guess) {
			$singular_name = $i;
			$i_id = "";
		} else {
			$singular_name = $guess["singular_name"];
			$i_id = $guess["id"];
		}
		if (!isset($total)) {
			array_push($total,["singular_name"=>$singular_name,"i_id"=>$i_id]);
		}
*/
	}
	
	echo json_encode($total);
	
	
?>
<?php

	if (isset($_GET["base_path"])) {
	    $base_path = $_GET["base_path"];
    } else {
	    $base_path = "../../../";
    }
	
	include_once $base_path."add_recipe/php/services/fuzzy_extract_ingredient.php";
	
	$ing_guess = $payload["ingredients"];
	
?>
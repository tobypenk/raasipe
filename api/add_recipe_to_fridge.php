<?php
	
	$ingredient_ids = $_GET["ingredient_ids"];
	$fridge_id = $_GET["fridge_id"];
	$recipe_id = $_GET["recipe_id"];
	
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
    

    $q1 = "select distinct i.singular_name, i.id from ingredient i join material m on m.ingredient_id = i.id".
    	" join step s on m.step_id = s.id join recipe r on s.recipe_id = r.id where r.id = ".$recipe_id.";";
    	
    $ings = execute_query($q1);


    $q = "insert into fridge_entry ".
		" (`fridge_id`,`ingredient_name`,`ingredient_id`) ".
		" values ";

	
	$q .= implode(
		",",
		array_map(
			function($x) use ($fridge_id)
			{return "('".$fridge_id."','".$x["singular_name"]."','".$x["id"]."')";}, $ings
		)
	);
	$q .= ";";

	execute_insertion($q);
	close_connection();
	
	echo "done";
?>






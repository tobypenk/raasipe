<?php
	
	$clicked_recipe_name = $_GET["clicked_recipe_name"];
	$search_string = $_GET["search_string"];
	
	$q = "insert into search_click ".
		" (search_string,clicked_recipe_name) ".
		" values ".
		" ('".$search_string."','".$clicked_recipe_name."');";
		
	include_once "../../../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
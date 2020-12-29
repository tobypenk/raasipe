<?php
	
	$fridge_name = $_GET["fridge_name"];
	$fridge_id = $_GET["fridge_id"];
	
	$q = "update fridge set name = '".$fridge_name."' where id = '".$fridge_id."';";
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
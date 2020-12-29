<?php
	
	$increment = $_GET["increment"];
	$entry_id = $_GET["entry_id"];
	
	
	$q = " update fridge_entry ".
		" set shelf_life_start = shelf_life_start + interval ".$increment." day ".
		" where id = ".$entry_id.";";
	
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();	
	
?>
<?php
	
	$fridge_entry_id = $_GET["fridge_entry_id"];
	
	$q = "delete from fridge_entry where id = '".$fridge_entry_id."';";
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
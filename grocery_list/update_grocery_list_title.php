<?php
	
	$val = $_GET["val"];
	$list_id = $_GET["list_id"];
	
	$q = "update grocery_list set name = '".$val."' where id = '".$list_id."';";
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
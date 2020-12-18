<?php
	
	$val = $_GET["val"];
	$list_id = $_GET["list_id"];
	
	include_once "../php/libraries/db_functions.php";
	
	$q = "insert into grocery_list_item (`entry`,`list_id`) values ('".$val."','".$list_id."');";
	
	open_connection();
	execute_insertion($q);
	close_connection();
	
?>
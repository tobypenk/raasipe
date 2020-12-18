<?php
	
	$val = $_GET["val"];
	$list_id = $_GET["list_id"];
	
	$q = "delete from grocery_list_item where entry = '".$val."' and list_id = '".$list_id."';";
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	execute_insertion($q);
	close_connection();
?>
<?php
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	$list_id = execute_insertion_and_return_insert_id(
		"insert into grocery_list (id) values (null);",
		"grocery_list"
	);
	close_connection();
	
	echo $list_id;
?>
<?php
	
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	$fridge_id = execute_insertion_and_return_insert_id(
		"insert into fridge (id) values (null);",
		"fridge"
	);
	close_connection();
	
	echo $fridge_id;
?>
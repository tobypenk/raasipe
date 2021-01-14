<?php

	function open_connection() {

		global $conn;

		include "db_credentials.php";

        $conn = new mysqli($host,$user,$pass,$dbname,$port);

        if (!$conn) {
	        die('Could not connect: ' . mysqli_error($conn));
	    }
	}

	function close_connection() {

		global $conn;

		mysqli_close($conn);
	}

	function execute_query($query_string) {
		global $conn;
		
	    $q = mysqli_query($conn,$query_string);
		$result = array();

		while ($row = $q->fetch_assoc()) {
			$result[] = $row;
		}

        return $result;
	}

	function view_instantiation($query_string) {
		global $conn;
		
	    $q = mysqli_query($conn,$query_string);

        return $q;
	}

	function execute_insertion($query_string) {

        global $conn;

	    $q = mysqli_query($conn,$query_string);
	}

	function execute_insertion_and_return_insert_id($query_string,$table) {

		global $conn;

	    $q = mysqli_query($conn,$query_string);
	    $r = mysqli_query($conn,"SELECT max(id) last_insert_id FROM ".$table.";");

		$r_arr = array();

		while ($row = $r->fetch_assoc()) {
			$r_arr[] = $row;
		}

	    return $r_arr[0]["last_insert_id"];
	}

?>
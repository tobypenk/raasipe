<?php
	
	session_start();
	$_SESSION["permission_granted"] = $_GET["permission_granted"];
	echo json_encode($_SESSION);
	
?>
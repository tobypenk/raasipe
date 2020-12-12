<?php

	include "../libraries/db_functions.php";
	include "../libraries/php_helpers.php";
	
	open_connection();
	$r = execute_query(
		"select lower(replace(replace(replace(name,' ','%20'),'%20','-'),'%27','')) name from recipe;"
	);
	close_connection();
	echo json_encode($r);
	$dirs = array_values(
		array_filter(
			scandir("../../recipes"),
			function($x){
				return $x != "." && $x != "..";
			}
		)
	);
	
	$exists = [];
	$not_exists = [];
	
	foreach ($r as $recipe) {
		$c = FALSE;
		foreach ($dirs as $dir) {
			if ($dir == $recipe["name"]) {
				array_push($exists, $recipe["name"]);
				$c = TRUE;
				break;
			}
		}
		if ($c) continue;
		array_push($not_exists,$recipe["name"]);
	}
	
	foreach ($not_exists as $new_dir) {
		mkdir("../../recipes/".$new_dir);
	}
	
	foreach($r as $recipe) {
		echo "../../recipes/".$recipe["name"];
		copy("template_kernel/index.php","../../recipes/".$recipe["name"]."/index.php");
	}
	
?>
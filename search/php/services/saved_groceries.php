<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$base_path = "../";
	if (isset($_GET["ajax"])){
		if ($_GET["ajax"] == 1) {
			$base_path = "../../../";
		}
	}
	
	include_once $base_path."php/libraries/db_functions.php";
	include_once $base_path."php/libraries/php_helpers.php";
	include_once $base_path."php/libraries/generate_html.php";
	
	$grocery_id = isset($_GET["grocery_id"]) ? $_GET["grocery_id"] : null;
	
	$code_words = [
		"age",
		"can",
		"cut",
		"cup",
		"dip",
		"dry",
		"fry",
		"gel",
		"mix",
		"oil",
		"pan",
		"pit",
		"pop",
		"raw",
		"rub",
		"sip",
		"zap",
		"ahi",
		"eat",
		"ate",
		"fix",
		"gut",
		"ice"
	];
	
	$max_code_phrase_length = 4;
	
	function generate_code_word($n) {
		global $code_words;
		global $max_code_phrase_length;
		
		$c = count($code_words);
		
		$inc = floor($c / $max_code_phrase_length);
		
		$pos = [];
		
		for ($i=$max_code_phrase_length-1; $i>=0; $i--) {
			$b = $c**$i;
			$val = floor($n/$b);
			array_push($pos,($val + $inc * $i) % $c);
			$n = $n - $b * $val;
		}
		
		$pos = array_map(function($x) use ($code_words) {return $code_words[$x];},$pos);
		$pos = implode("-", array_reverse($pos));
		return $pos;
	}
	
	function generate_code_number($s) {
		// to do
	}

//echo json_encode(generate_code_word(340000));







?>








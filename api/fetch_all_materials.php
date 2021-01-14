<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	include_once "../php/libraries/db_functions.php";
	include_once "../php/libraries/php_helpers.php";
	include_once "../php/libraries/recipe_calculators.php";
	
	open_connection();
	$m = execute_query(
		"select * from material m ".
		" left join ingredient i on m.ingredient_id = i.id".
		" left join measure msr on m.measure_id = msr.short_name"
	);
	close_connection();
	
	
	$total = [];
	$t_str = [];
	$t_tok = [];
	
/*
	foreach($m as $i) {
		$ti = [];
		$comma = (is_null($i["preparation"]) | trim($i["preparation"]) == "") ? "" : ",";
		if (!is_null($i["amount"])) array_push($ti,convert_float_to_fraction($i["amount"]));
		if (!is_null($i["short_name"])) array_push($ti,$i["short_name"]);
		if (!is_null($i["singular_name"])) array_push($ti,$i["singular_name"].$comma);
		if (!is_null($i["preparation"])) array_push($ti,$i["preparation"]);
		
		array_push($total,trim(implode(" ", $ti)));
	}
*/
	
	// quantity = 0, measure = 1, type = 2, ingredient = 3, prep = 4
	// could potentially add additional category for measure type; e.g. 'small clove'
	foreach($m as $i) {
		
		$ti = [];
		$ti_str = [];
		$ti_tok = [];
		
		$comma = (is_null($i["preparation"]) | trim($i["preparation"]) == "") ? "" : ",";
		
		if (!is_null($i["amount"])) {
			$amt = convert_float_to_fraction($i["amount"]);
			array_push($ti,$amt);

			$tmp_str = explode(" ", $amt);
			foreach ($tmp_str as $str) {
				array_push($ti_str,$str);
				array_push($ti_tok,"CC0");
			}
		}
		
		if (!is_null($i["short_name"]) & trim($i["short_name"]) != "") {
			array_push($ti,$i["short_name"]);
			
			$tmp_str = explode(" ", $i["short_name"]);
			foreach ($tmp_str as $str) {
				array_push($ti_str,$str);
				array_push($ti_tok,"CC1");
			}
		}
		
		if (!is_null($i["size"]) & trim($i["size"]) != "") {
			array_push($ti,$i["size"]);
			
			$tmp_str = explode(" ", $i["size"]);
			foreach ($tmp_str as $str) {
				array_push($ti_str,$str);
				array_push($ti_tok,"CC2");
			}
		}
		
		if (!is_null($i["singular_name"])) {
			array_push($ti,$i["singular_name"].$comma);
			
			$tmp_str = explode(" ", urldecode($i["singular_name"].$comma));
			foreach ($tmp_str as $str) {
				array_push($ti_str,$str);
				array_push($ti_tok,"CC3");
			}
		}
		
		if (!is_null($i["preparation"]) & trim($i["preparation"]) != "") {
			array_push($ti,$i["preparation"]);
			
			$tmp_str = explode(" ", urldecode($i["preparation"]));
			foreach ($tmp_str as $str) {
				array_push($ti_str,$str);
				array_push($ti_tok,"CC4");
			}
		}
		
		
		
		array_push($total,trim(implode(" ", $ti)));
		array_push($t_str,implode(" ",$ti_str));
		array_push($t_tok,implode(" ",$ti_tok));
	}
	
	
?>

<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<p>
			<?php 
				//echo urldecode(implode("<br>",$total)); 
				echo urldecode(implode("<br>",$t_tok));
				 
			?>
		</p>
	</body>
</html>










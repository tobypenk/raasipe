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
	
	foreach($m as $i) {
		$ti = [];
		$comma = (is_null($i["preparation"]) | trim($i["preparation"]) == "") ? "" : ",";
		if (!is_null($i["amount"])) array_push($ti,convert_float_to_fraction($i["amount"]));
		if (!is_null($i["short_name"])) array_push($ti,$i["short_name"]);
		if (!is_null($i["singular_name"])) array_push($ti,$i["singular_name"].$comma);
		if (!is_null($i["preparation"])) array_push($ti,$i["preparation"]);
		
		array_push($total,trim(implode(" ", $ti)));
	}
	
	
?>

<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<p>
			<?php echo urldecode(implode("<br>",$total)); ?>
		</p>
	</body>
</html>
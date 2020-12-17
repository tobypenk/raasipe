<?php

	// can't handle name = needs to pull id from recipe query

	$equipment_query = "SELECT * FROM recipe_equipment re".
		"	JOIN equipment e ON e.id = re.equipment_id".
		"	WHERE re.recipe_id = ".$recipe_id.";";

	open_connection();
	$equipment_raw = execute_query($equipment_query);
	$equipment_query_time = chained_interval();
	close_connection();

?>
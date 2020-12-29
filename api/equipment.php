<?php

	//include "../php/services/fetch_recipe.php";
	//include "../php/services/recipe_to_html.php";
	
	include "recipe_base.php";
	
	// to finish: array_values to the lists so they don't rely on i_id
	echo json_encode($recipe["data"]["equipment"]);
	
	
	
?>
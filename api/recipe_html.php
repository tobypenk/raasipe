<?php

	//include "../php/services/fetch_recipe.php";
	//include "../php/services/recipe_to_html.php";
	
	include "recipe_base.php";
	
	echo json_encode($recipe["html"]);
	
?>
<?php

	$recipe = array(

		"data" => [
			"steps" => array(),
			"nutrition" => array(),
			"equipment" => array(),
			"groceries" => array()
		],

		"html" => [
			"steps" => urlencode(generate_steps($recipe_raw,$multiplier)),
			"groceries" => urlencode(urldecode($groceries["html"])),
			"functions" => urlencode(generate_functions_badges()),
			"header" => urlencode(generate_recipe_header(
				ucwords(urldecode($recipe_raw[0]["recipe_name"])),
				$yield["string"],
				$timing["html"]["active_time_in_minutes"],
				$timing["html"]["inactive_time_in_minutes"],
				generate_source_string($recipe_raw))
			)
		]
	);

	$recipe["data"]["query"] = $query_stats;
	$recipe["data"]["metadata"] = $metadata;

	$recipe["data"]["nutrition"]["macros"] = $macros;
	$recipe["data"]["nutrition"]["dietary"] = array();
	foreach(display_qualities() as $q) {
		$recipe["data"]["nutrition"]["dietary"][$q] = $dietary[$q]["offender_total"] == 0 ? 1 : 0;
	}

	$recipe["data"]["equipment"] = recipe_equipment($equipment_raw);
	
	$recipe["html"]["social_metadata"] = generate_recipe_social_metadata(
		$metadata["recipe_name"],
		urldecode($metadata["recipe_description"]),
		$recipe_raw[0]["recipe_image"] == "" ? 
  			"" :
  			"https://raasipe.com/images/recipes/".str_replace("%20"," ",str_replace(" ","-",$recipe_raw[0]["recipe_image"]))."/medium-res.JPG",
		"https://raasipe.com/recipes/".str_replace("%20"," ",str_replace(" ","-",$recipe_name))
	);

	$recipe["data"]["groceries"] = $groceries["data"];

	$recipe["data"]["steps"] = structure_recipe_steps($recipe_raw);

	$recipe["html"]["nutrition"]["macros"] = urlencode(generate_macros(
		$recipe,
		$yield["type"],
		$yield["amount"],
		$recipe_raw[0]["recipe_makes_or_serves_measure_id_singular"]
	));
	$recipe["html"]["nutrition"]["dietary"] = urlencode(generate_dietary_badges($dietary));
	$recipe["html"]["equipment"] = generate_equipment_badges($recipe["data"]["equipment"]);

	$recipe["data"]["query"]["computation_time"] = round(chained_interval(),6);

	$recipe["data"]["similar_recipes"] = $similar_recipes["data"];
	$recipe["html"]["similar_recipes"] = $similar_recipes["html"];

?>
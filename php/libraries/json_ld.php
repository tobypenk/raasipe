<?php
	
	$excluded_keywords = [
		"to",
		"a",
		"and",
		"the"
	];
	
	$keyword_string = implode(", ",array_keys(array_filter(
		$recipe["data"]["nutrition"]["dietary"],
		function($x){return $x == 1;}
	)));
	$keyword_string = str_replace("_"," ",$keyword_string);
	
	$title_key_string = array_filter(
		explode(" ",strtolower($recipe["data"]["metadata"]["recipe_name"])),
		function($x) use ($excluded_keywords) {return !in_array($x,$excluded_keywords);}
	);
	
	$title_key_string = implode(", ", $title_key_string);
	
	$k = trim($keyword_string . ", " . $title_key_string);
	//echo $k;
	
	$json_ld = array(
		"@context" => "https://schema.org",
  		"@type" => "Recipe",
  		"author" => urldecode($recipe_raw[0]["recipe_source_name"]),
  		"recipeCategory" => $recipe_raw[0]["recipe_course"],
  		"recipeCuisine" => urldecode($recipe_raw[0]["recipe_cuisine"]),
  		"keywords" => $k,
  		"prepTime" => "PT".
  			$recipe["data"]["metadata"]["active_time_in_minutes"].
  			"M",
  		"cookTime" => "PT".
  			$recipe["data"]["metadata"]["inactive_time_in_minutes"].
  			"M",
  		"datePublished" => date($recipe_raw[0]["recipe_created_date"]),
  		"description" => urldecode($recipe_raw[0]["recipe_description"]),
  		"image"=> $recipe_raw[0]["recipe_image"] == "" ? 
  			"https://raasipe.com/images/default/medium.JPG" :
  			"https://raasipe.com/images/recipes/".$recipe_raw[0]["recipe_image"]."/high-res.JPG",
  		"recipeIngredient" => [],
		//"interactionStatistic": {
		//	"@type": "InteractionCounter",
		//	"interactionType": "https://schema.org/Comment",
		//	"userInteractionCount": "140"
		//},
		"name" => $recipe["data"]["metadata"]["recipe_name"],
		"nutrition" => [
			"@type" => "NutritionInformation",
			"calories" => round($recipe["data"]["nutrition"]["macros"]["amounts"]["calories"])." calories",
			"fatContent" => round($recipe["data"]["nutrition"]["macros"]["amounts"]["fat"])." grams fat",
			"carbohydrateContent" => round($recipe["data"]["nutrition"]["macros"]["amounts"]["carbs"])." grams carbohydrate",
			"proteinContent" => round($recipe["data"]["nutrition"]["macros"]["amounts"]["protein"])." grams protein"
		],
		"recipeInstructions" => urldecode(implode(" ",array_values(array_unique(col_from_arr("step_instructions",$recipe_raw))))),
		"recipeYield" => $yield["string"],
		//"suitableForDiet": "https://schema.org/LowFatDiet"
	);

	foreach ($recipe_raw as $m) {
		array_push($json_ld["recipeIngredient"],generate_ingredient_string($m));
	}

	$json_ld = json_encode($json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>
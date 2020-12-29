<?php
	
	if (isset($_GET["diet"])) $_GET[$_GET["diet"]] = 1;
	include "../php/services/search_recipes.php";
	
	for ($i=0; $i<count($search_result); $i++) {
		$search_result[$i]["recipe_name"] = urldecode($search_result[$i]["recipe_name"]);
		
		$search_result[$i]["makes_or_serves_amount"] = round($search_result[$i]["makes_or_serves_amount"],2);
		
		$search_result[$i]["calories"] = round($search_result[$i]["calories"],2);
		$search_result[$i]["protein"] = round($search_result[$i]["protein"],2);
		$search_result[$i]["fat"] = round($search_result[$i]["fat"],2);
		$search_result[$i]["carbs"] = round($search_result[$i]["carbs"],2);
		
		$search_result[$i]["protein_proportion"] = round($search_result[$i]["protein_proportion"],6);
		$search_result[$i]["fat_proportion"] = round($search_result[$i]["fat_proportion"],6);
		$search_result[$i]["carbs_proportion"] = round($search_result[$i]["carbs_proportion"],6);
	}
	

	
	
	
	$html_recipes = "<div class='inline-analysis-results-recipes'>".
		"<p class='recipe-result'>".
			trim(
				"<span class='recipe-result-component name'>"."recipe"."</span>"." ".
				"<span class='recipe-result-component yield'>"."yield"."</span>"." ".
				"<span class='recipe-result-component calories'>"."calories"."</span>".
				"<span class='recipe-result-component protein'>"."protein"."</span>".
				"<span class='recipe-result-component fat'>"."fat"."</span>".
				"<span class='recipe-result-component carbs'>"."carbs"."</span>"
			).
		"</p>";
			
		foreach($search_result as $r) {
			
			$html_recipes .= "<p class='recipe-result'>".
				trim(
					"<span class='recipe-result-component name'>".$r["recipe_name"]."</span>"." ".
					
					"<span class='recipe-result-component yield'>".
						($r["makes_or_serves"] == "serves" ? 
							("serves " . $r["makes_or_serves_amount"]) :
							("makes " . $r["makes_or_serves_amount"] . " " . $r["makes_or_serves_measure_id"])).
					"</span>"." ".
					
					"<span class='recipe-result-component calories'>".(round($r["calories"]/10)*10)."</span>".
					"<span class='recipe-result-component protein'>".round($r["protein"])."g</span>".
					"<span class='recipe-result-component fat'>".round($r["fat"])."g</span>".
					"<span class='recipe-result-component carbs'>".round($r["carbs"])."g</span>"
				).
			"</p>";
			
			
			
			$html_recipes .= "<div class='recipe-qualities'>";
				foreach(display_qualities() as $q) {
					if ($r[$q] == 1) {
						$html_recipes .= "<p class='dietary-result non-offender'>".
							ucwords(str_replace("_"," ",$q)).
						"</p>";
					} else {
						$html_recipes .= "<p class='dietary-result offender'>".ucwords("not ".str_replace("_"," ",$q))."</p>";
					}
				}
			$html_recipes .= "</div>";
		}
			
			

				
		
				
				
				
				
				
				
				/*
				
				"</div>".
					"<div class='inline-analysis-results-macros'>".
						"<div class='macros-calories'>".(round($macros["amounts"]["calories"]/10)*10)." total calories</div>".
						"<div class='macros-header'>".
							"<div class='macros-slider protein' style='width: 33.3%'><p>Protein ".
								round(floatval($macros["proportions"]["protein_proportion"])*100)."%</p>".
								"</div>".
							"<div class='macros-slider fat' style='width: 33.3%'><p>Fat ".
								round(floatval($macros["proportions"]["fat_proportion"])*100)."%</p>".
								"</div>".
							"<div class='macros-slider carbs' style='width: 33.3%'><p>Carbs ".
								round(floatval($macros["proportions"]["carbs_proportion"])*100)."%</p>".
								"</div>".
						"</div>".
						"<div class='macros-values'>".
							"<div class='macros-slider protein' style='width: ".(floatval($macros["proportions"]["protein_proportion"])*100)."%;'></div>".
							"<div class='macros-slider fat' style='width: ".(floatval($macros["proportions"]["fat_proportion"])*100)."%;'></div>".
							"<div class='macros-slider carbs' style='width: ".(floatval($macros["proportions"]["carbs_proportion"])*100)."%;'></div>".
						"</div>".

					//protein
					//fat
					//carbs
					"</div>".
				"</div>";
				*/
				
				
				
				
				
				
				
				
	
	if (isset($_GET["ajax"])) {
		if ($_GET["ajax"] == 1) {
			
			echo json_encode(
				[
					"recipes" => $search_result
					//,"html" => $html_recipes
				]
			);
			
			
		}
	}
	
?>
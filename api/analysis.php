<?php
	
	include "../add_recipe/php/services/extract_ingredient.php";	
	
	
	for ($i=0; $i<count($payload["ingredients"]); $i++) {
		$payload["ingredients"][$i]["calories_per_cup"] = round($payload["ingredients"][$i]["calories_per_cup"],2);
		$payload["ingredients"][$i]["fat_grams_per_cup"] = round($payload["ingredients"][$i]["fat_grams_per_cup"],2);
		$payload["ingredients"][$i]["carb_grams_per_cup"] = round($payload["ingredients"][$i]["carb_grams_per_cup"],2);
		$payload["ingredients"][$i]["protein_grams_per_cup"] = round($payload["ingredients"][$i]["protein_grams_per_cup"],2);
		
		
		$payload["ingredients"][$i]["oz_multiple"] = round($payload["ingredients"][$i]["oz_multiple"],2);
		$payload["ingredients"][$i]["unit_multiple"] = round($payload["ingredients"][$i]["unit_multiple"],2);
	}
	
	
	$payload["macros"]["amounts"]["calories"] = round($payload["macros"]["amounts"]["calories"],3);
	$payload["macros"]["amounts"]["protein"] = round($payload["macros"]["amounts"]["protein"],3);
	$payload["macros"]["amounts"]["fat"] = round($payload["macros"]["amounts"]["fat"],3);
	$payload["macros"]["amounts"]["carbs"] = round($payload["macros"]["amounts"]["carbs"],3);
	
	$payload["macros"]["proportions"]["protein_proportion"] = round($payload["macros"]["proportions"]["protein_proportion"],6);
	$payload["macros"]["proportions"]["fat_proportion"] = round($payload["macros"]["proportions"]["fat_proportion"],6);
	$payload["macros"]["proportions"]["carbs_proportion"] = round($payload["macros"]["proportions"]["carbs_proportion"],6);
	
	
	if (isset($_GET["echo_data"])) {
		if ($_GET["echo_data"] == 1) {
			$echo_payload = $payload;
			unset($echo_payload["html"]);
			echo json_encode($echo_payload);
		}
	}
	
	
?>
<?php
	
	$_GET["limit"] = 1000;
	include "services/search_recipes.php";
	
	
	
	$dietary_cards = [
		array("col" => "vegan", "val" => "1"),
		array("col" => "vegetarian", "val" => "1"),
		array("col" => "gluten_free", "val" => "1"),
		array("col" => "sugar_free", "val" => "1"),
		array("col" => "kosher", "val" => "1"),
		array("col" => "nut_free", "val" => "1"),
		array("col" => "pescatarian", "val" => "1"),
		array("col" => "shellfish_free", "val" => "1"),
		array("col" => "dairy_free", "val" => "1"),
		array("col" => "alcohol_free", "val" => "1")
	];
	
	$course_cards = [
		array("col" => "course", "val" => "main"),
		array("col" => "course", "val" => "soup"),
		array("col" => "course", "val" => "salad"),
		array("col" => "course", "val" => "side"),
		array("col" => "course", "val" => "beverage"),
		array("col" => "course", "val" => "dessert")
	];
	
	$macro_cards = [
		array("col" => "carbs_proportion", "macro_order" => "carbs", "sort_basis" => "asc"),
		array("col" => "carbs_proportion", "macro_order" => "carbs", "sort_basis" => "desc"),
		array("col" => "protein_proportion", "macro_order" => "protein", "sort_basis" => "asc"),
		array("col" => "protein_proportion", "macro_order" => "protein", "sort_basis" => "desc"),
		array("col" => "fat_proportion", "macro_order" => "fat", "sort_basis" => "asc"),
		array("col" => "fat_proportion", "macro_order" => "fat", "sort_basis" => "desc")
		
	];
	
	$card_data = [];
	
	for ($i=0; $i<4; $i++) {
		$d_rand = array_rand($dietary_cards);
		$d_target = $dietary_cards[$d_rand];
		$d_data = array_slice(
			array_values(
				array_filter(
					$search_result,
					function($x) use ($d_target) {return $x[$d_target["col"]] == $d_target["val"];}
				)
			),0,5,true
		);
		array_push(
			$card_data,
			[
				"data" => $d_data,
				"title" => str_replace("_","-",$d_target["col"])." stuff -->",
				"href" => "https://raasipe.com/search/?".$d_target["col"]."=".$d_target["val"],
				"data-attribute" => "dietary",
				"data-attribute-value" => $d_target["col"]
			]
		);
		unset($dietary_cards[$d_rand]);
		
		$c_rand = array_rand($course_cards);
		$c_target = $course_cards[$c_rand];
		$c_data = array_slice(
			array_values(
				array_filter(
					$search_result,
					function($x) use ($c_target) {return $x[$c_target["col"]] == $c_target["val"];}
				)
			),0,5,true
		);
		array_push(
			$card_data,
			[
				"data" => $c_data,
				"title" => $c_target["val"]."s -->",
				"href" => "https://raasipe.com/search/?".$c_target["col"]."=".$c_target["val"],
				"data-attribute" => "course",
				"data-attribute-value" => $c_target["col"]
			]
		);
		unset($course_cards[$c_rand]);
	}
	
	function generate_home_cards($arr) {
		$html = "";
		
		foreach ($arr as $c) {
			$html .= generate_home_card($c);
		}
		
		return $html;
	}
	
	function generate_home_card($c) {
		
		$html = "<div class='home-tag-card' data-".$c["data-attribute"]."='".$c["data-attribute-value"]."'>".
				"<a href='".$c["href"]."'><h2>".$c["title"]."</h2></a>";
				
			foreach ($c["data"] as $r) {
				$html .= generate_recipe_card_ribbon($r,$c["href"]);
			}
				
		$html .= "</div>";
			
		return $html;
	}
	
	function generate_recipe_card_ribbon($r,$l) {
		return "<a href='https://raasipe.com/recipes/".str_replace(" ","-",urldecode($r["recipe_name"]))."'>".
			ucwords(urldecode($r["recipe_name"])).
			"</a>";
	}	
	
	$home_cards = generate_home_cards($card_data);

?>
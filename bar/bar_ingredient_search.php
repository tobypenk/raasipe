<?php
	
	include_once "../php/libraries/db_functions.php";
	$i_ids = isset($_GET["i_ids"]) ? $_GET["i_ids"] : [];
	open_connection();
		
		
	if (isset($_GET["ingredient_like"])) {
		$v = $_GET["ingredient_like"];
	
		$q = "SELECT id".
			" FROM ingredient ".
			" WHERE replace(singular_name,'%20',' ') REGEXP '[[:<:]]".$v."[[:>:]]' ".
				" AND texture in ('liquid','fizzy','liquor')";
		$r = execute_query($q);
		for ($i=0; $i<count($r); $i++) {
			array_push($i_ids, $r[$i]["id"]);
			
		}
	}
	
	$i_ids = array_values(array_unique($i_ids));
	
	
	$i_q = "select singular_name,id from ingredient where id in ('".implode("','",$i_ids)."')";
	
	$i_full = execute_query($i_q);
	for ($i=0; $i<count($i_full); $i++) {
		$i_full[$i]["singular_name"] = urldecode($i_full[$i]["singular_name"]);
	}

	
	
	$html = "";
	for ($i=0; $i<count($i_full); $i++) {
		$html .= "<div class='saved-ingredient' data-ingredient-id='".$i_full[$i]["id"]."'>".
			"<p>".$i_full[$i]["singular_name"]."</p>".
		"</div>";
	}
	
	
	
	
	
	$r_q = "select r.name, t.total_ingredients".
		" from recipe r ".
		" join step s on s.recipe_id = r.id ".
		" join material m on m.step_id = s.id ".
		" join (".
			" select ".
			" count(distinct m.ingredient_id) total_ingredients".
			", s.recipe_id ".
			" from step s join material m on m.step_id = s.id join ingredient i on m.ingredient_id = i.id".
			//" where i.supermarket_section not in ('baking','condiments','oil%20/%20vinegar','household')".
			" group by s.recipe_id".
		") t on t.recipe_id = r.id".
		" where m.ingredient_id in ('".implode("','",$i_ids)."') ".
			" and m.ingredient_id not in (71)".
			" and course = 'beverage' ".
			" order by r.name".
			";";
	$recipes = execute_query($r_q);
	
	
	
	
	
	
	$r_hit = [];
	
	for ($i=count($recipes)-1; $i>=0; $i--) {
		if (isset($r_hit[$recipes[$i]["name"]])) {
			$r_hit[$recipes[$i]["name"]] = $r_hit[$recipes[$i]["name"]] + 1;
			unset($recipes[$i]);
		} else {
			$r_hit[$recipes[$i]["name"]] = 1;
		}
	}
	
	
	for ($i=0; $i<count($recipes); $i++) {
		$recipes[$i]["matches"] = $r_hit[$recipes[$i]["name"]];
		$recipes[$i]["total_ingredients"] = intval($recipes[$i]["total_ingredients"]);
		
		$recipes[$i]["suitability_score"] = $recipes[$i]["matches"] * 1 - $recipes[$i]["total_ingredients"];
	}
	
	usort($recipes,function($x,$y) {
		
		//return ($x["total_ingredients"] - $x["matches"]) > ($y["total_ingredients"] - $y["matches"]);
		
		//most matches
		//return $x["matches"] < $y["matches"];
		
		//matches worth a multiple of total-ingredient penalty
		return $x["suitability_score"] < $y["suitability_score"];
	});
	
	$recipes = array_values(array_filter($recipes,function($x){return !is_null($x["matches"]);}));


	$r_html = "<table><tr><th>drink</th><th>matches</th></tr>";
	for ($i=0; $i<count($recipes); $i++) {
		
		$r_html .= "<tr>".
			"<td><a ".
				" href='https://www.raasipe.com/recipes/".str_replace("'","",str_replace(" ","-",urldecode($recipes[$i]["name"])))."' ".
				" class='recipe-result' ".
				" target='_blank'".
			">".
				urldecode($recipes[$i]["name"]).
			"</a></td>".
			"<td>".$recipes[$i]["matches"]." / ".$recipes[$i]["total_ingredients"]."</td>".
		"</tr>";
		
		
	}
	$r_html .= "</table>";
	
	close_connection();
	
	
	
	
	
	
	$payload = [
		"data" => [
			"ingredient" => $i_full,
			"recipe" => $recipes,
			"i_ids" => $i_ids,
			"query" => $r_q,
			"r_hit" => $r_hit
		],
		"html" => [
			"ingredient" => $html,
			"recipe" => $r_html
		]
	];
	
	if (count($i_ids) == 0) {
		$payload["data"]["recipe"] = [];
		$payload["html"]["recipe"] = "";
	}
		
	echo json_encode($payload);
	
?>









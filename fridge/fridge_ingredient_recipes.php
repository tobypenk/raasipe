<?php
	
	$i_id = $_GET["i_id"];
	$i_ids = $_GET["i_ids"];
	$fridge_id = $_GET["fridge_id"];
	
	$q = "select * from recipe r join (".
		" select distinct r.id id from recipe r".
		" join step s on s.recipe_id = r.id".
        " join material m on m.step_id = s.id".
        " where m.ingredient_id = '".$i_id."'".
		" ) i on i.id = r.id;";
	
	include_once "../php/libraries/db_functions.php";
		

	
	function subquery($i_id) {
		$q = "select r.id, r.name".
		"	, i.i_id i_id".
		"	from recipe r join (".
		"	select distinct r.id id ".
		"		, case when 1=1 then '".$i_id."' else '' end i_id".
		"		from recipe r".
		"		join step s on s.recipe_id = r.id".
		"		join material m on m.step_id = s.id".
        "       join ingredient i on m.ingredient_id = i.id".
		"		where substitutes_as in (select substitutes_as from ingredient where id = ".$i_id.")".
		") i on i.id = r.id";
		
		return($q);
	}
	
	$i_ids_ordered = [$i_id];
	for ($i=0; $i<count($i_ids); $i++) {
		if (!in_array($i_ids[$i],$i_ids_ordered)) array_push($i_ids_ordered, $i_ids[$i]);
	}

	$q = "select count(id) matches, id, i_id, name from (".
	
	implode(" union ",array_map(function($x) {return subquery($x);}, $i_ids_ordered)).
	
	") x group by id order by matches desc;";
	
	open_connection();
	$r = execute_query($q);
	close_connection();
	
	
	$uses_ingredient = array_column(array_values(array_filter($r,function($x) use ($i_id){
		return $x["i_id"] == $i_id;
	})),"id");
	
	//echo json_encode($r);
	
	
	$r = array_values(array_filter($r,function($x) use ($uses_ingredient) {
		return in_array($x["id"], $uses_ingredient);
	}));
	
	
	

	
	$html = "<div class='recipe-list'>".
		"<table>".
			"<tr>".
				"<th>recipe</th>".
				"<th>fridge usage</th>".
			"</tr>";
	
	for ($i=0; $i<count($r); $i++) {
		$html .= "<tr>".
				"<td>".
					"<a ".
							" href='https://raasipe.com/recipes/".str_replace(" ","-",strtolower(urldecode($r[$i]["name"])))."?fridge_id=".$fridge_id."'".
							" target='_blank'".
						">".
							urldecode($r[$i]["name"]).
						"</a>".
				"</td>".
				"<td>".$r[$i]["matches"]."</td>".
			"</tr>";
	}
	
	$html .= "</table></div>";
	
	
	
	
	
	
	
	
	$payload = [
		"data" => $r,
		"html" => $html,
		"fridge_html" => $fridge_html,
		"query" => $q
	];
	
	
	
	echo json_encode($payload);
	
	
	
	
	
	
	//echo json_encode($r);
	
	
?>
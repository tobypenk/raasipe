<?php
	
	// To do: update CSS classes
	
	include_once "../php/libraries/db_functions.php";
	$fridge_id = $_GET["fridge_id"];
		
	open_connection();
	$l = execute_query("select * from fridge where id = ".$fridge_id.";");
	$r = execute_query(
		"select fe.*".
		", -TIMESTAMPDIFF(DAY,current_timestamp(),fe.shelf_life_start) age ".
		", i.shelf_life_days + TIMESTAMPDIFF(DAY,current_timestamp(),fe.shelf_life_start) remaining_shelf_life".
		", i.shelf_life_days".
		" from fridge_entry fe".
		" left join ingredient i on i.id = fe.ingredient_id".
		" where fridge_id = ".$fridge_id.
		" order by ".
			" case when i.shelf_life_days = -1 then 10000 else remaining_shelf_life end asc ".
			",remaining_shelf_life asc".
			",age desc".
			",i.singular_name".
		";"
	);
	close_connection();
	
	
	$html = "<div class='fridge' data-fridge-id='".$fridge_id."'>".
		"<input class='fridge-name' placeholder='title your fridge' value='".$l[0]["name"]."'/>".
		"<div class='add-item'>".
			"<input ".
				" class='add-item-input' ".
				" type='text' ".
				" placeholder='add an item' ".
				" onkeypress='handle_keypress(event)' ".
				" data-fridge-id='".$fridge_id."'".
			" />".
			"<div class='add-item-button'></div>".
		"</div>";
	
	
	
	
		
	$fridge_html = "<div class='fridge-items'>".
		"<table>".
		"<tr>".
			"<th></th>".
			"<th>thing</th>".
			"<th>age</th>".
			"<th>days left</th>".
		"</tr>";
	
	
	
	
	
	for ($i=0; $i<count($r); $i++) {
		
		$fridge_html .= "<tr data-entry-id='".$r[$i]["id"]."'>".
			"<td>"."<div class='remove-item' data-entry-id='".$r[$i]["id"]."'></div>"."</td>".
			"<td class='fridge-ingredient' data-ingredient-id='".$r[$i]["ingredient_id"]."'>".urldecode($r[$i]["ingredient_name"])."</td>".
			"<td>".$r[$i]["age"]."</td>".
			"<td>".
				"<span ".
					" class='increment-age down' ".
					" data-increment-value=-1 ".
					" data-entry-id='".$r[$i]["id"]."'>< </span>".
				($r[$i]["shelf_life_days"] == -1 ? "--" : $r[$i]["remaining_shelf_life"]).
				"<span ".
					" class='increment-age up' ".
					" data-increment-value=1 ".
					" data-entry-id='".$r[$i]["id"]."'> ></span>".
			"</td>";
	
		$fridge_html .= "</tr>";
	}
	
	$fridge_html .= "</table></div></div>";
	
	
	$html .= $fridge_html;

		
	$payload = [
		"html" => $html,
		"fridge_html" => $fridge_html,
		"data" => $r,
		"fridge_id" => $fridge_id
	];
	
	if (isset($prevent_echo)) {

	} else {
		echo json_encode($payload);
	}
	

?>














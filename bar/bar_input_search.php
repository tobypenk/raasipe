<?php
	
		
	$v = $_GET["ingredient_like"];
	
	$q = "SELECT singular_name, id ".
		"	FROM ingredient  ".
		"	WHERE replace(singular_name,'%20',' ') = '".$v."'  ".
		"		AND texture in ('liquid','fizzy','liquor') ".
		"		AND id not in (71)".
		"	union ".
		" SELECT singular_name, id ".
		"	FROM ingredient  ".
		"	WHERE replace(singular_name,'%20',' ') REGEXP '^".$v."[[:>:]]'  ".
		"		AND texture in ('liquid','fizzy','liquor') ".
		"		AND id not in (71)".
		"	union ".
		" SELECT singular_name, id ".
		"	FROM ingredient  ".
		"	WHERE replace(singular_name,'%20',' ') REGEXP '[[:<:]]".$v."[[:>:]]'  ".
		"		AND texture in ('liquid','fizzy','liquor') ".
		"		AND id not in (71)".
		"	UNION  ".
		"		select singular_name, id ".
		"		from ingredient  ".
		"		WHERE replace(singular_name,'%20',' ') LIKE '%".$v."%'  ".
		"		AND texture in ('liquid','fizzy','liquor')".
		"		AND id not in (71)".
		";";
		
	include_once "../php/libraries/db_functions.php";
	
	open_connection();
	$r = execute_query($q);
	close_connection();
	
	$html = "";
	for ($i=0; $i<count($r); $i++) {
		$html .= "<div class='ingredient-option ".($i == 0 ? "on-deck" : "")."' data-ingredient-id='".$r[$i]["id"]."'>".
			"<p>".urldecode($r[$i]["singular_name"])."</p>".
		"</div>";
	}
	
	$payload = [
		"data" => [
			"ingredient" => $r
		],
		"html" => [
			"ingredient" => $html
		]
	];
	
	//echo $q;
		
	echo json_encode($payload);
	
?>









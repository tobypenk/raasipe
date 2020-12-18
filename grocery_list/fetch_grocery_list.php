<?php
	
	include_once "fetch_user_lists.php";
	$list_id = $_GET["list_id"];
		
	open_connection();
	$l = execute_query("select * from grocery_list where id = ".$list_id.";");
	$r = execute_query("select * from grocery_list_item where list_id = ".$list_id." order by entry;");
	close_connection();
	
	
	$html = $user_lists_html;
	
	$html .= "<div class='grocery-list' data-list-id='".$list_id."'>".
		"<input class='grocery-list-title' placeholder='title your list' value='".$l[0]["name"]."' >".
		"<div class='add-item'>".
			"<input ".
				" class='add-item-input' ".
				" type='text' ".
				" placeholder='add an item' ".
				" onkeypress='handle_keypress(event)' ".
				" data-list-id='".$list_id."'".
			">".
			"<div class='add-item-button'></div>".
		"</div>".
		"<div class='list-items'></div>".
	"</div>";
	
	if (isset($_GET["ajax"])) {
		echo json_encode([
			"html" => $html,
			"data" => $r,
			"list_id" => $list_id,
			"cookie" => $_COOKIE,
			"lists" => unserialize($_COOKIE["list_ids"])
		]);
	} else {
		echo $html;
	}
	
?>
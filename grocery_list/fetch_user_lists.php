<?php
	
	session_start();
	include_once "../php/libraries/db_functions.php";
	$list_id = isset($_GET["list_id"]) ? $_GET["list_id"] : null;
	
	if (!isset($_COOKIE["list_ids"])) {
		$prior_list = [$list_id];
	} else {
		$prior_list = unserialize($_COOKIE["list_ids"]);
		if (!in_array($list_id, $prior_list)) array_push($prior_list, $list_id);
	}
	
	setcookie("list_ids", serialize($prior_list), time()+60*60*24*30);
	
	open_connection();
	$lists = execute_query("select * from grocery_list where id in ('".implode("','",unserialize($_COOKIE["list_ids"]))."');");
	
	$user_lists_html = "<div class='grocery-list-hud'>".
		"<p class='your-lists-title'>your grocery lists:</p>";
	
	if (count($lists) == 0) {
		$user_lists_html .= "<p class='no-lists'>you have no saved grocery lists.</p>";
	} else {
		foreach ($lists as $l) {
			$user_lists_html .= "<p class='saved-list'>".
				($l["id"] == $list_id ? "" : "<a href='https://www.raasipe.com/grocery_list?list_id=".$l["id"]."'>").
					(is_null($l["name"]) ? "unnamed list" : $l["name"]).
				($l["id"] == $list_id ? "" : "</a>").
			"</p>";
		}
	}
	
	$user_lists_html .= "<div class='create-new-grocery-list'>".
		"<p>".
			(is_null($list_id) ? 
				"Click here to create a new shared grocery list. Share it with others by sending them the link." :
				"Create new list"
				).
		"</p>".
		"</div></div>";
?>
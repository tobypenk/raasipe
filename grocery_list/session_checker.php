<?php
	
	session_start();
	$cookie_permission = false;
	
	if (isset($_COOKIE["raasipe"])) {
		if ($_COOKIE["raasipe"] == 1) {
			$cookie_permission = true;
		}
	}
	
	if ($cookie_permission) {
		
		include_once "fetch_user_lists.php";
		
		if (isset($_GET["list_id"])) {
			include "fetch_grocery_list.php";
		} else {
			echo $user_lists_html;
		}
	} else {
		echo "<div class='cookie-permission-wrapper'>".
			"<p>".
				"To use this service, we need to store the data you provide us as a <a href='http://www.raasipe.com/recipes/chocolate-chip-cookies' target='_blank'>cookie</a>. ".
				"We will never sell or share your data with any third party and will use it only to enable the functionality of this tool. ".
				"If you do not consent to this, please close this page now. ".
				"Otherwise, click the button below.".
			"</p>".
			"<div class='consent-button'><p>Okey dokey</p></div>".
		"</div>";
	}
	
?>


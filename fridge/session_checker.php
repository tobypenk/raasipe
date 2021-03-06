<?php
	
	session_start();
	$cookie_permission = false;
	
	if (isset($_SESSION["permission_granted"])) {
		if ($_SESSION["permission_granted"]) {
			$cookie_permission = true;
		}
	}
	
	if ($cookie_permission) {
		if (isset($_GET["fridge_id"])) {
			include "fetch_fridge.php";
		} else {
			$payload = [
				"html" => "<div class='create-new-fridge'>".
					"<p>Click here to create a new fridge.</p>".
					"</div>"
				];
		}
	} else {
		
		$payload = [
			"html" => "<div class='cookie-permission-wrapper'>".
				"<p>".
					"To use this service, we need to store the data you provide us as a <a href='http://www.raasipe.com/recipes/chocolate-chip-cookies' target='_blank'>cookie</a>. ".
					"We will never sell or share your data with any third party and will use it only to enable the functionality of this tool. ".
					"If you do not consent to this, please close this page now. ".
					"Otherwise, click the button below.".
				"</p>".
				"<div class='consent-button'><p>Okey dokey</p></div>".
			"</div>"
		];
		
		if (isset($prevent_echo)) {
			
		} else {
			echo json_encode($payload);
		}
		
	}
	
	
	
?>
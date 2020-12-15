<?php

	$base_path = "php/";
	include "php/home.php";
	
?>

<!DOCTYPE html>

<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">
		<script src="js/jquerylib.js"></script>

		<link rel="stylesheet" href="css/main.css">


		<script type="application/ld+json">
		{
			"author": "Toby Penk",
			"description": "A cooking and meal planning site with the red-hot power of a trillion dying suns"
		}
		</script>
		<?php include "html_templates/google_analytics.html"; ?>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
		<meta property='description' content='A cooking and meal planning site with the red-hot power of a trillion dying suns'>
		
		<title>RaaSipe</title>

	</head>
	<body>

		<?php include "html_templates/header.html"; ?>
		
		<div class='main-content'>
			<div class='banner-headline'>
				<h1>RaaSipe</h1>
				<h4><em>definitely <span class='ul'>not</span> a culinary AI plotting the overthrow of humanity!</em></h4>
			</div>
			
			<div class='home-tag-cards'>
				<?php echo $home_cards; ?>
			</div>
			
			<?php include "html_templates/footer.html"; ?>
		</div>
		
		
	</body>
</html>
<?php



?>

<html>
	<head>
		<link rel='stylesheet' href='../css/main.css' />
		<link rel='stylesheet' href='../css/bar.css' />

		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">
		<script src="../js/jquerylib.js"></script>
		
		<script type="application/ld+json">
		{
			"author": "Toby Penk",
			"description": "A combination of alchemy, magic, and cheap tricks to turn your liquor cabinet into actual cocktails"
		}
		</script>
		
		<meta property='description' content='A combination of alchemy, magic, and cheap tricks to turn your liquor cabinet into actual cocktails'>
		
		<?php include "../html_templates/google_analytics.html"; ?>
		
		<title>RaaSipe | Bar</title>
	</head>
	<body>
		<?php 
			include "../html_templates/header.html"; 
		?>
		
		<div class='main-content'>
			
			<div class='bar'>
				<div class='bar-search'>
					<div class='bar-search-inputs'>
						<h2>liquor cabinet</h2>
						<div class='bar-search-wrapper'>
							<input 
								class='bar-search-input' 
								
								placeholder="search liquors here" 
								
								autocomplete="off"
								autocorrect="off"
								autocapitalize="off"
								spellcheck="false"
							/>
							<div class='bar-search-results '></div>
							
						</div>
						
						<div class='bar-preset-search-inputs'>
							<h5 style='width: 100%; margin-bottom: 5px;'>click to summon common spirits</h5>
							<div class='preset-pill' data-ingredient-like='vodka'>vodka</div>
							<div class='preset-pill' data-ingredient-like='whiskey'>whiskey</div>
							<div class='preset-pill' data-ingredient-like='gin'>gin</div>
							<div class='preset-pill' data-ingredient-like='tequila'>tequila</div>
							<div class='preset-pill' data-ingredient-like='rum'>rum</div>
							<div class='preset-pill' data-ingredient-like='vermouth'>vermouth</div>
						</div>
					</div>
					<div class='bar-search-selections disappearing-text-basis'></div>
					<p class='disappearing-text'>search some liquors and / or click some presets and your liquor cabinet will grow here (click an ingredient to remove it)</p>
				</div>
				<div class='bar-menu'>
					<h2>menu</h2>
					<div class='bar-search-outputs disappearing-text-basis'></div>
					<p class='disappearing-text'>once you choose some ingredients, links to cocktail formulae will appear here</p>
				</div>
			</div>
			
			
			<?php 
				include "../html_templates/footer.html"; 
			?>
		
		</div>
	</body>
</html>



<script>
	
	var selected_ingredients = [];
	
	$(document).on("click",".preset-pill",function(){
		ingredient_search_preset($(this).attr("data-ingredient-like"));
	});
	
	function ingredient_search_preset(val) {
		
		var i_ids = extract_saved_i_ids();
		
		$.ajax({
			method: "GET",
			url: "bar_ingredient_search.php",
			data: {ingredient_like: val,i_ids: i_ids},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				
				$(".bar-search-selections").html(e.html.ingredient);
				$(".bar-search-outputs").html(e.html.recipe);
			}
		});
	}
	
	function ingredient_search_id(id) {
		
		var i_ids = extract_saved_i_ids();
		i_ids.push(id);
		
		retrieve_ingredient_list(i_ids);
	}
	
	function remove_ingredient(id) {
		var i_ids = extract_saved_i_ids().filter(function(x){return x != id;});
		retrieve_ingredient_list(i_ids);
	}
	
	function retrieve_ingredient_list(i_ids) {
		$.ajax({
			method: "GET",
			url: "bar_ingredient_search.php",
			data: {i_ids: i_ids},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				
				$(".bar-search-results").html("");
				$(".bar-search-selections").html(e.html.ingredient);
				$(".bar-search-outputs").html(e.html.recipe);
				$(".bar-search-input").val("");
			}
		});
	}
	
	function ingredient_search(val) {
		
		$.ajax({
			method: "GET",
			url: "bar_input_search.php",
			data: {ingredient_like: val},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e)
				console.log(e);
				
				if ($(".bar-search-input").val().trim().length == 0) {
					$(".bar-search-results").html("");
				} else {
					$(".bar-search-results").html(e.html.ingredient);
				}
				
			}
		})
	}
	
	
	var timeout = null;
	
	document.onkeyup = function(e) {
		if ($(document.activeElement).hasClass("bar-search-input")) {
			console.log(e.keyCode);
			switch (e.keyCode) {
				case 37: 
					//left
	            case 38: 
	            	//up
	                increment_ingredient_option(-1); 
	                break; 
	            case 39: 
	            	//right
	            case 40: 
	            	//down
	                increment_ingredient_option(1); 
	                break; 
	            case 13:
	            	$(".ingredient-option.on-deck").click();
	            	break;
	            default:
	            	window.clearTimeout(timeout);
			
					timeout = setTimeout(function(){
						ingredient_search($(".bar-search-input").val().toLowerCase().trim())
					}, 300);
            }
			
			
		}
	}
	
	function increment_ingredient_option(inc) {
		
		if (inc == 1) {
			
			if ($(".ingredient-option.on-deck").next().length == 0) {
				
			} else {
				$(".ingredient-option.on-deck").addClass("to-remove");
				$(".ingredient-option.on-deck").next().addClass("on-deck");
				$(".ingredient-option.on-deck.to-remove").removeClass("on-deck");
				$(".ingredient-option.to-remove").removeClass("to-remove");
			}
		} else if (inc == -1) {
			if ($(".ingredient-option.on-deck").prev().length == 0) {
				console.log("cunt");
			} else {
				$(".ingredient-option.on-deck").addClass("to-remove");
				$(".ingredient-option.on-deck").prev().addClass("on-deck");
				$(".ingredient-option.on-deck.to-remove").removeClass("on-deck");
				$(".ingredient-option.to-remove").removeClass("to-remove");
			}
		}
		
		$(".bar-search-results").scrollTop($(".ingredient-option.on-deck").position().top);
		
		
	}

	function extract_saved_i_ids() {
		
		var i_ids = [];
		
		$(".saved-ingredient").each(function() {
			i_ids.push($(this).attr("data-ingredient-id"));
		})
		
		return i_ids;
	}
	
	
	$(document).on("click",".ingredient-option",function() {
		var i_id = $(this).attr("data-ingredient-id");
		ingredient_search_id(i_id);
	});
	
	$(document).on("click",".saved-ingredient",function() {
		var i_id = $(this).attr("data-ingredient-id");
		remove_ingredient(i_id);
	});
	
	
	
</script>












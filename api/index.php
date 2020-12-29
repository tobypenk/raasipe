<?php
	
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);
	
	$_GET["base_path"] = "../";
	$_GET["ajax_ingredients_only"] = 1;
	
	$_GET["ingredient_list"] = 
		"1 lb beef tenderloin".
		"\n3 tbsp unsalted butter";
	
	//include "../add_recipe/php/services/extract_ingredient.php";
	include "analysis.php";
	unset ($payload["html"]);
	
	$_GET["search_string"] = "taco";
	
	include "recipe_search.php";
	
	$_GET["recipe_id"] = 5;
	include "related_recipes.php";
	include "recipe_base.php";
	
	//include "../php/services/search_recipes.php";
?>

<!DOCTYPE html>

<html>
	<head>
		<link rel='stylesheet' href='../css/main.css' />

		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">
		<script src="../js/jquerylib.js"></script>
		
		<?php include "../html_templates/google_analytics.html"; ?>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
		
		<title>RaaSipe | API</title>
	</head>
	<body>
		
		<?php 
			include "../html_templates/header.html"; 
		?>
		
		<div class='main-content'>
			<div class='api-header'>
				<div class='api-menu-button nutrition'></div>
				<div class='api-menu-button query'></div>
				<!--<div class='api-menu-button prepare'></div>-->
			</div>
			
			
			<div class='api-sections'>
				<div class='api-section nutrition'>
					<h1>nutrition analysis</h1>
					<h2>macros and dietary characteristics</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					
					<div class='return-object-example call'>
						<pre class='json'>var ingredient_list =
		"1 lb beef tenderloin"+
		"\n3 tbsp unsalted butter";
		
	<em>// ingredient strings should be separated by newlines</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/analysis.php",
		method: "GET",
		data: {
			ingredient_list: ingredient_list,
			user_id: YOUR_USER_ID,
			user_api_key: YOUR_API_KEY
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre>
	<!-- next step here is to make a wrapper php script with a displayable title and path, and better ajax variables or none -->
	
					</div>
					<h3>example return data</h3>
					<div class='return-object-example nutrition'>
						<pre class='json'><?php echo json_encode($payload, JSON_PRETTY_PRINT); ?></pre>
					</div>
					
					
					<h3>inline testing tool</h3>
					<div class='nutrition-inline-analysis inline-tool'>
						<textarea 
							class='inline-analysis-textarea inline-textarea' 
							placeholder='e.g., 1 lb beef tenderloin'></textarea>
						<div class='nutrition-api-button process api-button'>
							<p>process</p>
						</div>
					</div>
					<div class='inline-analysis-header'></div>
					<div class='inline-analysis-ingredients inline-analysis-results'></div>
					
				</div>
				
				<div class='api-section query'>
					<h1>query the RaaSipe database</h1>
					<h2>1 - recipe search</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example recipe-search-call'>
						<pre class='json'>var search_keyword = "taco",
		course = "main",
		<em>// optional variable. allowed values are: 
			["main", "side", "soup", 
			"salad", "beverage", "dessert",
			null]
		</em>
		
		diet = ["kosher","nut_free"];
		<em>// optional variable. allowed values are:
			["vegan", "vegetarian",
			"kosher", "gluten_free", 
			"alcohol_free", "pescatarian", 
			"shellfish_free", "sugar_free", 
			"dairy_free", "nut_free",
			null]
		</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/recipe_search.php",
		method: "GET",
		data: {
			search_keyword: recipe_keyword,
			course: course,
			diet: diet,
			user_id: YOUR_USER_ID,
			user_api_key: YOUR_API_KEY
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre></div>	
	<h3>example return data</h3>
					<div class='return-object-example recipe-search-result'>
						<pre class='json'>
	<?php echo json_encode($search_result, JSON_PRETTY_PRINT); ?>
						</pre>
					</div>
					
	
					
					<!--<h3>inline testing tool (keyword only)</h3>
					<div class='recipe-inline-search inline-tool'>
						<textarea 
							class='inline-analysis-textarea-search inline-textarea' 
							placeholder='e.g., taco'></textarea>
						<div class='recipe-api-button process api-button'>
							<p>search</p>
						</div>
					</div>
					<div class='inline-analysis-search-header'></div>
					<div class='inline-analysis-returned-recipes inline-analysis-results'></div>-->
				</div>
				
				<div class='api-section content'>
					<h2>2 - recipe content</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example recipe-content-call'>
	<pre class='json'>
	var recipe_id = 5;
	<em>//recipe_id must be integer</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/recipe_content.php",
		method: "GET",
		data: {
			recipe_id: recipe_id
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre></div>
	<h3>example return data</h3>
					<div class='return-object-example recipe-content-result'><pre class='json'><?php echo json_encode(
								[
									"metadata" => $recipe["data"]["metadata"],
									"nutrition" => $recipe["data"]["nutrition"],
									"steps" => $recipe["data"]["steps"]
								], 
								JSON_PRETTY_PRINT); ?></pre>
					</div>
					
					
					<!--<h3>inline testing tool</h3>
					<div class='inline-related-recipe inline-tool'>
						<textarea 
							class='inline-analysis-textarea-related inline-textarea' 
							placeholder='e.g., 5 - must be a valid recipe id'></textarea>
						<div class='related-recipe-api-button process api-button'>
							<p>search</p>
						</div>
					</div>
					<div class='inline-analysis-search-header'></div>
					<div class='inline-analysis-related-recipes inline-analysis-results'></div>-->
				</div>
				
				<div class='api-section related'>
					<h2>3 - related recipes</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example related-recipe-call'>
	<pre class='json'>
	var recipe_id = 5;
	<em>//recipe_id must be integer</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/related_recipes.php",
		method: "GET",
		data: {
			recipe_id: recipe_id
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre></div>	
	<h3>example return data</h3>
					<div class='return-object-example related-recipe-result'><pre class='json'><?php echo json_encode($similar_recipes["data"], JSON_PRETTY_PRINT); ?></pre>
					</div>
					
					
					<!--<h3>inline testing tool</h3>
					<div class='inline-related-recipe inline-tool'>
						<textarea 
							class='inline-analysis-textarea-related inline-textarea' 
							placeholder='e.g., 5 - must be a valid recipe id'></textarea>
						<div class='related-recipe-api-button process api-button'>
							<p>search</p>
						</div>
					</div>
					<div class='inline-analysis-search-header'></div>
					<div class='inline-analysis-related-recipes inline-analysis-results'></div>-->
				</div>
	
				<div class='api-section recipe-html'>
					<h2>4 - recipe HTML</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example recipe-html-call'>
	<pre class='json'>
	var recipe_id = 5;
	<em>//recipe_id must be integer</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/recipe_html.php",
		method: "GET",
		data: {
			recipe_id: recipe_id
		},
		success: function(data) {
			<em>//do stuff with the data - urldecode() [PHP] or unescape() [JS] HTML strings</em>
		}
	});
	</pre></div>
	<h3>example return data</h3>
					<div class='return-object-example recipe-html-result'>
						<pre class='json'><?php echo json_encode(
								[
									"nutrition" => $recipe["html"]["nutrition"],
									"steps" => $recipe["html"]["steps"]
								], 
								JSON_PRETTY_PRINT); ?></pre>
					</div>
				</div>
				
				<div class='api-section groceries-data'>
					<h2>5 - recipe grocery list</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example groceries-data-call'>
	<pre class='json'>
	var recipe_id = 5;
	<em>//recipe_id must be integer</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/groceries.php",
		method: "GET",
		data: {
			recipe_id: recipe_id
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre></div>
	<h3>example return data</h3>
					<div class='return-object-example groceries-data-result'>
						<pre class='json'>
	<em>// keys are supermarket sections; values are arrays of products found in that section</em>
							<?php 
								
								$api_groceries = $recipe["data"]["groceries"];
		
								foreach ($api_groceries as $k => $v) {
									$api_groceries[$k] = array_values($api_groceries[$k]);
								}
								
								echo json_encode($api_groceries,JSON_PRETTY_PRINT);
							?>
						</pre>
					</div>
				</div>
				
				<div class='api-section equipment-data'>
					<h2>6 - recipe equipment</h2>
					
					<h3>example API call (JS + jQuery)</h3>
					<div class='return-object-example equipment-data-call'>
	<pre class='json'>
	var recipe_id = 5;
	<em>//recipe_id must be integer</em>
	
	$.ajax({
		url: "http://www.raasipe.com/api/equipment.php",
		method: "GET",
		data: {
			recipe_id: recipe_id
		},
		success: function(data) {
			<em>//do stuff with the data</em>
		}
	});
	</pre></div>
	<h3>example return data</h3>
					<div class='return-object-example equipment-data-result'><pre class='json'><?php echo json_encode(
								$recipe["data"]["equipment"], 
								JSON_PRETTY_PRINT); ?></pre>
					</div>
				</div>
			</div>
			
			
			
			
			
	
			
			<?php 
				include "../html_templates/footer.html"; 
			?>
		</div>
		
		
		
	</body>
</html>

<script>
	
	
	
	$(".nutrition-api-button.process-test").click(function(){
		var ingredient_list =
			"1 lb beef tenderloin"+
			"\n3 tbsp unsalted butter"+
			"\n2 kg oats";
		
		
		$.ajax({
			url: "analysis.php",
			method: "GET",
			data: {
				ingredient_list: ingredient_list,
				echo_data: 1,
				base_path: "../",
				ajax_ingredients_only: 1,
				//ajax: 1
				//$_GET["ajax_ingredients_only"] = 1;
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		});
	});

	$(".recipe-search-api-button.process-test").click(function(){
		var search_string = "taco",
			course = "main",
			diet = "pescatarian";
		
		$.ajax({
			url: "recipe_search.php",
			method: "GET",
			data: {
				search_string: search_string,
				//course: course,
				diet: diet,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				ajax_recipes_only: 1,
				ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		})
	});
	
	$(".recipe-content-api-button.process-test").click(function(){
		var recipe_id = 5;
		
		$.ajax({
			url: "recipe_content.php",
			method: "GET",
			data: {
				recipe_id: recipe_id,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				ajax_recipes_only: 1,
				//ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		});
	});
	
	$(".related-recipe-api-button.process-test").click(function(){
		var recipe_id = 5;
		
		$.ajax({
			url: "related_recipes.php",
			method: "GET",
			data: {
				recipe_id: recipe_id,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				//ajax_recipes_only: 1,
				//ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		})
	});
	
	$(".recipe-html-api-button.process-test").click(function(){
		var recipe_id = 5;
		
		$.ajax({
			url: "recipe_html.php",
			method: "GET",
			data: {
				recipe_id: recipe_id,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				ajax_recipes_only: 1,
				//ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		})
	});
	
	$(".groceries-data-api-button.process-test").click(function(){
		var recipe_id = 5;
		
		$.ajax({
			url: "groceries.php",
			method: "GET",
			data: {
				recipe_id: recipe_id,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				ajax_recipes_only: 1,
				//ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		})
	});
	
	$(".equipment-data-api-button.process-test").click(function(){
		var recipe_id = 5;
		
		$.ajax({
			url: "equipment.php",
			method: "GET",
			data: {
				recipe_id: recipe_id,
				//user_id: YOUR_USER_ID,
				//user_api_key: YOUR_API_KEY
				ajax_recipes_only: 1,
				//ajax: 1,
				base_path:"../"
			},
			success: function(data) {
				console.log(data);
				data = JSON.parse(data);
				console.log(data);
			}
		})
	});


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$(".nutrition-api-button.process").click(function(){
		var ings = $(".inline-analysis-textarea").val();
		
		console.log(ings);
		
		$.ajax({
			url: "../add_recipe/php/services/extract_ingredient.php",
			method: "GET",
			data: {"ingredient_list": ings, "ajax_ingredients_only": 1, "ajax": 1},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				$(".inline-analysis-header").html(e.html.header);
				$(".inline-analysis-ingredients").html(e.html.ingredients);
			}
		});
	});
	
	$(".recipe-api-button.process").click(function(){
		var ss = $(".inline-analysis-textarea-search").val();
		
		
		$.ajax({
			url: "recipe_search.php",
			method: "GET",
			data: {
				"search_string": ss,
				"ajax_recipes_only": 1,
				"ajax": 1,
				"base_path":"../"
			},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				$(".inline-analysis-returned-recipes").html(e.html);
			}
		})
	});
	
	$(".related-recipe-api-button.process").click(function(){
		var recipe_id = $(".inline-analysis-textarea-related").val();
		
		$.ajax({
			url: "related_recipes.php",
			method: "GET",
			data: {
				"recipe_id": recipe_id,
				"similar_results": 20
			},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				$(".inline-analysis-related-recipes").html(e.example_html);
			}
		})
	});
	
	
	
	
	
	$(".return-object-example").click(function() {
		$(this).toggleClass("expanded");
	})
	
	
	$(".api-menu-button.query").click(function(){
		window.scrollTo(0,$(".api-section.query").offset().top - 50);
	});
	
	$(".api-menu-button.nutrition").click(function(){
		window.scrollTo(0,$(".api-section.nutrition").offset().top - 50);
	});
	
	
	
	
</script>




















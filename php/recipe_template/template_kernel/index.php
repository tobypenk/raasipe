<?php
	
	$recipe_name = explode("/", getcwd());
	$recipe_name = $recipe_name[count($recipe_name)-1];
	$recipe_name = str_replace("-", "%20", $recipe_name);

	include "../../php/init.php";
	
?>

<!DOCTYPE html>

<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">
		<script src="../../js/jquerylib.js"></script>

		<link rel="stylesheet" href="../../css/main.css">


		<script type="application/ld+json">
			<?php echo $json_ld; ?>
		</script>
		
		<?php echo $recipe["html"]["social_metadata"]; ?>
		<?php include "../../html_templates/google_analytics.html"; ?>

		<?php echo $title; ?>
		
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
	</head>
	<body>
		
		
		
		<div class='ad-banner'>
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- recipe-view-ad -->
			<ins class="adsbygoogle"
			     style="display:inline-block;width:700px;height:90px"
			     data-ad-client="ca-pub-3104562679199136"
			     data-ad-slot="9479021462"></ins>
			<script>
			     (adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</div>


		<?php include "../../html_templates/header.html"; ?>
		
		<?php //ad banner goes here ?>

		<div class='main-content'>
		
		
			<div class='recipe-horizontal'>
					
				<div class='recipe-content'>
					
					<?php
						if (strlen(trim($description)) > 0) echo "<div class='recipe-description'>".$description."</div>";
					?>
					
					
					<div class='recipe-header'>
						<div class='recipe-tombstone'>
							<?php echo urldecode($recipe["html"]["header"]); ?>
						</div>
						<div class='macros collapsed'>
							<h2>Nutrition</h2>
							<p class='label-text'>Click to see macros and stuff.</p>
							<div class='macros-expander'>
								<?php echo urldecode($recipe["html"]["nutrition"]["macros"]); ?>
								<?php echo urldecode($recipe["html"]["nutrition"]["dietary"]); ?>
							</div>
						</div>
						<div class='groceries collapsed'>
							<h2>Groceries</h2>
							<p class='label-text'>Text yourself the grocery list for this recipe. We'll never use your phone number for anything other than this.</p>
							<div class='grocery-expander'>
								<?php echo urldecode($recipe["html"]["groceries"]); ?>
							</div>
						</div>
					</div>
					<div class='recipe'>
						<?php echo urldecode($recipe["html"]["steps"]); ?>
					</div>
				</div>
				<div class='equipment'>
					<h2>Equipment</h2>
					<?php echo urldecode($recipe["html"]["equipment"]); ?>
				</div>
				
			</div>
			<div class='similar-recipes'>
				<h2>Similar Recipes</h2>
				<?php echo urldecode($recipe["html"]["similar_recipes"]); ?>
			</div>
	
			<?php include "../../html_templates/footer.html"; ?>
		
		</div>
	</body>
</html>


<script>


	document.onkeyup = function(e) {
		
	    if (e.keyCode == 13) {
	        if ($(e.target).hasClass("text-groceries")) {
	        	text_groceries();
	        }
	    }
	}

	$(".groceries").click(function(e){

		if (!$(e.target).hasClass("text-groceries")) {
			$(this).toggleClass("collapsed");
		}
	});

	$(".macros").click(function(){
		$(this).toggleClass("collapsed");
	});

	function extract_text_groceries() {
		return $(".grocery-list")
			.html()
			.replace(/amount">/g,"amount\">\n")
			.replace(/header">/g,"header\">\n\n")
			.replace(/<(.*?)>/g,"")
			.trim();
	}

	function extract_phone_number() {

		return $(".text-groceries").val();
	}

	function text_groceries() {

		var pn = extract_phone_number(),
			gr = extract_text_groceries();

		if (validate_phone_number(pn)) {
			// send the text

			$.ajax({
				method: "POST",
				url: "../../php/services/send_api_text.php",
				data: {
					message: gr,
					pn: pn
				},
				success: function(e) {
					console.log(e);
				}
			});
		} else {
			// handle erroneous phone number
			console.log("that's not a phone number.");
		}
	}

	function validate_phone_number(pn) {

		var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
		if(pn.match(phoneno)) {
			return true;
		}
		else {
			return false;
		}
	}

	$(document).on("blur",".amt-quantity-input",function() {

        var this_q = $(this).val().toString().match("/") ? 
        		convert_string_to_float($(this).val().toString()) : 
        		$(this).val(),
            orig_q = $(this).attr("data-underlying-quantity"),
            new_m  = this_q/orig_q,
            params = parse_get_parameters();
            
        var data = {};
		for (var i=0; i<params.length; i++) {
			data[params[i][0]] = params[i][1];
		}
		data["ajax"] = 1;
		data["multiplier"] = new_m;
		data["recipe_name"] = window
			.location
			.pathname
			.split("/")
			.filter(function(x){return x.trim() != "" && x != "index.php"; })
			.pop();
		console.log(data);

        $.ajax({
        	method: "GET",
        	url: "../../php/init.php",
        	data: data,
        	success: function(e) {
        		//console.log(e);
        		e = JSON.parse(e);
        		//console.log(e);
        		$(".recipe").html(urldecode(e.html.steps));

        		$(".recipe-tombstone").html(urldecode(e.html.header));
        		$(".macros-expander").html(urldecode(e.html.nutrition.macros + e.html.nutrition.dietary));

        		// this ain't too good:
        		$(".grocery-expander").html(
        			urldecode(e.html.groceries)
        		);
        	}
        });
	});





	function convert_string_to_float(s) {
	    s = s.split(" ");
	    if (s.length == 1) {
	        if (s[0].match("/")) {
	            s = s[0].split("/");
	            return parseInt(s[0])/parseInt(s[1]);
	        } else {
	            return parseInt(s[0]);
	        }
	    } else {
	        s[1] = s[1].split("/");
	        return parseInt(s[0]) + parseInt(s[1][0])/parseInt(s[1][1]);
	    }
	}

	function urldecode(str) {
		return decodeURIComponent(str.replace(/\+/g, ' '));
	}

	function adjust_recipe_viewport() {
		var window_height = window.innerHeight,
			//header_height = parseInt($(".header").css("height")),
			//footer_height = parseInt($(".footer").css("height")),
			//margin_bottom = parseInt($(".recipe-horizontal").css("margin-bottom")),
			target_height = window_height;

		$(".recipe-horizontal").css("height", target_height);
	}
	//adjust_recipe_viewport();

	//window.onresize = adjust_recipe_viewport;



	



	function parse_get_parameters() {

		var params = params = window.location.search
				.slice(1)
				.split("&")
				.map(function(x){return x.split("=");});

		return dedupe_parameters(params).filter(function(x){return x.length == 2;});
	}

	function dedupe_parameters(params) {

		var p = [], n = [], i

		for (i=params.length-1; i>=0; i--) {
			if (contains(n,params[i][0])) {
				continue;
			} else {
				p.push(params[i]);
				n.push(params[i][0]);
			}
		}
		return p;
	}

	function contains(arr,el) {

		for (var i=0; i<arr.length; i++) {
			if (arr[i] == el) return true;
		}
		return false;
	}


</script>















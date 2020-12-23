<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$is_builder = TRUE;
	
	include "../php/services/search_recipes.php";
	include "php/services/grocery_list.php";
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='../css/main.css' />

		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">
<!-- 		<link href="https://fonts.googleapis.com/css?family=Nanum+Myeongjo&display=swap" rel="stylesheet"> -->
		<script src="../js/jquerylib.js"></script>
		
		<?php include "../html_templates/google_analytics.html"; ?>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
		
		<title>RaaSipe | Search</title>
	</head>
	<body>
		
		<?php 
			include "../html_templates/header.html"; 
		?>
		
		<div class='main-content'>
		
			<!--<div class='grocery-code-word-wrapper'>
				<input class='grocery-code-word' placeholder='grocery list key' />
			</div>-->
	
			<div class='search-ui'>
				<div class='search-ui-header'>
					<input class='search-string-ui' placeholder='type keywords, press enter' onsubmit='keyword_search()'/>
					<div class='search-loading-ui'></div>
				</div>
				
				<div class='dietary-ui'>
					<?php echo $dietary_ui; ?>
				</div>
				
				<div class='course-ui'>
					<?php echo $course_ui; ?>
				</div>
			</div>
			
			<div class='saved-recipes'>
				<div class='saved-recipes-holder'></div>
				<p class='hidable-sibling'>Save recipes by clicking the square boxes on the left</p>
	
			</div>
	
			<div class='search-results' >
				<div class='search-result search-result-header'>
					<div class='builder-checkbox header-checkbox'></div>
					<p class='search-result-title'>Recipe Name</p>
					<p class='search-result-calories'>Calories</p>
					<div class='search-result-macros'>
						<div class='macros-slider protein' onclick=order_macro_search('protein') style='width: 33.3%;'>Protein</div>
						<div class='macros-slider fat' onclick=order_macro_search('fat') data-macro='fat' style='width: 33.3%;'>Fat</div>
						<div class='macros-slider carbs' onclick=order_macro_search('carbs') data-macro='carbs' style='width: 33.3%;'>Carbs</div>
					</div>
				</div>
	
				<div class='returned-results' onscroll='scrolled(this)'>
					<?php 
						echo $search_result_html; 
					?>
				</div>
			</div>
			
			<div class='requirements-holder'>
				<div class='list-holder'>
					<h2>groceries</h2>
					<div class='grocery-list-holder'></div>
					<p class='hidable-sibling'>A consolidated grocery list for saved recipes will appear here</p>
				</div>
				<div class='list-holder'>
					<h2>equipment</h2>
					<div class='equipment-list-holder'></div>
					<p class='hidable-sibling'>Necessary equipment for saved recipes will appear here</p>
				</div>
			</div>
			
			<?php 
				include "../html_templates/footer.html"; 
			?>
		
		</div>
		
	</body>
</html>


<script>





	// there is a 1600-character limit on texts that needs to be accounted for
	// add saved recipes to parameters list
	

	document.onkeypress = function(e) {
		if (e.charCode == 13 && $(document.activeElement).hasClass("search-string-ui")) {
			keyword_search();
		}
	}

	function order_macro_search(macro) {

		// make always desc if new basis

		var params = window.location.search
			.slice(1)
			.split("&")
			.map(function(x){return x.split("=");}),
			sort_basis = params.filter(function(x){return x[0] == "sort_basis"}),
			orig_macro = params.filter(function(x){return x[0] == "macro_order"}),
			sort_basis;
		params = params
			.filter(function(x){return x[0] != "sort_basis"})
			.filter(function(x){return x[0] != "macro_order"})
			.filter(function(x){return x[0] != "offset"})
			.filter(function(x){return x[0] != "limit"});

		params.push(["macro_order",macro]);
		params.push(["offset",0]);
		params.push(["limit",20]);

		if (sort_basis.length == 0) {
			sort_basis = "desc";
		} else if (orig_macro.length != 0 && orig_macro[0][1] != macro) {
			sort_basis = "desc";
		} else if (sort_basis[0][1] == "desc") {
			sort_basis = "asc";
		} else {
			sort_basis = "desc";
		}
		$(".macros-slider").removeClass("desc");
		$(".macros-slider").removeClass("asc");
		$(".search-result-header .macros-slider."+macro).addClass(sort_basis);

		params.push(["sort_basis",sort_basis])

		params = dedupe_parameters(params).filter(function(x){return x.length == 2;});

		update_search_results(params,true);
	}

	function keyword_search() {
		var k = $(".search-string-ui").val();

		params = parse_get_parameters()
			.filter(function(x){return x[0] != "offset";});
		params.push(["search_string",k]);
		params = dedupe_parameters(params);

		update_search_results(params,true);
	}

	function dietary_search(el) {

		var attr = $(el).attr("data-dietary-quality"),
			val = $(el).is(":checked") ? 1 : 0,
			payload = [attr,val],
			params = parse_get_parameters()
				.filter(function(x) {return x[0] != attr;})
				.filter(function(x){return x[0] != "offset";});
		if (val != 0) params.push(payload);
		update_search_results(params,true);
	}
	
	function course_search(el) {
		
		var init = $(el).prop("checked");
		$(".course-checkbox").prop("checked",false);
		$(el).prop("checked",init);
	
		//parse params
		//toggle this course
		
		var params = parse_get_parameters()
			.filter(function(x) {return x[0] != "course";})
			.filter(function(x){return x[0] != "offset";});
		
			
		if ($(el).prop("checked")) params.push(["course",$(el).attr("data-course")]);
			
		//console.log(params);
		update_search_results(params,true);
	}

	function update_search_results(params,wipe=false) {

		var data = {};
		for (var i=0; i<params.length; i++) {
			data[params[i][0]] = params[i][1];
		}
		data["ajax"] = 1;
		data["is_builder"] = 1;

		$(".search-loading-ui").addClass("loading");
		
		$.ajax({
			url: "../php/services/search_recipes.php",
			method: "GET",
			data: data,
			success: function(e) {
				e = JSON.parse(e);
				if (wipe) {
					$(".returned-results").html(e.search_result_html);
				} else {
					$(".returned-results").append(e.search_result_html);
				}
				fire_limit_increase = false;
				setTimeout(function(){fire_limit_increase = true;}, 500);
				relocate_window(params);
				$(".search-loading-ui").removeClass("loading");
			}
		});
	}
	
	
	
	
	
	
	
	// these need to be in a shared spot so that search functionality can be shared across pages without redundant code
	function parse_get_parameters() {

		var params = params = window.location.search
				.slice(1)
				.split("&")
				.map(function(x){return x.split("=");})
				.filter(function(x){return x[1] != 0;});

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

	function relocate_window(params) {
		param_string = stringify_parameter_array(params);

		var new_location = window.location.protocol + 
			"//" + 
			window.location.host + 
			window.location.pathname + "?" +
			param_string;
		if (history.pushState) {
		    window.history.pushState({path:new_location},'',new_location);
		}
	}

	function stringify_parameter_array(params) {
		return params.map(function(x){return x.join("=")})
			.join("&");
	}

	var fire_limit_increase = true;
    
	function scrolled(o) {
        //visible height + pixel scrolled = total height 

        if(o.offsetHeight + o.scrollTop >= o.scrollHeight && fire_limit_increase) {
            load_additional_results();
            fire_limit_increase = false;
            setTimeout(function(){fire_limit_increase = true;}, 1000);
        }
    }

    function load_additional_results() {
		
		var offset_distance = 20,
			params=parse_get_parameters(),
			offset_adjusted = false;
		for (var i=0; i<params.length; i++) {
			if (params[i][0] == "offset") {
				params[i][1] = Math.max(0,parseInt(params[i][1]) + offset_distance);
				offset_adjusted = true;
			}
		}
		if (!offset_adjusted) params.push(["offset",offset_distance]);
		update_search_results(params);
    }
    
    function reconcile_dom_to_window_location() {
	    var ss = parse_get_parameters()
	    		.filter(function(x) {return x[0] == "search_string"; }),
	    	mo = parse_get_parameters()
	    		.filter(function(x) {return x[0] == "macro_order"; }),
	    	mb = parse_get_parameters()
	    		.filter(function(x) {return x[0] == "sort_basis"; });
	    	
	    if (ss.length == 1) {
		    $(".search-string-ui").val(ss[0][1]);
	    }
	    
	    if (mo.length == 1 && mb.length == 1) {
		    $(".search-result-header .macros-slider."+mo[0][1]).addClass(mb[0][1]);
	    }
    }
    
    function update_parameters(k,v) {
	    var params = parse_get_parameters(), i;
	    
	    for (i=0; i<params.length; i++) {
		    if (params[i][0] == k) {
			    params[i][1] = v;
			    return params;
		    }
	    }
	    params.push([k,v]);
	    return params;
    }
	
	reconcile_dom_to_window_location();
	
	
	
	
	
	
	$(document).on("click",".search-results .builder-checkbox:not(.header)",function(e) {
		$(this).toggleClass("checked");
		move_to_saved_recipes_list($(this).parent());
		update_saved_recipes();
	});
	
	$(document).on("click",".saved-recipes .builder-checkbox:not(.header)",function(e) {
		$(this).toggleClass("checked");
		remove_from_saved_recipes_list($(this).parent());
		update_saved_recipes();
	});
	
	function move_to_saved_recipes_list(r) {
		var copy = r.clone();
		$(".saved-recipes-holder").append(copy);
		r.remove();
	}
	
	function remove_from_saved_recipes_list(r) {
		r.remove();
	}
	
	$(document).on("click","a.search-result",function(e){
		if ($(e.target).is(".builder-checkbox")) e.preventDefault();
	});
	
	function update_saved_recipes() {
		
		var saved_recipes = $(".saved-recipes .search-result")
			.map(function(){
				return $(this).attr("data-recipe-name");
			})
			.toArray();

		var data = {
			"ajax":1,
			"saved_recipes":saved_recipes,
			"is_builder":1
		};
			
		$(".search-loading-ui").addClass("loading");
		
		$.ajax({

			url: "php/services/grocery_list.php",
			method: "GET",
			data: data,
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				
				$(".grocery-list-holder").html(e.groceries.html);
				$(".equipment-list-holder").html(e.equipment.text);
				//relocate_window(params);
				$(".search-loading-ui").removeClass("loading");
			}

		});

	}
	
	
	
	
	
	
	
	
	
	
	
	

	document.onkeyup = function(e) {
		
	    if (e.keyCode == 13) {
	        if ($(e.target).hasClass("text-groceries")) {
	        	text_groceries();
	        }
	    }
	}
	
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





	function pull_saved_groceries() {
		var grocery_code_word = $("$grocery-code-word").val();
		
		//$.ajax() {}
	}





	$(".requirements-holder")
		.css(
			"padding-bottom",
			(window.innerHeight - 
				$(".search-ui").height() - 
				$(".saved-recipes").height() - 
				$(".search-results").height() - 
				$(".grocery-code-word-wrapper").height() + 
				$(".header").height()) + 
				"px"
			);



	$(document).on("click",".search-result",function(){
		var clicked_recipe_name = $(this).attr("data-recipe-name"),
			search_string = $("input.search-string-ui").val();
		
		$.ajax({
			url: "php/services/log_search_click.php",
			method: "GET",
			data: {
				clicked_recipe_name: clicked_recipe_name,
				search_string: search_string
			},
			success: function(e) {
				console.log(e);
			}
		})
	});



</script>



















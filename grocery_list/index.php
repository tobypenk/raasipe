<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='../css/main.css' />
		<link rel='stylesheet' href='../css/grocery_list.css' />

		<link href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" rel="stylesheet">

		<script src="../js/jquerylib.js"></script>
		
		<?php include "../html_templates/google_analytics.html"; ?>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
		
		<title>RaaSipe | Grocery list</title>
	</head>
	<body>
		
		<?php 
			include "../html_templates/header.html"; 
		?>
		
		<div class='main-content'>
			
			<!--<div class='body-expander'>x</div>-->
			
			<div class='content-holder'><?php include "session_checker.php";?></div>
					
			
		</div>
		
		<?php 
			include "../html_templates/footer.html"; 
		?>
		
	</body>
	
	
</html>

<script>
	
	var list_active = false;
	
	$(document).on("click",".consent-button",function(){
		$.ajax({
			method: "GET",
			url: "set_cookie.php",
			data: {permission_granted: true},
			success: function(e) {
				update_dom(e);
			}
		})
	});
	
	function update_dom() {
		$.ajax({
			method: "GET",
			url: "session_checker.php",
			success: function(e) {
				$(".content-holder").html(e);
			}
		});
	}
	
	$(document).on("click",".create-new-grocery-list",function(){
		$.ajax({
			method: "GET",
			url: "create_grocery_list.php",
			success: function(e) {
				//update_dom(e);
				console.log(e);
				
				update_list_url(e);
			}
		});
	});
	
	function guess_ingredient(ing) {
		
		$.ajax({
			url: "../add_recipe/php/services/extract_ingredient.php",
			method: "GET",
			data: {"ingredient_list": ing, "ajax_ingredients_only": 1, "ajax": 1},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				$(".inline-analysis-header").html(e.html.header);
				$(".inline-analysis-ingredients").html(e.html.ingredients);
			}
		});
	}
	
	function guess_ingredient_v2(ing) {
		
		$.ajax({
			url: "php/services/guess_ingredient.php",
			method: "GET",
			data: {"ingredient_list": ing, "ajax_ingredients_only": 1},
			success: function(e) {
				console.log(e);
				e = JSON.parse(e);
				console.log(e);
				$(".inline-analysis-header").html(e.html.header);
				$(".inline-analysis-ingredients").html(e.html.ingredients);
			}
		});
	}
	
	function update_list_url(list_id) {
		
		var s = parse_window_search(),
			k = s.map(function(x) {return s[0]}),
			i = -1;
			
			for (j in s) {
				if (s[j][0] == "list_id") i = j;
			}
			
		if (i == -1) {
			s.push(["list_id",list_id]);
		} else {
			s[i][1] = list_id;
		}
		
		s = s.filter(function(x){return x.length == 2;});
		console.log(s);
		
		update_window_location(s);
	}
	
	function update_window_location(s) {
		
		s = "?" + s.map(function(x){return x.join("=");}).join("&");
		
		var newurl = window.location.protocol + 
	    	"//" + 
	    	window.location.host + 
	    	window.location.pathname + 
	    	s;
	    
	    console.log(newurl);	
		//if (history.pushState) {
		//    window.history.pushState({path:newurl},'',newurl);
		//}
		window.location.href = newurl;
	}
	
	function parse_window_search() {
		
		return window.location.search
			.replace("?", "")
			.split("&")
			.map(function(x) {return x.split("=");});
	}
	
	function clear_item_search_url() {
		var s = parse_window_search()
			.filter(function(x){return x[0] != "list_id"});
		update_window_location(s);
	}
	
	function summon_grocery_list(list_id) {
		
		$.ajax({
			method: "GET",
			url: "session_checker.php",
			data: {list_id: list_id, ajax: true},
			success: function(e) {
				e = JSON.parse(e);
				if (!list_active) $(".content-holder").html(e.html);
				list_active = true;
				$(".list-items").html("");
				
				var supermarket_sections = [], i, j, this_section;
				for (var i in e.data) {
					supermarket_sections.push(e.data[i].supermarket_section);
				}
				
				supermarket_sections = supermarket_sections.filter(function (value, index, self) {
					return self.indexOf(value) === index;
				});
				
				for (i in supermarket_sections) {
					this_section = e.data.filter(function(x) {return x.supermarket_section == supermarket_sections[i];});
					$(".list-items").append(create_grocery_list_header(supermarket_sections[i]));
					
					for (j in this_section) {
						$(".list-items").append(create_grocery_list_html(this_section[j]));
					}
				}
				
				
			}
		});
	}
	
	function create_grocery_list_header(s) {
		var html = $(
			"<h3 class='supermarket-section-header'>"+
				s+
			"</h3>"
		);
		
		return html;
	}
	
	function create_grocery_list_html(l) {
		var html = $(
			"<div class='list-item'>"+
				"<div class='remove-item' data-list-item-id='"+l.id+"'>"+
				"</div>"+
				"<p>"+
					l.guess+
				"</p>"+
			"</div>"
		);
		
		return html;
	}
	
	setInterval(function(){
		
		var list_check = parse_window_search()
			.filter(function(x){return x[0] == "list_id";});
		if (list_check.length == 1) {
			summon_grocery_list(list_check[0][1]);
		} else {
			//update_dom();
		}
	}, 500);

	function handle_keypress(e){
			
        if(e.keyCode === 13){
            e.preventDefault();
			add_grocery_list_item();
        }
    }
	
	function add_grocery_list_item() {
		
		var val = $(".add-item-input").val(),
			list_id = $(".grocery-list").attr("data-list-id");
		
		$.ajax({
			method: "GET",
			url: "add_grocery_list_item.php",
			data: {
				val: val,
				list_id: list_id
			},
			success: function(e) {
				console.log(e);
				$(".add-item-input").val("");
			}
		});
	};
	
	$(document).on("click",".add-item-button",function() {
		add_grocery_list_item();
	});
	
	$(document).on("click",".remove-item",function() {
		var val = $(this).attr("data-list-item-id"),
			list_id = $(".grocery-list").attr("data-list-id");
			
		$.ajax({
			method: "GET",
			url: "remove_grocery_list_item.php",
			data: {
				val: val,
				list_id: list_id
			},
			success: function(e) {
				console.log(e);
			}
		});
	});
	
	$(document).on("blur",".grocery-list-title",function() {
		var val = $(".grocery-list-title").val(),
			list_id = $(".grocery-list").attr("data-list-id");
			
		$.ajax({
			method: "GET",
			url: "update_grocery_list_title.php",
			data: {
				val: val,
				list_id: list_id
			},
			success: function(e) {
				console.log(e);
			}
		});
	});
	
</script>













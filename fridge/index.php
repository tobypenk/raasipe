<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$prevent_echo = true;
	
	include "session_checker.php";
	
	
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='../css/main.css' />
		<link rel='stylesheet' href='../css/fridge.css' />

		<link 
			href="https://fonts.googleapis.com/css?family=Didact+Gothic|Reem+Kufi|Source+Sans+Pro:200,400,700" 
			rel="stylesheet"
		/>
		<script src="../js/jquerylib.js"></script>
		
		<?php include "../html_templates/google_analytics.html"; ?>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
		
		<title>RaaSipe | Fridge</title>
	</head>
	<body>
		
		<?php 
			include "../html_templates/header.html"; 
		?>
		
			<div class='main-content'>
			<div class='body-expander'></div>
			
			<div class='content-holder'>
				<?php 
					echo $payload["html"];
				?>
			</div>
					
			<?php 
				include "../html_templates/footer.html"; 
			?>
			
		</div>
		
		<div class='recipe-modal'></div>
		<div class='recipe-modal-shroud'></div>
		
	</body>
</html>

<script>
	
	var fridge_active = false,
		dataset = [],
		force_update = false;
		
		
		
		
	setInterval(function(){

		var fridge = fridge_check();
		
		if (fridge.length == 1) {
			//update_fridge_url(fridge[0][1]);
			summon_fridge(fridge[0][1]);
			
		}
	}, 1000);
	
		
		
		
	function fridge_check() {
		return parse_window_search()
			.filter(function(x){return x[0] == "fridge_id";});
	}
	
	$(document).on("click",".consent-button",function(){
		$.ajax({
			method: "GET",
			url: "set_cookie_permission.php",
			data: {permission_granted: true},
			success: function(e) {
				console.log(JSON.parse(e));
				update_dom(e);
			}
		})
	});
	
	$(document).on("click",".create-new-fridge",function(){
		$.ajax({
			method: "GET",
			url: "create_fridge.php",
			success: function(e) {
				//update_dom(e);
				//console.log(e);
				
				update_fridge_url(e);
			}
		});
	});
	
	function update_fridge_url(fridge_id) {
		
		var s = parse_window_search(),
			k = s.map(function(x) {return s[0]}),
			i = -1;
			
			for (j in s) {
				if (j[0] == "fridge_id") i = j;
			}
			
		if (i == -1) {
			s.push(["fridge_id",fridge_id]);
		} else {
			s[i][1] = fridge_id;
		}
		
		s = s.filter(function(x){return x.length == 2;});
		
		update_window_location(s);
	}
	
	function update_window_location(s) {
		
		s = "?" + s.map(function(x){return x.join("=");}).join("&");
		
		var newurl = window.location.protocol + 
	    	"//" + 
	    	window.location.host + 
	    	window.location.pathname + 
	    	s;
	    				
		if (history.pushState) {
		    window.history.pushState({path:newurl},'',newurl);
		}
	}
	
	function parse_window_search() {
		
		return window.location.search
			.replace("?", "")
			.split("&")
			.map(function(x) {return x.split("=");});
	}
	
	function clear_item_search_url() {
		var s = parse_window_search()
			.filter(function(x){return x[0] != "fridge_id"});
		update_window_location(s);
	}
	
	function arrays_are_equal(a1,a2) {
		
	}
	
	function summon_fridge(fridge_id) {
		
		$.ajax({
			method: "GET",
			url: "session_checker.php",
			data: {fridge_id: fridge_id},
			success: function(e) {
				e = JSON.parse(e);
				if (!fridge_active) {
					$(".content-holder").html(e.html);
				}
				
				fridge_active = true;
				
				e.dataset = e.data.map(function(x){return x.id;}).sort();

				if (JSON.stringify(e.dataset) != JSON.stringify(dataset) || force_update) {
					$(".fridge-items").html(e.fridge_html);
					dataset = e.dataset;
					force_update = false;
				}
			}
		});
	}
	
	function create_fridge_html(l) {
		console.log(l);
		var html = $(
			"<div class='fridge-item'>"+
				"<div class='remove-item' data-entry-id='"+l.id+"'></div>"+
				"<p>"+l.ingredient_name+"</p>"+
			"</div>"
		);
		
		return html;
	}
	
	
	
	
	function handle_keypress(e){
			
        if (e.keyCode === 13){
            e.preventDefault();
			add_fridge_item();
        }
    }
    
    $(document).on('keydown', function(e) {
    	if (e.key == "Escape") {
	        undisplay_recipe_modal();
        }
    });
    
    $(document).on("click",".recipe-modal-shroud",function() {
	    undisplay_recipe_modal();
    })
    
    function undisplay_recipe_modal() {
	    $(".recipe-modal").removeClass("displayed");
	    $(".recipe-modal-shroud").removeClass("displayed");
    }
	
	function add_fridge_item() {
		
		var ingredient_name = $(".add-item-input").val(),
			fridge_id = $(".fridge").attr("data-fridge-id"),
			ingredient_id = "-1";
		// To do: fix this, obviously
		
		console.log(ingredient_name+" "+fridge_id);
		
		$.ajax({
			method: "GET",
			url: "add_fridge_item.php",
			data: {
				ingredient_name: ingredient_name,
				ingredient_id: ingredient_id,
				fridge_id: fridge_id
			},
			success: function(e) {
				console.log(e);
				$(".add-item-input").val("");
			}
		});
	};
	
	$(document).on("click",".add-item-button",function() {
		add_fridge_item();
	});
	
	$(document).on("click",".remove-item",function() {
		
		var fridge_entry_id = $(this).attr("data-entry-id");
			
		$.ajax({
			method: "GET",
			url: "remove_fridge_item.php",
			data: {
				fridge_entry_id: fridge_entry_id
			},
			success: function(e) {
				console.log(e);
			}
		});
	});
	
	$(document).on("blur",".fridge-name",function() {
		var fridge_name = $(".fridge-name").val(),
			fridge_id = $(".fridge").attr("data-fridge-id");
		
		console.log(fridge_name + " " + fridge_id);
		
		$.ajax({
			method: "GET",
			url: "update_fridge_name.php",
			data: {
				fridge_name: fridge_name,
				fridge_id: fridge_id
			},
			success: function(e) {
				console.log(e);
			}
		});
	});
	
	$(document).on("click",".fridge-ingredient",function(){
		
		start_biting();
		var i_id = $(this).attr("data-ingredient-id"),
			fridge_id = $(".fridge").attr("data-fridge-id");
		
		$.ajax({
			method: "GET",
			url: "fridge_ingredient_recipes.php",
			data: {i_id: i_id, i_ids: extract_i_ids(), fridge_id: fridge_id},
			success: function(e) {
				e = JSON.parse(e);
				console.log(e.query);
				console.log(e);
				summon_recipe_modal(e);
				stop_biting();
			}
		});
	});
	
	function summon_recipe_modal(e) {
		$(".recipe-modal").html(e.html);
		$(".recipe-modal").addClass("displayed");
		$(".recipe-modal-shroud").addClass("displayed");
	}
	
	function extract_i_ids() {
		
		var total = [];
		
		$(".fridge-ingredient").each(function(){
			total.push($(this).attr("data-ingredient-id"));
		});
		
		return total;
	}
	
	$(document).on("click",".increment-age",function() {
		
		var increment = $(this).attr("data-increment-value"),
			entry_id = $(this).attr("data-entry-id");
			
			
		$.ajax({
			method: "GET",
			url: "increment_shelf_life.php",
			data: {increment: increment, entry_id: entry_id},
			success:function(e) {
				force_update = true;
			}
		});
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	///////// graveyard
		
	function update_dom() {
		$.ajax({
			method: "GET",
			url: "session_checker.php",
			success: function(e) {
				$(".content-holder").html(e.html);
			}
		});
	}
	
	
	
	
	
	
	var bites = [1,2,3,4],
		biting = null;
		
	function start_biting() {
		biting = setInterval(function(){
			
			if ($(".fridge").attr("data-background-value") == undefined) {
				$(".fridge").attr("data-background-value",1);
			}
			var new_image = (
				parseInt($(".fridge").attr("data-background-value")) + 1) % 
				bites.reduce(function(x,y){return x > y ? x : y})+1;
			
			$(".fridge").attr("data-background-value",new_image);
			
			$(".fridge").css("background-image","url(bites/bite_"+new_image+".svg)");
		}, 100);
	}
	
	function stop_biting() {
		window.clearInterval(biting);
		$(".fridge").css("background-image","none");
	}
	
	
	
	
	
	
</script>













<?php
	
	$daily_values = [
		
		"calories" => [null,null],
		"calories_from_fat" => [null,null],
		
		"protein" => [50,"g"],
		"fat" => [78,"g"],
		"carbs" => [275,"g"],
		
		"fiber" => [28,"g"],
		"calcium" => [1300,"mg"],
		"iron" => [18,"mg"],
		"magnesium" => [420,"mg"],
		
		"phosphorus" => [1250,"mg"],
		"potassium" => [4700,"mg"],
		"sodium" => [2300,"mg"],
		
		"zinc" => [11,"mg"],
		"thiamin" => [1.2,"mg"],
		"niacin" => [16,"mg"],
		"riboflavin" => [1.3,"mg"],
		
		"saturated_fat" => [20,"g"],
		"polyunsaturated_fat" => [null,null],
		"monounsaturated_fat" => [null,null],
		"trans_fat" => [null,null],
		"saturated_fat" => [20,"g"],
		"cholesterol" => [300,"mg"],
		
		"sugars" => [50,"g"],
		"folate" => [400,"mcg"],
		"caffeine" => [null,null],
		
		"vitamin_k" => [120,"mcg"],
		"vitamin_c" => [90,"mg"],
		"vitamin_b6" => [1.7,"mg"],
		"vitamin_b12" => [2.4,"mcg"],
		"vitamin_e" => [15,"mg"],
		"vitamin_a" => [900,"mcg"],
		"vitamin_d" => [20,"mg"],
		
		"biotin" => [30,"mcg"],
		"chloride" => [2300,"mg"],
		"chromium" => [35,"mcg"],
		"copper" => [0.9,"mg"],
		"molybdenum" => [45,"mcg"],
		"pantothenic_acid" => [5,"mg"],
		"selenium" => [55,"mcg"],
		"manganese" => [2.3,"mg"],
		"iodine" => [150,"mcg"],
		"choline" => [550,"mg"]
	];
	
	$metric_conversions = [
		"kg" => 1000,
		"g" => 1,
		"mg" => 1/1000,
		"mcg" => 1/1000000
	];
	
	$calories_per_fat_gram = 9;
	
	function reconcile_quantity($original_quantity,$original_units,$target_units) {
		global $metric_conversions;
		if (is_null($target_units)) return $original_quantity;
		return $original_quantity * $metric_conversions[$original_units] / $metric_conversions[$target_units];
	}
	
	function macro_div($gross_q,$unit_q,$unit,$str,$ms,$m_id,$p_class) {
		
		if (round($unit_q) == 0) return "";
		
		global $daily_values;
		$dv = $daily_values[str_replace(" ","_",$str)];
		if (is_null($dv[0])) {
			$dv_val = $unit_q." ".$unit;
			$dv_pct = (is_null($dv[1]) ? "" : "0%");
		} else {
			$dv_val = reconcile_quantity($unit_q,$unit,$dv[1]);
			$dv_pct = round($dv_val / $dv[0]*100)."%";
		}
		
		
		return "<div class='macro-data-wrapper ".$str."'>".
    		//"<p class='macro-data-text ".$str."-val'>".$gross_q."</p>".
    		//"<p class='macro-data-text ".$str."-text'>".$unit." ".$str."</p>".
    		//"<p class='macro-data-mini-text'>".
    		//	$unit_q . $unit . " / " .
    		//	($ms == "makes" ? $m_id : "serving").
    		//"</p>".
    		"<div class='macro-data-mini-text ".$p_class."'>".
    			//"<p class='macro-data-text ".$str."-text'>".$unit." ".$str."</p>".
    			"<p>".
    				"<span>".$str."</span>".
    				" ".$dv_val.$dv[1].
    			"</p>".
    			"<p>".$dv_pct."</p>".
    		"</div>".
    	"</div>";
	}

	function generate_macros($r,$ms,$msa,$m_id) {

		global $calories_per_fat_gram;
		
		$msa=convert_string_to_float($msa);
	    $cal=$r["data"]["nutrition"]["macros"]["amounts"]["calories"];
	    $car=$r["data"]["nutrition"]["macros"]["amounts"]["carbs"];
	    $fat=$r["data"]["nutrition"]["macros"]["amounts"]["fat"];
	    $pro=$r["data"]["nutrition"]["macros"]["amounts"]["protein"];
	    $fiber=$r["data"]["nutrition"]["macros"]["amounts"]["fiber"];
	    $calcium=$r["data"]["nutrition"]["macros"]["amounts"]["calcium"];
	    $iron=$r["data"]["nutrition"]["macros"]["amounts"]["iron"];
	    $magnesium=$r["data"]["nutrition"]["macros"]["amounts"]["magnesium"];
	    
	    $phosphorus=$r["data"]["nutrition"]["macros"]["amounts"]["phosphorus"];
	    $potassium=$r["data"]["nutrition"]["macros"]["amounts"]["potassium"];
	    $sodium=$r["data"]["nutrition"]["macros"]["amounts"]["sodium"];
	    
	    
	    $zinc=$r["data"]["nutrition"]["macros"]["amounts"]["zinc"];
	    $thiamin=$r["data"]["nutrition"]["macros"]["amounts"]["thiamin"];
	    $riboflavin=$r["data"]["nutrition"]["macros"]["amounts"]["riboflavin"];
	    $niacin=$r["data"]["nutrition"]["macros"]["amounts"]["niacin"];
	    
	    $saturated_fat=$r["data"]["nutrition"]["macros"]["amounts"]["saturated_fat"];
	    $monounsaturated_fat=$r["data"]["nutrition"]["macros"]["amounts"]["monounsaturated_fat"];
	    $polyunsaturated_fat=$r["data"]["nutrition"]["macros"]["amounts"]["polyunsaturated_fat"];
	    $trans_fat=$r["data"]["nutrition"]["macros"]["amounts"]["trans_fat"];
	    $cholesterol=$r["data"]["nutrition"]["macros"]["amounts"]["cholesterol"];
	    
	    $sugars=$r["data"]["nutrition"]["macros"]["amounts"]["sugars"];
	    $folate=$r["data"]["nutrition"]["macros"]["amounts"]["folate"];
	    $caffeine=$r["data"]["nutrition"]["macros"]["amounts"]["caffeine"];
	    
	    $vitamin_k=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_k"];
	    $vitamin_c=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_c"];
	    $vitamin_b6=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_b6"];
	    $vitamin_b12=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_b12"];
	    $vitamin_e=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_e"];
	    $vitamin_d=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_d"];
	    $vitamin_a=$r["data"]["nutrition"]["macros"]["amounts"]["vitamin_a"];
	    
	    $r_id = $r["data"]["metadata"]["raasipe_id"];

	    $macro_wrapper = "<div class='macro-wrapper'>".
	    	"<p class='macro-header thin-bottom-border'>nutrition</p>".
			
			"<p class='thickest-bottom-border'>".$msa." ".($ms == "makes" ? $m_id : "servings")." per recipe</p>".
			"<p class='thick-bottom-border'>amount per ".($ms == "makes" ? $m_id : "serving")."</p>".
			"<p style='text-align:right; font-size: 70%;'><strong>% daily value</strong></p>".
			
			macro_div((round($cal/10)*10),(round($cal / $msa / 10) * 10),"","calories",$ms,$m_id,"thinnest-bottom-border").
			//macro_div($cal,$cal,"","calories",$ms,$m_id,"thinnest-bottom-border").
			
			
			
			macro_div((round(($fat*$calories_per_fat_gram)/10)*10),(round(($fat*$calories_per_fat_gram) / $msa / 10) * 10),"","calories from fat",$ms,$m_id,"thick-bottom-border indent").
			
			macro_div(round($fat),round($fat / $msa),"g","fat",$ms,$m_id,"thinnest-bottom-border").
				macro_div(round($saturated_fat),round($saturated_fat / $msa),"g","saturated fat",$ms,$m_id,"thinnest-bottom-border indent").
				macro_div(round($monounsaturated_fat),round($monounsaturated_fat / $msa),"g","monounsaturated fat",$ms,$m_id,"thinnest-bottom-border indent").
				macro_div(round($polyunsaturated_fat),round($polyunsaturated_fat / $msa),"g","polyunsaturated fat",$ms,$m_id,"thinnest-bottom-border indent").
				macro_div(round($trans_fat),round($trans_fat / $msa),"g","trans fat",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($cholesterol),round($cholesterol / $msa),"g","cholesterol",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($sodium),round($sodium / $msa),"mg","sodium",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($car),round($car / $msa),"g","carbs",$ms,$m_id,"thinnest-bottom-border").
				macro_div(round($fiber),round($fiber / $msa),"g","fiber",$ms,$m_id,"thinnest-bottom-border indent").
				macro_div(round($sugars),round($sugars / $msa),"g","sugars",$ms,$m_id,"thinnest-bottom-border indent").
			macro_div(round($pro),round($pro / $msa),"g","protein",$ms,$m_id,"thickest-bottom-border").
			
			
			macro_div(round($vitamin_k),round($vitamin_k / $msa),"mg","vitamin k",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_c),round($vitamin_c / $msa),"mg","vitamin c",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_b6),round($vitamin_b6 / $msa),"mg","vitamin b6",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_b12),round($vitamin_b12 / $msa),"mg","vitamin b12",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_e),round($vitamin_e / $msa),"mg","vitamin e",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_d),round($vitamin_d / $msa),"mg","vitamin d",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($vitamin_a),round($vitamin_a / $msa),"mg","vitamin a",$ms,$m_id,"thinnest-bottom-border").
			
			macro_div(round($calcium),round($calcium / $msa),"mg","calcium",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($iron),round($iron / $msa),"mg","iron",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($magnesium),round($magnesium / $msa),"mg","magnesium",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($potassium),round($potassium / $msa),"mg","potassium",$ms,$m_id,"thinnest-bottom-border").
			
			macro_div(round($phosphorus),round($phosphorus / $msa),"mg","phosphorus",$ms,$m_id,"thinnest-bottom-border").
			
			macro_div(round($zinc),round($zinc / $msa),"mg","zinc",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($thiamin),round($thiamin / $msa),"mg","thiamin",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($riboflavin),round($riboflavin / $msa),"mg","riboflavin",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($niacin),round($niacin / $msa),"mg","niacin",$ms,$m_id,"thinnest-bottom-border").
			
			macro_div(round($folate),round($folate / $msa),"mg","folate",$ms,$m_id,"thinnest-bottom-border").
			macro_div(round($caffeine),round($caffeine / $msa),"g","caffeine",$ms,$m_id,"thinnest-bottom-border").
			
			


		

/*
	    	"<div class='macro-data-wrapper calories'>".
	    		"<p class='macro-data-text cal-val'>".(round($cal/10)*10)."</p>".
	    		"<p class='macro-data-text cal-text'> calories</p>".
	    		"<p class='macro-data-mini-text'>".
	    			(round($cal / $msa / 10) * 10) . " / " .
	    			($ms == "makes" ? $m_id : "serving").
	    		"</p>".
	    	"</div>".
*/

/*
	    	"<div class='macro-data-wrapper carbs'>".
	    		"<p class='macro-data-text cal-val'>".round($car)."</p>".
	    		"<p class='macro-data-text cal-text'> g carbs</p>".
	    		"<p class='macro-data-mini-text'>".
	    			(round($car / $msa)) . "g / " .
	    			($ms == "makes" ? $m_id : "serving").
	    		"</p>".
	    	"</div>".
*/

/*
	    	"<div class='macro-data-wrapper fat'>".
	    		"<p class='macro-data-text cal-val'>".round($fat)."</p>".
	    		"<p class='macro-data-text fat-text'> g fat</p>".
	    		"<p class='macro-data-mini-text'>".
	    			(round($fat / $msa)) . "g / " .
	    			($ms == "makes" ? $m_id : "serving").
	    		"</p>".
	    	"</div>".
*/
	    	/*
"<div class='macro-data-wrapper pro'>".
	    		"<p class='macro-data-text cal-val'>".round($pro)."</p>".
	    		"<p class='macro-data-text pro-text'> g protein</p>".
	    		"<p class='macro-data-mini-text'>".
	    			(round($pro / $msa)) . "g / " .
	    			($ms == "makes" ? $m_id : "serving").
	    		"</p>".
	    	"</div>".
*/
	    	
	    	/*
"<div class='macro-data-wrapper fiber'>".
	    		"<p class='macro-data-text fiber-val'>".round($fiber)."</p>".
	    		"<p class='macro-data-text fiber-text'> g fiber</p>".
	    		"<p class='macro-data-mini-text'>".
	    			(round($fiber / $msa)) . "g / " .
	    			($ms == "makes" ? $m_id : "serving").
	    		"</p>".
	    	"</div>".
*/
	    	
	    "</div>";

	    return $macro_wrapper;
	}

	function generate_dietary_badges($q) {

		$q_names = array_keys($q);

		$dietary_badges = "<div class='dietary-badges'>";

	    for ($i=0; $i<count($q_names); $i++) {
	        $dietary_badges .= generate_dietary_badge($q[$q_names[$i]]);
	    }

	    $dietary_badges .= "</div>";

	    return $dietary_badges;
	}

	function generate_dietary_badge($q) {
		
		$this_badge = "<p class='badge dietary-badge ".$q["name"]." ".($q["offender_total"] == 0 ? "true" : "false")."' ".
			" data-quality-name='".$q["name"]."' ".
			" id=".$q["name"]."_badge>";
	        
	    if ($q["offender_total"] == 0) {
	        $label_text = str_replace("_","-",$q["name"]);
	        $this_badge .= ucwords($label_text);
	    } else {

	        if (strpos($q["name"],"free") !== false) {
	            $qn = str_replace('/_.*/',"",$q["name"]);
	                if ($qn == "nut") {$qn = "nuts";}
	        }
	    }

	    $this_badge .= "</p>";

	    return $q["offender_total"] == 0 ? $this_badge : "";
	}

	function generate_equipment_badges($e) {
	    $equipment = "<div class='equipment-holder' id='equipment-holder'>".
	    	"<div class='equipment-badge-container'>";

	    	for ($i=0; $i<count($e); $i++) {
	    		$equipment .= generate_equipment_badge($e[$i]);
	    	}
	    $equipment .="</div><div class='tooltip-holder'></div></div>";

	    return $equipment;
	}

	function generate_equipment_badge($e) {

	    $this_name = ucwords($e["device_name"]);
	    $equipment = $e["rich_purchase_link"];

	    return $equipment;
	}

	function generate_steps($r,$m) {

		$steps = array_values(array_unique(array_column($r, "step_id")));
		$wrapper = "";
		for ($i=0; $i<count($steps); $i++) {
			$this_id = $steps[$i];
			$these_materials = array_values(array_filter($r,function($x) use($this_id) {return $x["step_id"] == $this_id;}));
			$wrapper .= generate_step($these_materials,$m);
		}

		return $wrapper;
	}

	function generate_step($s,$m) {
		$global_qualities = display_qualities();
		$wrapper_opener = "<div class='recipe-content-column-holder step'>";
	    $materials_opener = "<div class='materials'>";

	    for ($i=0; $i<count($s); $i++) {

	        $material_opener = "<div class='material ".($s[$i]["ingredient_in_fridge"] ? "in-fridge" : "")."' ";
	        $material_closer = "</div>";
			for ($j=0; $j<count($global_qualities); $j++) {
				if ($global_qualities[$j] != "kosher" && $global_qualities[$j] != "pescatarian")
	            $material_opener .= "data-".$global_qualities[$j]."='".($s[$i][$global_qualities[$j]] == 1 ? "true" : "false")."'";
	        }
	        $material_opener .= ">";

	        if (is_null($s[$i]["ingredient_amount"]) != 1) {
	            $amt_magnitude = convert_float_to_fraction(floatval($s[$i]["ingredient_amount"]));
	                $el_width_scalar = strlen(strval($amt_magnitude));
	                $amount = " <input class='amt-quantity-input' ".
		                	" value= '".$amt_magnitude."' ".
		                	" data-underlying-quantity='".($s[$i]["ingredient_amount"]/$m)."' ".
		                	" data-recipe-id='".$s[0]["recipe_id"]."' />".
		                " <p class='amt-measure'>".
			                (($s[$i]["ingredient_amount"]) > 1 ? $s[$i]["measure_plural"] : $s[$i]["measure_abbreviation"]).
			            " </p>";

	                $i_name = ($s[$i]["ingredient_size"] == "" ? "" : $s[$i]["ingredient_size"] . " ") .
	                        (($s[$i]["measure_type"] != "" || $s[$i]["ingredient_amount"] > 1) ?
	                        	$s[$i]["ingredient_plural"] :
	                        	$s[$i]["ingredient_singular"]);
	                $i_name = str_replace("%27","'",$i_name); // NEW LINE
	                $i_prep = ($s[$i]["ingredient_preparation"] == "" ? "" : ", ") . $s[$i]["ingredient_preparation"];

	                $ingredient = "<div class='recipe-column ingredient'".
	                	" data-ingredient-id='".$s[$i]["ingredient_id"]."'".
	                    " data-supermarket-section='".$s[$i]["ingredient_supermarket_section"]."'".
	                	" >".
	                		ucwords(str_replace("%20"," ",$i_name)).$i_prep.
						"</div>";
	        } else {
	            $amount = "<div class='recipe-column amount'></div>";
	            $ingredient = "<p class='recipe-column ingredient'></p>";
	            if (strlen($s[0]["step_instructions"]) > 60) {
	            	
	            	$wrapper_opener = "<div class='recipe-content-column-holder step no-material'>";
	            }
	        }
	        
	       	$materials_opener .= $material_opener . 
	        		$amount .
	        		$ingredient .
	        	$material_closer;
	    }
	    $materials_opener .= "</div>";
	    
	    $instructions = "<p class='recipe-column instructions'>".urldecode($s[0]["step_instructions"])."</p>";
	    $wrapper_opener .= $materials_opener . $instructions . "</div>";
	    
	    return $wrapper_opener;
	}

	function generate_ingredient_string($m) {

		$amt_magnitude = convert_float_to_fraction(floatval($m["ingredient_amount"]));
        $amount = (($m["ingredient_amount"]) > 1 ? $m["measure_plural"] : $m["measure_abbreviation"]);

        $i_name = ($m["ingredient_size"] == "" ? "" : $m["ingredient_size"] . " ") .
                (($m["measure_type"] != "" || $m["ingredient_amount"] > 1) ?
                	$m["ingredient_plural"] :
                	$m["ingredient_singular"]);
        $i_name = str_replace("%27","'",$i_name); // NEW LINE
        $i_prep = ($m["ingredient_preparation"] == "" ? "" : ", ") . $m["ingredient_preparation"];

        $ingredient = ucwords(str_replace("%20"," ",$i_name)) . $i_prep;

        return trim(preg_replace('/\s+/', ' ', $amt_magnitude . " " . $amount . " " . $ingredient));
	}

	function generate_groceries($r) {
		// does not handle diverse preparations, varieties, sizes, or multiple entries of same i_id with diff measures, or plural vs singular ingredient name

		$data = [];
		$html = "<div class='grocery-list'>" .
			"<input class='text-groceries' placeholder='type digits, press enter' />";

		$covered_ingredient_ids = [];

		for ($i=0; $i<count($r); $i++) {

			if (is_null($r[$i]["ingredient_id"])) continue;

			if (arr_contains_el($covered_ingredient_ids,$r[$i]["ingredient_id"])) {
				$data[$r[$i]["ingredient_supermarket_section"]][$r[$i]["ingredient_id"]]["amount"] += floatval($r[$i]["ingredient_amount"]);
			} else {

				if (isset($data[$r[$i]["ingredient_supermarket_section"]])) {

					$data[$r[$i]["ingredient_supermarket_section"]][$r[$i]["ingredient_id"]] = [
						"raasipe_ingredient_id" => $r[$i]["ingredient_id"],
						"amount" => floatval($r[$i]["ingredient_amount"]),
						"display_amount" => convert_float_to_fraction(floatval($r[$i]["ingredient_amount"])),
						"measure" => $r[$i]["measure_abbreviation"],
						"variety" => $r[$i]["ingredient_size"],
						"ingredient_name" => $r[$i]["ingredient_singular"]
					];

				} else {
					$data[$r[$i]["ingredient_supermarket_section"]][$r[$i]["ingredient_id"]] = [
						"raasipe_ingredient_id" => $r[$i]["ingredient_id"],
						"amount" => floatval($r[$i]["ingredient_amount"]),
						"display_amount" => convert_float_to_fraction(floatval($r[$i]["ingredient_amount"])),
						"measure" => $r[$i]["measure_abbreviation"],
						"variety" => $r[$i]["ingredient_size"],
						"ingredient_name" => $r[$i]["ingredient_singular"]
					];
				}
				array_push($covered_ingredient_ids,$r[$i]["ingredient_id"]);
			}
		}

		foreach ($data as $section => $ings) {
			$html .= "<div class='grocery-list-section'>".
				"<h4 class='grocery-list-section-header'>".ucwords(urldecode($section))." Section</h4>";
				foreach($ings as $i) {
					$html .= "<div class='grocery-list-material'>".trim(
						"<span class='grocery-list-material-component amount'>".$i["display_amount"]."</span> ".
						"<span class='grocery-list-material-component measure'>".$i["measure"]."</span> ".
						"<span class='grocery-list-material-component ingredient'>".
							($i["variety"] == "" ? "" : $i["variety"]." ").
							$i["ingredient_name"].
							"</span> ").
						"</div>";
				}
				$html .="</div>";
		}

		$html .= "</div>";

		return [
			"data" => $data,
			"html" => $html
		];
	}

	function generate_source_string($r) {

	    $src_string = trim($r[0]["recipe_source_name"]) == "" ? "" : ("Adapted From " . $r[0]["recipe_source_name"]);

	    $source = urldecode("<p>" . $src_string . "</p>");

	    return $source;
	}

	function generate_functions_badges() {
	    
	    $functions = "<div class='recipe-functions-holder'>".
	    	"<div class='functions-badge-container'>".
		    	"<a href='#print_recipe' class='print  function-badge' id='print'>".
		    		"<p class='function-badge-text'>Print</p>".
			    "</a>".
			    "<div class='text-grocery-list  function-badge' id='text-grocery-list'>".
			    	"<input ".
			    		" class='inline-phone-number grocery-list-number' ".
			    		" id='inline-phone-number' ".
			    		" placeholder='Text'".
			    		"/>".
			    "</div>".
		    "</div>".
		    "<div class='tooltip-holder'></div>".
	    "</div>";

	    return $functions;
	}

	function generate_recipe_header($name,$yield,$at,$it,$source) {
		return "<p class='recipe-name'>".$name."</p>".
			"<div class='print-recipe'></div>".
			"<div class='metadata'>".
				"<div class='recipe-timing'>".
					"<p>Active time: ".$at."</p>".
					"<p>Inactive time: ".$it."<p>".
				"</div>".
				"<div class='recipe-yield'>".
					"<p>".$yield."</p>".
					"<p>".$source."</p>".
				"</div>".
			"</div>";
	}

	function generate_recipe_social_metadata($title,$desc,$image,$url) {
		return "<meta property='og:title' content='".$title."'>".
			"<meta property='og:description' content='".$desc."'>".
			"<meta property='og:image' content='".$image."'>".
			"<meta property='og:url' content='".$url."'>".
			"<meta property='og:type' content='"."website"."'>".
			"<meta name='twitter:card' content='"."recipe_image"."'>".
			"<meta property='description' content='".$desc."'>";
	}









?>





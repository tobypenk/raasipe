<?php

	function display_qualities() {
		return array(
		    "vegetarian"
		    ,"vegan"
		    ,"gluten_free"
		    ,"sugar_free"
		    ,"kosher"
		    ,"alcohol_free"
		    ,"dairy_free"
		    ,"nut_free"
		    ,"pescatarian"
		    ,"shellfish_free"
		);
	}

	function raw_qualities() {
		return array(
			"vegetarian"
		    ,"vegan"
		    ,"gluten_free"
		    ,"sugar_free"
		    ,"pork_free"
		    ,"alcohol_free"
		    ,"dairy_free"
		    ,"nut_free"
		    ,"fish_free"
		    ,"shellfish_free"
		);
	}

	function compute_timing($d) {

	    $at = 0;
	    $it = 0;
	    $tracker = array();

	    for ($i=0; $i<count($d); $i++) {
	    	if (in_array($d[$i]["step_id"], $tracker)) {continue;}

	    	array_push($tracker, $d[$i]["step_id"]);
	        $at += is_null($d[$i]["active_time_in_minutes"]) ? 0 : intval($d[$i]["active_time_in_minutes"]);
	        $it += is_null($d[$i]["inactive_time_in_minutes"]) ? 0 : intval($d[$i]["inactive_time_in_minutes"]);
	    }

	    $at = ceil($at / 5) * 5;
	    $it = ceil($it / 5) * 5;

	    $at_raw = $at;
	    $it_raw = $it;

	    if ($at > 90) {
	        $at = (round($at / 60 * 4) / 4) . " Hours";
	    } else {
	        $at .= " Minutes";
	    }
	    
	    if ($it > 90) {
	        if ($it / 60 >= 12) {
	            $it = "Overnight";
	        } else {
	            $it = convert_float_to_fraction(round($it / 15) * 15 / 60) . " Hours";
	        }
	    } else if ($it == 0) {
	        $it = "None";
	    } else {
	        $it .= " Minutes";
	    }

	    return [
	    	"data" => [
	    		"active_time_in_minutes" => $at_raw,
	    		"inactive_time_in_minutes" => $it_raw
	    	],
	    	"html" => [
	    		"active_time_in_minutes" => $at,
	    		"inactive_time_in_minutes" => $it
	    	]
	    ];
	}

	function volume_value($r_i,$c,$cc) {
		//echo json_encode($r_i);
	    return floatval($r_i[$c]) / 8 * 
        	floatval($r_i["measure_oz"]) * 
        	floatval($r_i["ingredient_amount"]) * 
        	($cc == 1 ? $cc : floatval($r_i["count_calories"]));
    }
	
	function weight_value($r_i,$c,$cc) {
		return floatval($r_i[$c]) * 
        	floatval($r_i["oz_multiple"]) * 
        	floatval($r_i["measure_oz"]) * 
        	floatval($r_i["ingredient_amount"]) * 
        	($cc == 1 ? $cc : floatval($r_i["count_calories"]));
	}
	
	function unit_value($r_i,$c,$cc) {
		
		$val = floatval($r_i[$c]) *
        	floatval($r_i["unit_multiple"]) * 
        	floatval($r_i["ingredient_amount"]) * 
        	($cc == 1 ? $cc : floatval($r_i["count_calories"]));
        //echo " " . $val;
        //echo "<br>";
		return $val;
	}


	function assess_macros($r) {

		$calories = 0;
		$protein = 0;
		$fat = 0;
		$carbs = 0;
		$fiber = 0;
		$calcium = 0;
		$iron = 0;
		$magnesium = 0;
		
		$phosphorus = 0;
        $potassium = 0;
        $sodium = 0;
        
        $zinc = 0;
        $thiamin = 0;
        $riboflavin = 0;
        $niacin = 0;
        
        $saturated_fat = 0;
        $monounsaturated_fat = 0;
        $polyunsaturated_fat = 0;
        $trans_fat = 0;
        $cholesterol = 0;
        
        $sugars = 0;
        $folate = 0;
        $caffeine = 0;
        
        $vitamin_k = 0;
        $vitamin_c = 0;
        $vitamin_b6 = 0;
        $vitamin_b12 = 0;
        $vitamin_e = 0;
        $vitamin_d = 0;
        $vitamin_a = 0;
		        
		
		global $count_calories_global;
		if (!isset($count_calories_global)) $count_calories_global = 0;

	    for ($i=0; $i<count($r); $i++) {

	        if ($r[$i]["measure_type"] == "volume") {
		        
				$calories += volume_value($r[$i],"calories_per_cup",$count_calories_global);
		        $protein += volume_value($r[$i],"protein_grams_per_cup",$count_calories_global);
		        $fat += volume_value($r[$i],"fat_grams_per_cup",$count_calories_global);
		        $carbs += volume_value($r[$i],"carb_grams_per_cup",$count_calories_global);
		        $fiber += volume_value($r[$i],"fiber_grams_per_cup",$count_calories_global);
		        $calcium += volume_value($r[$i],"calcium_milligrams_per_cup",$count_calories_global);
		        $iron += volume_value($r[$i],"iron_milligrams_per_cup",$count_calories_global);
		        $magnesium += volume_value($r[$i],"magnesium_milligrams_per_cup",$count_calories_global);
		        
		        $phosphorus += volume_value($r[$i],"phosphorus_milligrams_per_cup",$count_calories_global);
		        $potassium += volume_value($r[$i],"potassium_milligrams_per_cup",$count_calories_global);
		        $sodium += volume_value($r[$i],"sodium_milligrams_per_cup",$count_calories_global);
		        
		        $zinc += volume_value($r[$i],"zinc_milligrams_per_cup",$count_calories_global);
		        $thiamin += volume_value($r[$i],"thiamin_milligrams_per_cup",$count_calories_global);
		        $riboflavin += volume_value($r[$i],"riboflavin_milligrams_per_cup",$count_calories_global);
		        $niacin += volume_value($r[$i],"niacin_milligrams_per_cup",$count_calories_global);
		        
		        $saturated_fat += volume_value($r[$i],"saturated_fat_grams_per_cup",$count_calories_global);
		        $monounsaturated_fat += volume_value($r[$i],"monounsaturated_fat_grams_per_cup",$count_calories_global);
		        $polyunsaturated_fat += volume_value($r[$i],"polyunsaturated_fat_grams_per_cup",$count_calories_global);
		        $trans_fat += volume_value($r[$i],"trans_fat_grams_per_cup",$count_calories_global);
		        $cholesterol += volume_value($r[$i],"cholesterol_grams_per_cup",$count_calories_global);
		        
		        $sugars += volume_value($r[$i],"sugars_grams_per_cup",$count_calories_global);
		        $folate += volume_value($r[$i],"folate_milligrams_per_cup",$count_calories_global);
		        $caffeine += volume_value($r[$i],"caffeine_milligrams_per_cup",$count_calories_global);
		        
		        $vitamin_k += volume_value($r[$i],"vitamin_k_milligrams_per_cup",$count_calories_global);
		        $vitamin_c += volume_value($r[$i],"vitamin_c_milligrams_per_cup",$count_calories_global);
		        $vitamin_b6 += volume_value($r[$i],"vitamin_b6_milligrams_per_cup",$count_calories_global);
		        $vitamin_b12 += volume_value($r[$i],"vitamin_b12_micrograms_per_cup",$count_calories_global);
		        $vitamin_e += volume_value($r[$i],"vitamin_e_milligrams_per_cup",$count_calories_global);
		        $vitamin_d += volume_value($r[$i],"vitamin_d_milligrams_per_cup",$count_calories_global);
		        $vitamin_a += volume_value($r[$i],"vitamin_a_micrograms_per_cup",$count_calories_global);
		        
	            
	        } else if ($r[$i]["measure_type"] == "weight") {
		        
		        $calories += weight_value($r[$i],"calories_per_cup",$count_calories_global);
		        $protein += weight_value($r[$i],"protein_grams_per_cup",$count_calories_global);
		        $fat += weight_value($r[$i],"fat_grams_per_cup",$count_calories_global);
		        $carbs += weight_value($r[$i],"carb_grams_per_cup",$count_calories_global);
		        $fiber += weight_value($r[$i],"fiber_grams_per_cup",$count_calories_global);
		        $calcium += weight_value($r[$i],"calcium_milligrams_per_cup",$count_calories_global);
		        $iron += weight_value($r[$i],"iron_milligrams_per_cup",$count_calories_global);
		        $magnesium += weight_value($r[$i],"magnesium_milligrams_per_cup",$count_calories_global);
		        
		        $phosphorus += weight_value($r[$i],"phosphorus_milligrams_per_cup",$count_calories_global);
		        $potassium += weight_value($r[$i],"potassium_milligrams_per_cup",$count_calories_global);
		        $sodium += weight_value($r[$i],"sodium_milligrams_per_cup",$count_calories_global);
		        
		        $zinc += weight_value($r[$i],"zinc_milligrams_per_cup",$count_calories_global);
		        $thiamin += weight_value($r[$i],"thiamin_milligrams_per_cup",$count_calories_global);
		        $riboflavin += weight_value($r[$i],"riboflavin_milligrams_per_cup",$count_calories_global);
		        $niacin += weight_value($r[$i],"niacin_milligrams_per_cup",$count_calories_global);
		        
		        $saturated_fat += weight_value($r[$i],"saturated_fat_grams_per_cup",$count_calories_global);
		        $monounsaturated_fat += weight_value($r[$i],"monounsaturated_fat_grams_per_cup",$count_calories_global);
		        $polyunsaturated_fat += weight_value($r[$i],"polyunsaturated_fat_grams_per_cup",$count_calories_global);
		        $trans_fat += weight_value($r[$i],"trans_fat_grams_per_cup",$count_calories_global);
		        $cholesterol += weight_value($r[$i],"cholesterol_grams_per_cup",$count_calories_global);
		        
		        $sugars += weight_value($r[$i],"sugars_grams_per_cup",$count_calories_global);
		        $folate += weight_value($r[$i],"folate_milligrams_per_cup",$count_calories_global);
		        $caffeine += weight_value($r[$i],"caffeine_milligrams_per_cup",$count_calories_global);
		        
		        $vitamin_k += weight_value($r[$i],"vitamin_k_milligrams_per_cup",$count_calories_global);
		        $vitamin_c += weight_value($r[$i],"vitamin_c_milligrams_per_cup",$count_calories_global);
		        $vitamin_b6 += weight_value($r[$i],"vitamin_b6_milligrams_per_cup",$count_calories_global);
		        $vitamin_b12 += weight_value($r[$i],"vitamin_b12_micrograms_per_cup",$count_calories_global);
		        $vitamin_e += weight_value($r[$i],"vitamin_e_milligrams_per_cup",$count_calories_global);
		        $vitamin_d += weight_value($r[$i],"vitamin_d_milligrams_per_cup",$count_calories_global);
		        $vitamin_a += weight_value($r[$i],"vitamin_a_micrograms_per_cup",$count_calories_global);
	            
	        } else {
		        
		        $calories += unit_value($r[$i],"calories_per_cup",$count_calories_global);
		        $protein += unit_value($r[$i],"protein_grams_per_cup",$count_calories_global);
		        $fat += unit_value($r[$i],"fat_grams_per_cup",$count_calories_global);
		        $carbs += unit_value($r[$i],"carb_grams_per_cup",$count_calories_global);
		        $fiber += unit_value($r[$i],"fiber_grams_per_cup",$count_calories_global);
		        $calcium += unit_value($r[$i],"calcium_milligrams_per_cup",$count_calories_global);
		        $iron += unit_value($r[$i],"iron_milligrams_per_cup",$count_calories_global);
		        $magnesium += unit_value($r[$i],"magnesium_milligrams_per_cup",$count_calories_global);
		        
	            $phosphorus += unit_value($r[$i],"phosphorus_milligrams_per_cup",$count_calories_global);
		        $potassium += unit_value($r[$i],"potassium_milligrams_per_cup",$count_calories_global);
		        $sodium += unit_value($r[$i],"sodium_milligrams_per_cup",$count_calories_global);
		        
		        $zinc += unit_value($r[$i],"zinc_milligrams_per_cup",$count_calories_global);
		        $thiamin += unit_value($r[$i],"thiamin_milligrams_per_cup",$count_calories_global);
		        $riboflavin += unit_value($r[$i],"riboflavin_milligrams_per_cup",$count_calories_global);
		        $niacin += unit_value($r[$i],"niacin_milligrams_per_cup",$count_calories_global);
		        
		        $saturated_fat += unit_value($r[$i],"saturated_fat_grams_per_cup",$count_calories_global);
		        $monounsaturated_fat += unit_value($r[$i],"monounsaturated_fat_grams_per_cup",$count_calories_global);
		        $polyunsaturated_fat += unit_value($r[$i],"polyunsaturated_fat_grams_per_cup",$count_calories_global);
		        $trans_fat += unit_value($r[$i],"trans_fat_grams_per_cup",$count_calories_global);
		        $cholesterol += unit_value($r[$i],"cholesterol_grams_per_cup",$count_calories_global);
		        
		        $sugars += unit_value($r[$i],"sugars_grams_per_cup",$count_calories_global);
		        $folate += unit_value($r[$i],"folate_milligrams_per_cup",$count_calories_global);
		        $caffeine += unit_value($r[$i],"caffeine_milligrams_per_cup",$count_calories_global);
		        
		        $vitamin_k += unit_value($r[$i],"vitamin_k_milligrams_per_cup",$count_calories_global);
		        $vitamin_c += unit_value($r[$i],"vitamin_c_milligrams_per_cup",$count_calories_global);
		        $vitamin_b6 += unit_value($r[$i],"vitamin_b6_milligrams_per_cup",$count_calories_global);
		        $vitamin_b12 += unit_value($r[$i],"vitamin_b12_micrograms_per_cup",$count_calories_global);
		        $vitamin_e += unit_value($r[$i],"vitamin_e_milligrams_per_cup",$count_calories_global);
		        $vitamin_d += unit_value($r[$i],"vitamin_d_milligrams_per_cup",$count_calories_global);
		        $vitamin_a += unit_value($r[$i],"vitamin_a_micrograms_per_cup",$count_calories_global);
	        }
	    }

	    $total = $protein + $fat + $carbs;

	    return [
	    	"amounts" => [
		    	"calories" => $calories,
		    	"protein" => $protein,
		    	"fat" => $fat,
		    	"carbs" => $carbs,
		    	"fiber" => $fiber,
		    	"calcium" => $calcium,
		    	"iron" => $iron,
		    	"magnesium" => $magnesium,
		    	
		    	"phosphorus" => $phosphorus,
		    	"potassium" => $potassium,
		    	"sodium" => $sodium,
		    	
		    	"zinc" => $zinc,
		    	"thiamin" => $thiamin,
		    	"riboflavin" => $riboflavin,
		    	"niacin" => $niacin,
		    	
		    	"saturated_fat" => $saturated_fat,
		    	"monounsaturated_fat" => $monounsaturated_fat,
		    	"polyunsaturated_fat" => $polyunsaturated_fat,
		    	"trans_fat" => $trans_fat,
		    	"cholesterol" => $cholesterol,
		    	
		    	"sugars" => $sugars,
		    	"folate" => $folate,
		    	"caffeine" => $caffeine,
		    	
		    	"vitamin_k" => $vitamin_k,
		    	"vitamin_c" => $vitamin_c,
		    	"vitamin_b6" => $vitamin_b6,
		    	"vitamin_b12" => $vitamin_b12,
		    	"vitamin_e" => $vitamin_e,
		    	"vitamin_d" => $vitamin_d,
		    	"vitamin_a" => $vitamin_a

		    ],
		    "proportions" => [
		    	"protein_proportion" => $protein / $total,
				"fat_proportion" => $fat / $total,
				"carbs_proportion" => $carbs / $total
		    ]
	    ];
	}

	function compute_quality_offenders($r) {

		$global_qualities = display_qualities();
	    
	    $qualities = array();
	    for ($i=0; $i<count($global_qualities); $i++) {
	        $this_quality = array("name" => $global_qualities[$i],"offender_total" => 0, "offender_list" => array());
	        $qualities[$global_qualities[$i]] = $this_quality;
	    }
    	$dairy = Array();
    	$meat = Array();
    	
	    for ($i=0; $i<count($global_qualities); $i++) {
	        for ($j=0; $j<count($r); $j++) {
	        	if (is_null($r[$j]["vegetarian"])) {
	        		continue;
	        	}
	            if ($global_qualities[$i] == "kosher") {
	                if ($r[$j]["pork_free"] == 0) {
	                    $qualities["kosher"]["offender_total"] += 1;
	                    array_push($qualities[$global_qualities[$i]]["offender_list"],$r[$j]["ingredient_name"]);
	                } 
	                if ($r[$j]["dairy_free"] == 0) {
	                    array_push($dairy,$r[$j]["ingredient_name"]);
	                }
	                if ($r[$j]["vegetarian"] == 0) {
	                    array_push($meat,$r[$j]["ingredient_name"]);
	                }
	            } else if ($global_qualities[$i] == "pescatarian") {

	                if ($r[$j]["vegetarian"] == 0 && $r[$j]["fish_free"] == 1 && $r[$j]["shellfish_free"] == 1) {
	                    $qualities["pescatarian"]["offender_total"] += 1;
	                    array_push($qualities[$global_qualities[$i]]["offender_list"],$r[$j]["ingredient_name"]);
	                }

	            } else {
	                if ($r[$j][$global_qualities[$i]] == 0) {
	                    $qualities[$global_qualities[$i]]["offender_total"] += 1;
	                    array_push($qualities[$global_qualities[$i]]["offender_list"],$r[$j]["ingredient_name"]);
	                }
	            }
	        }
	    }

	    for ($i=0; $i<count($global_qualities); $i++) {
	        if ($global_qualities[$i] == "kosher") {
	            if (count($dairy) > 0 && count($meat) > 0) {
	                $qualities[$global_qualities[$i]]["offender_total"] += 1;
	                for ($k=0; $k<count($dairy); $k++) {
	                    array_push($qualities[$global_qualities[$i]]["offender_list"],$dairy[$k]);
	                }
	                for ($k=0; $k<count($meat); $k++) {
	                    array_push($qualities[$global_qualities[$i]]["offender_list"],$meat[$k]);
	                }
	            }
	        }
	    }

	    return $qualities;
	}

	function compute_yield($r) {

		if ($r[0]["recipe_makes_or_serves"] == "serves") {
			$n = convert_float_to_fraction($r[0]["recipe_makes_or_serves_amount"]);
			$t = "serves";
			$s = "Serves ".$n;
			$u = "people";
		} else {
			$n = convert_float_to_fraction($r[0]["recipe_makes_or_serves_amount"]);
			$t = "makes";
			$u = $r[0]["recipe_makes_or_serves_amount"] == 1 ? $r[0]["recipe_makes_or_serves_measure_id_singular"] : $r[0]["recipe_makes_or_serves_measure_id_plural"];
			$s = "Makes ".
				$n.
				" ".
				$u;
		}
		
		return [
			"amount" => $n,
			"type" => $t,
			"string" => $s,
			"unit" => $u
		];
	}

	function recipe_metadata($r,$y,$t) {

		$recipe_name = ucwords(urldecode($r[0]["recipe_name"]));
		$recipe_source = urldecode($r[0]["recipe_source_name"]);
		$raasipe_id = $r[0]["recipe_id"];
		$yield_type = $y["type"];
		$yield_amount = $y["amount"];
		$yield_unit = $y["unit"];
		$recipe_description = $r[0]["recipe_description"];
		$active_time_in_minutes = $t["data"]["active_time_in_minutes"];
		$inactive_time_in_minutes = $t["data"]["inactive_time_in_minutes"];

		return [
			"recipe_name" => $recipe_name,
			"recipe_source" => $recipe_source,
			"raasipe_id" => $raasipe_id,
			"yield_type" => $yield_type,
			"yield_amount" => $yield_amount,
			"yield_unit" => $yield_unit,
			"recipe_description" => $recipe_description,
			"active_time_in_minutes" => $active_time_in_minutes,
			"inactive_time_in_minutes" => $inactive_time_in_minutes
		];
	}

	function structure_recipe_steps($r) {

		$total = [];

		$steps = col_from_arr("step_order",$r);

		$step_vals = array_unique_better(array_values($steps));

		foreach ($step_vals as $i) {

			$materials = array_filter($r,function($x) use($i) { 
				return($x["step_order"] == $i); 
			});
			$tak = array_keys($materials);

			$this_step = array(
				"place_in_order"=>$i,
				"materials"=>array(),
				"instructions"=>array_values($materials)[0]["step_instructions"]
			);

			foreach ($materials as $k => $v) {
				array_push(
					$this_step["materials"],
					array(
						"ingredient_name" => $v["ingredient_name"],
						"raasipe_measure_id" => $v["measure_abbreviation"],
						"ingredient_amount" => $v["ingredient_amount"],
						"ingredient_preparation" => $v["ingredient_preparation"],
						"ingredient_variety" => $v["ingredient_size"]
					)
				);
			}
			array_push($total,$this_step);
		}
		return $total;
	}

	function recipe_query_stats($connect,$recipe,$equipment) {

		return [
			"connect_time" => round($connect,6),
			"recipe_query_time" => round($recipe,6),
			"equipment_query_time" => round($equipment,6)
		];
	}

	function recipe_equipment($e) {

		$total = array();

		foreach ($e as $i) {
			array_push(
				$total,
				array(
					"device_name" => $i["device_name"],
					"raasipe_equipment_id" => $i["equipment_id"],
					"purchase_link" => $i["purchase_link"],
					"icon_source" => $i["icon_source"],
					"rich_purchase_link" => $i["rich_purchase_link"],
					"native_purchase_link" => $i["native_purchase_link"]
				)
			);
		}

		return $total;
	}
	

?>
<?php
	
	open_connection();
	
	if (isset($_GET["fridge_id"])) {
		$fridge_q = "select distinct ingredient_id from fridge_entry where fridge_id = '".$_GET["fridge_id"]."';";
		$fridge = array_column(execute_query($fridge_q),"ingredient_id");
		$fridge = "select substitutes_as from ingredient where id in ('".implode("','",$fridge)."')";
	} else {
		$fridge = "('xkcd')";
	}

	$recipe_query = "select r.name recipe_name ".
		" ,r.source_name recipe_source_name ".
		" ,r.id recipe_id ".
		" ,r.makes_or_serves recipe_makes_or_serves".
		" ,r.makes_or_serves_amount * ".$multiplier." recipe_makes_or_serves_amount".
		" ,r.makes_or_serves_measure_id recipe_makes_or_serves_measure_id".
		" ,r.course recipe_course".
		" ,r.origin recipe_cuisine".
		" ,r.description recipe_description".

		" ,r_msr.short_name recipe_makes_or_serves_measure_id_singular".
		" ,r_msr.plural_name recipe_makes_or_serves_measure_id_plural".

		" ,r.created_date recipe_created_date".
		" ,case when r.image_exists = 1 then replace(r.name,'%20','-') else '' end recipe_image".

		" ,s.place_in_order step_order".
		" ,s.instructions step_instructions".
		" ,s.id step_id".
		" ,s.active_time_in_minutes active_time_in_minutes".
		" ,s.inactive_time_in_minutes inactive_time_in_minutes".
		" ,case when m.amount = 1 then i.singular_name else i.plural_name end ingredient_name".

		" ,i.singular_name ingredient_singular".
		" ,i.plural_name ingredient_plural".
		" ,case when i.substitutes_as in (".$fridge.") then 1 else 0 end ingredient_in_fridge".
		
		" ,m.measure_id measure_abbreviation".
		" ,m.amount * ".$multiplier." ingredient_amount ".
		" ,m.preparation ingredient_preparation".
		" ,m.size ingredient_size".

		" ,i.".implode(", i.",raw_qualities()).
		" ,i.id ingredient_id".
		" ,i.supermarket_section ingredient_supermarket_section".

		" ,msr.oz measure_oz".
		" ,msr.weight_or_volume measure_type".
		" ,m.count_calories count_calories".
		" ,msr.plural_name measure_plural".


		" ,n.unit_multiple unit_multiple". //or use from food_nutrition table
		" ,n.oz_multiple oz_multiple".
		" ,n.calories_per_cup calories_per_cup".
		" ,n.protein_grams_per_cup protein_grams_per_cup".
		" ,n.fat_grams_per_cup fat_grams_per_cup".
		" ,n.carb_grams_per_cup carb_grams_per_cup".
		
		" ,n.fiber_g_per_cup fiber_grams_per_cup".
		
		" ,n.calcium_mg_per_cup calcium_milligrams_per_cup".
		" ,n.iron_mg_per_cup iron_milligrams_per_cup".
		
		" ,n.magnesium_mg_per_cup magnesium_milligrams_per_cup".
		" ,n.phosphorus_mg_per_cup phosphorus_milligrams_per_cup".
		" ,n.potassium_mg_per_cup potassium_milligrams_per_cup".
		" ,n.sodium_mg_per_cup sodium_milligrams_per_cup".
		
		" ,n.zinc_mg_per_cup zinc_milligrams_per_cup".
		" ,n.thiamin_mg_per_cup thiamin_milligrams_per_cup".
		" ,n.riboflavin_mg_per_cup riboflavin_milligrams_per_cup".
		" ,n.niacin_mg_per_cup niacin_milligrams_per_cup".
		
		" ,n.saturated_fat_g_per_cup saturated_fat_grams_per_cup".
		" ,n.monounsaturated_fat_g_per_cup monounsaturated_fat_grams_per_cup".
		" ,n.polyunsaturated_fat_g_per_cup polyunsaturated_fat_grams_per_cup".
		" ,n.trans_fat_g_per_cup trans_fat_grams_per_cup".
		" ,n.cholesterol_g_per_cup cholesterol_grams_per_cup".
		
		" ,n.sugars_g_per_cup sugars_grams_per_cup".
		" ,n.folate_mg_per_cup folate_milligrams_per_cup".
		" ,n.caffeine_g_per_cup * 1000 caffeine_milligrams_per_cup".
		
		" ,n.vitamin_k_mg_per_cup vitamin_k_milligrams_per_cup".
		" ,n.vitamin_c_mg_per_cup vitamin_c_milligrams_per_cup".
		" ,n.vitamin_b6_mg_per_cup vitamin_b6_milligrams_per_cup".
		" ,n.vitamin_b12_mg_per_cup / 1000 vitamin_b12_micrograms_per_cup".
		" ,n.vitamin_e_mg_per_cup vitamin_e_milligrams_per_cup".
		" ,n.vitamin_d_mg_per_cup vitamin_d_milligrams_per_cup".
		" ,n.vitamin_a_mg_per_cup / 1000 vitamin_a_micrograms_per_cup".

		" ,r.description recipe_description".

		" from recipe r ".
	    " join step s on s.recipe_id = r.id".
	    " left join material m on m.step_id = s.id".
	    " left join ingredient i on m.ingredient_id = i.id".
	    " left join measure msr ON m.measure_id = msr.short_name".
	    " left join food_nutrient n on i.food_ndbno = n.food_ndbno".
	    " left join measure r_msr on r.makes_or_serves_measure_id = r_msr.short_name".
	    " where replace(replace(".$recipe_query_target["column"].",'%27',''),'+',' ') in (".$recipe_query_target["value"].")".
	    " order by s.place_in_order;";
		
	$init_time = chained_interval();
	
	$connect_time = chained_interval();
	$recipe_raw = execute_query($recipe_query);
	$recipe_id = $recipe_raw[0]["recipe_id"];
	$recipe_query_time = chained_interval();
	
	$access_query = "insert into recipe_access ".
		" (recipe_id) ".
		" values ".
		" (".$recipe_id.");";
			
	execute_insertion($access_query);
	
	close_connection();

?>
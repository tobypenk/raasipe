CREATE VIEW recipe_macro_summary AS
	SELECT r.id recipe_id
		,r.name recipe_name
        ,SUM(CASE WHEN msr.weight_or_volume = 'volume'
			THEN i.calories_per_cup / 8 * m.amount * msr.oz * m.count_calories
			WHEN msr.weight_or_volume = 'weight' 
				THEN i.calories_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories
			ELSE i.calories_per_cup * i.unit_multiple * m.amount * m.count_calories
		END) calories
        ,SUM(CASE WHEN msr.weight_or_volume = 'volume'
			THEN i.protein_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories
			WHEN msr.weight_or_volume = 'weight' 
				THEN i.protein_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories
			ELSE i.protein_grams_per_cup * i.unit_multiple * m.amount * m.count_calories
		END) protein
        ,SUM(CASE WHEN msr.weight_or_volume = 'volume'
			THEN i.fat_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories
			WHEN msr.weight_or_volume = 'weight' 
				THEN i.fat_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories
			ELSE i.fat_grams_per_cup * i.unit_multiple * m.amount * m.count_calories
		END) fat
		,SUM(CASE WHEN msr.weight_or_volume = 'volume'
			THEN i.carb_grams_per_cup / 8 * m.amount * msr.oz * m.count_calories
			WHEN msr.weight_or_volume = 'weight' 
				THEN i.carb_grams_per_cup * i.oz_multiple * msr.oz * m.amount * m.count_calories
			ELSE i.carb_grams_per_cup * i.unit_multiple * m.amount * m.count_calories
		END) carbs
	FROM recipe r
    JOIN step s ON s.recipe_id = r.id
    JOIN material m ON m.step_id = s.id
    JOIN ingredient i ON m.ingredient_id = i.id
    LEFT JOIN measure msr ON m.measure_id = msr.short_name
    GROUP BY r.id, r.name;  
    
create view recipe_dietary_qualities as
SELECT r.name `name` 
	, r.id recipe_id
	, concat(
		case when sum(case when i.vegan = 0 then 1 else 0 end) = 0 
			then 'vegan' else '' end, " "
		, case when sum(case when i.vegetarian = 0 then 1 else 0 end) = 0 
			then 'vegetarian' else '' end, " "
		, case when sum(case when i.gluten_free = 0 then 1 else 0 end) = 0 
			then 'gluten_free' else '' end, " "
		, case when sum(case when i.sugar_free = 0 then 1 else 0 end) = 0 
			then 'sugar_free' else '' end, " "
		, case when sum(case when i.pork_free = 0 then 1 else 0 end) = 0 
			then 'pork_free' else '' end, " "
		, case when sum(case when i.alcohol_free = 0 then 1 else 0 end) = 0 
			then 'alcohol_free' else '' end, " "
		, case when sum(case when i.dairy_free = 0 then 1 else 0 end) = 0 
			then 'dairy_free' else '' end, " "
		, case when sum(case when i.nut_free = 0 then 1 else 0 end) = 0 
			then 'nut_free' else '' end, " "
		, case when sum(case when i.fish_free = 0 then 1 else 0 end) = 0 
			then 'fish_free' else '' end, " "
		, case when sum(case when i.shellfish_free = 0 then 1 else 0 end) = 0 
			then 'shellfish_free' else '' end, " "
		, case when 
			sum(case when i.pork_free = 0 then 1 else 0 end) = 0 
				and (sum(case when i.dairy_free = 0 then 1 else 0 end) = 0 or 
					sum(case when i.vegetarian = 0 then 1 else 0 end) = 0)
				then 'kosher' else '' end, " "
            ) quality_list
		,case when sum(case when i.vegan = 0 then 1 else 0 end) = 0 
			then 1 else 0 end vegan
		, case when sum(case when i.vegetarian = 0 then 1 else 0 end) = 0 
			then 1 else 0 end vegetarian
		, case when sum(case when i.gluten_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end gluten_free
		, case when sum(case when i.sugar_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end sugar_free
		, case when sum(case when i.pork_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end pork_free
		, case when sum(case when i.alcohol_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end alcohol_free
		, case when sum(case when i.dairy_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end dairy_free
		, case when sum(case when i.nut_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end nut_free
		, case when sum(case when i.fish_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end fish_free
		, case when sum(case when i.shellfish_free = 0 then 1 else 0 end) = 0 
			then 1 else 0 end shellfish_free
		, case when 
			sum(case when i.pork_free = 0 then 1 else 0 end) = 0 
				and (sum(case when i.dairy_free = 0 then 1 else 0 end) = 0 or 
					sum(case when i.vegetarian = 0 then 1 else 0 end) = 0)
				then 1 else 0 end kosher
		, case when sum(case when i.fish_free = 1 and shellfish_free = 1 and i.vegetarian = 0 then 1 else 0 end) = 0 
			then 1 else 0 end pescatarian
    from recipe r 
		left join step s on s.recipe_id = r.id
		left join material m on m.step_id = s.id
        left join ingredient i on m.ingredient_id = i.id
	group by r.name;
	
create view recipe_timing as
	select sum(active_time_in_minutes) active_time_in_minutes
		, sum(inactive_time_in_minutes) inactive_time_in_minutes
		, r.id recipe_id
		from recipe r
		join step s on s.recipe_id = r.id
		group by r.id;

create view recipe_summary as
	select m.recipe_id, m.recipe_name, m.calories, m.protein, m.fat, m.carbs
		, ifnull(m.protein / (m.protein + m.fat + m.carbs),0) protein_proportion
        , ifnull(m.fat / (m.protein + m.fat + m.carbs),0) fat_proportion
        , ifnull(m.carbs / (m.protein + m.fat + m.carbs),0) carbs_proportion
        , q.quality_list, q.vegan, q.vegetarian, q.gluten_free, q.sugar_free, q.pork_free, q.alcohol_free
			, q.dairy_free, q.nut_free, q.fish_free, q.shellfish_free, q.kosher, q.pescatarian
		,t.inactive_time_in_minutes, t.active_time_in_minutes
	from recipe_macro_summary m
    join recipe_timing t on t.recipe_id = m.recipe_id
    join recipe_dietary_qualities q 
		on m.recipe_id = q.recipe_id and m.recipe_name = q.name;
        
create view recipe_display as 
	SELECT r.name recipe_name
			,r.id recipe_id
			,r.description recipe_description
				,r.source_name recipe_source
				,r.makes_or_serves makes_or_serves
				,r.makes_or_serves_amount makes_or_serves_amount
				,r.makes_or_serves_measure_id makes_or_serves_measure_id
				,summ.calories calories
				,summ.protein protein
				,summ.fat fat
				,summ.carbs carbs
				,summ.active_time_in_minutes active_time_in_minutes
				,summ.inactive_time_in_minutes inactive_time_in_minutes

				,summ.vegan vegan
				,summ.vegetarian vegetarian
				,summ.kosher kosher
				,summ.gluten_free gluten_free
				,summ.nut_free nut_free
				,summ.alcohol_free alcohol_free
				,summ.shellfish_free shellfish_free
				,summ.dairy_free dairy_free
				,summ.pescatarian pescatarian
				,summ.sugar_free sugar_free 
				,summ.protein_proportion protein_proportion 
				,summ.fat_proportion fat_proportion 
				,summ.carbs_proportion carbs_proportion 

				,s.place_in_order step_order
				,s.instructions step_instructions
				,m.measure_id measure_abbreviation
				,m.amount ingredient_amount
				,m.preparation preparation
				,m.size size

				,i.id ingredient_id
				,case when m.amount = 1 then i.singular_name else i.plural_name end ingredient_name

			 from recipe r 
				JOIN step s ON r.id = s.recipe_id 
				JOIN material m ON s.id = m.step_id
				JOIN ingredient i ON m.ingredient_id = i.id
				JOIN recipe_summary summ ON summ.recipe_id = r.id
				LEFT JOIN measure msr ON m.measure_id = msr.short_name
			ORDER BY r.id, s.place_in_order;

create or replace view recipe_material as 
	select r.id recipe_id, r.name recipe_name, m.ingredient_id ingredient_id
	from recipe r 
    join step s on s.recipe_id = r.id 
    join material m on m.step_id = s.id;
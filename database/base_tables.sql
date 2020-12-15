create table ingredient (
	id int 						not null auto_increment
	, singular_name varchar(255) 		not null
    , plural_name varchar(255)  not null
	, vegetarian tinyint(1) 	not null default 0
    , vegan tinyint(1) 			not null default 0
    , gluten_free tinyint(1) 	not null default 0
    , sugar_free tinyint(1) 	not null default 0
    , alcohol_free tinyint(1) 	not null default 0
    , pork_free tinyint(1) 		not null default 0
    , nut_free tinyint(1) 		not null default 0
    , shellfish_free tinyint(1) not null default 0
    , fish_free tinyint(1) 		not null default 0
    , dairy_free tinyint(1) 	not null default 0
    , created_datetime datetime default current_timestamp
    , `function` varchar(255)
    , supermarket_section varchar(255)
    , expense_index_1_to_6 tinyint(1) default 2
    , genus varchar(255)
    , ancestor varchar(255)
    , texture varchar(255)
    , calories_per_cup decimal(16,8)
    , fat_grams_per_cup decimal(16,8)
    , carb_grams_per_cup decimal(16,8)
    , protein_grams_per_cup decimal(16,8)
    , oz_multiple decimal(16,8)
    , unit_multiple decimal(16,8)
    , unit_name varchar(255)
    , unit_price decimal(10,2)
    , purchase_unit_oz decimal(16,8)
    , substitutes_as varchar(255)
    , unit_name varchar(255)
    , unit_price decimal(10,2) default 0
    , purchase_unit_oz decimal(20,10) default 0
    , food_ndbno int not null
    , primary key (id)
    , shelf_life_days int
    , user_facing tinyint(1)
);

create table measure (
    short_name varchar(255) 	not null
    , long_name varchar(255) 	not null
    , plural_name varchar(255) 	not null
    , oz float(16,8) unsigned
    , weight_or_volume varchar(255)
	, imperial_or_metric varchar(255)
    , liquid tinyint(1) 		not null
    , round tinyint(1) unsigned
    , fraction tinyint(1)		not null
    , created_date date			not null
    , primary key (short_name)
);

create table recipe (
	id int 									not null auto_increment
    , name varchar(255) 					not null
    , course varchar(255) 					not null
    , origin varchar(255)
    , makes_or_serves varchar(255) 			not null
    , makes_or_serves_amount float(16,8) 	not null
    , makes_or_serves_measure_id varchar(255) 		not null
    , created_date date						not null
	, source_name varchar(255)
    , source_url varchar(255)
    , description text
    , image_exists tinyint(1) default 0
    , primary key (id)
    , foreign key (makes_or_serves_measure_id) references measure(short_name)
);

create table step (
	id int 				not null auto_increment
    , instructions text not null
    , active_time_in_minutes int
    , inactive_active_time_in_minutes int
    , recipe_id int		not null
    , place_in_order int not null
    , created_date date not null
    , primary key (id)
    , foreign key (recipe_id) references recipe(id)
);

create table material (
	id int 					not null auto_increment
    , step_id int 			not null
	, ingredient_id int 	not null
    , measure_id varchar(255) 		not null
    , amount float(16,8)
    , preparation varchar(255)
    , size varchar(255)
    , created_date date 	not null
    , count_calories tinyint(1) default 1 not null
    , primary key (id)
    , foreign key (step_id) references step(id)
    , foreign key (ingredient_id) references ingredient(id)
    , foreign key (measure_id) references measure(id)
);

create table equipment (
	id int not null auto_increment
    , device_name varchar(255) not null
    , brand_name varchar(255) not null
    , purchase_link text
    , rich_purchase_link text
    , native_purchase_link text
    , icon_source text
    , description text
    , approximate_price decimal(12,6)
    , ubiquity_1_to_5 int
    , primary key (id)
);

create table recipe_equipment (
	recipe_id int not null
	, equipment_id int not null
    , foreign key (recipe_id) references recipe (id)
    , foreign key (equipment_id) references equipment (id)
    , primary key (equipment_id, recipe_id)
);

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

create view recipe_timing as
	select sum(active_time_in_minutes) active_time_in_minutes
		, sum(inactive_time_in_minutes) inactive_time_in_minutes
		, r.id recipe_id
		from recipe r
		join step s on s.recipe_id = r.id
		group by r.id;

select r.name recipe
	, r.makes_or_serves
    , r.makes_or_serves_amount
    , r.description
    , r.id recipe_id
    , s.active_time_in_minutes
    , s.inactive_time_in_minutes
    , s.place_in_order step_number
    , s.instructions step_instructions
    , i.id ingredient_id
    , i.singular_name ingredient
    , m.amount ingredient_amount
    , m.measure_id ingredient_measure
	from recipe r
	join step s on s.recipe_id = r.id
    join material m on m.step_id = s.id
    join ingredient i on m.ingredient_id = i.id
    where r.id in (select * from tmp);

create table food_ndb(
	ndbno int not null
    , primary key (ndbno)
	, food_name varchar(255)
    , food_group varchar(255)
    , food_subgroup varchar(255)
    , fdc_id int
);

create table food_nutrient(
	food_name varchar(255)
	, food_ndbno int not null
    , foreign key (food_ndbno) references food_ndb (ndbno)
    , primary key (food_ndbno)

    , calories_per_cup decimal (16,8)
    , fat_grams_per_cup decimal (16,8)
    , carb_grams_per_cup decimal (16,8)
    , protein_grams_per_cup decimal (16,8)
    
    , fiber_g_per_cup decimal (16,8)
    , calcium_mg_per_cup decimal (16,8)
    , iron_mg_per_cup decimal (16,8)
    , magnesium_mg_per_cup decimal (16,8)
    , phosphorus_mg_per_cup decimal (16,8)
    , potassium_mg_per_cup decimal (16,8)
    , sodium_mg_per_cup decimal (16,8)
    
    , zinc_mg_per_cup decimal (16,8)
    , thiamin_mg_per_cup decimal (16,8)
    , riboflavin_mg_per_cup decimal (16,8)
    , niacin_mg_per_cup decimal (16,8)
    
    , saturated_fat_g_per_cup decimal (16,8)
    , monounsaturated_fat_g_per_cup decimal (16,8)
    , polyunsaturated_fat_g_per_cup decimal (16,8)
    , trans_fat_g_per_cup decimal (16,8)
    , cholesterol_g_per_cup decimal (16,8)
    
    , sugars_g_per_cup decimal (16,8)
    , folate_mg_per_cup decimal (16,8)
    , caffeine_g_per_cup decimal (16,8)
    
	, vitamin_k_mg_per_cup decimal (16,8)
	, vitamin_c_mg_per_cup decimal (16,8)
	, vitamin_b6_mg_per_cup decimal (16,8)
	, vitamin_b12_mg_per_cup decimal (16,8)
	, vitamin_e_mg_per_cup decimal (16,8)
	, vitamin_d_mg_per_cup decimal (16,8)
	, vitamin_a_mg_per_cup decimal (16,8)
    
	, oz_multiple decimal (16,8)
	, unit_multiple decimal (16,8)
	, unit_name varchar(255)
);

use recipeappdata;

create table usda_nutrients (
	id int not null
    , primary key (id)
    , `name` varchar(255) not null
    , `group` varchar(255) not null
);

create table usda_foods (
	ndbno int not null
    , primary key (ndbno)
    , food_name varchar(255) not null
    , `group` varchar(255)
);
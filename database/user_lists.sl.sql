create table grocery_list (
	id int not null auto_increment
    , primary key (id)
    , name varchar(255)
);

create table grocery_list_item (
	entry varchar(255)
    , list_id int not null
    , foreign key (list_id) references grocery_list (id)
    , supermarket_section varchar(255)
);

create table fridge (
	id int not null auto_increment
    , primary key (id)
    , user_id int
    , name varchar(255)
);

create table fridge_entry (
	id int not null auto_increment
    , primary key (id)
	, fridge_id int not null
    , foreign key (fridge_id) references fridge (id)
    , ingredient_name varchar(255)
    , ingredient_id int
    , foreign key (ingredient_id) references ingredient (id)
    , shelf_life_start datetime default current_timestamp
);
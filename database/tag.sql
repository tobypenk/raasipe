create table tag (
	id varchar(40) not null
    , created_date date not null
    , primary key (id)
);

create table ingredient_tag_map (
	tag_id varchar(40) not null
    , ingredient_id int not null
    , foreign key (tag_id) references tag (id)
    , foreign key (ingredient_id) references ingredient (id)
);

create table recipe_tag_map (
	tag_id varchar(40) not null
    , recipe_id int not null
    , foreign key (tag_id) references tag (id)
    , foreign key (recipe_id) references recipe (id)
);
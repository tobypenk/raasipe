create table genetics(
	genus varchar(255) not null
    ,primary key (genus)
    ,family varchar(255)
    ,`order` varchar(255)
    ,`group` varchar(255)
);

#insert into genetics values('seriola','carangidae','perciformes','chordata');
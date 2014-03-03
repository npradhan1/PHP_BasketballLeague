create table team (
  dcode varchar(20),
  tno integer(2),
  coach varchar(25),
  primary key (dcode,tno),
  foreign key (dcode) references division(dcode)
);

insert into team values ('G10',1,'Adolphus Harper');
insert into team values ('G10',2,'Mary Pierce');
insert into team values ('G10',3,'Dean Harris');
insert into team values ('G10',4,'Damon Aluisy');
insert into team values ('G10',5,'Rick Epstein');
insert into team values ('G10',6,'Kari Beebe');

insert into team values ('G13',1,'Penny Chasko');
insert into team values ('G13',2,'Bill Freeman');
insert into team values ('G13',3,'Mary Pierce');
insert into team values ('G13',4,'Rick Epstein');
insert into team values ('G13',5,'Greg Peacock');
insert into team values ('G13',6,'Diana Meador');

insert into team values ('G1418',1,'Kiech');
insert into team values ('G1418',2,'Jorgen');
insert into team values ('G1418',3,'Palace');
insert into team values ('G1418',4,'Howard');
insert into team values ('G1418',5,'Johnson');
insert into team values ('G1418',6,'Lane');

insert into team values ('B10I',1,'Kevin Kennedy');
insert into team values ('B10I',2,'Chip Kaiser');
insert into team values ('B10I',3,'Guillermo Goldsztein');
insert into team values ('B10I',4,'Kevin Miles');
insert into team values ('B10I',5,'Chris Lloyd');
insert into team values ('B10I',6,'Scott Schewe');

insert into team values ('B10II',11,'Condon');
insert into team values ('B10II',12,'Benson');
insert into team values ('B10II',13,'White');
insert into team values ('B10II',14,'Fulton');
insert into team values ('B10II',15,'Doolittle');
insert into team values ('B10II',16,'Pierce');
insert into team values ('B10II',17,'Chasko');
insert into team values ('B10II',18,'Butler');
insert into team values ('B10II',19,'Jones');

insert into team values ('B10II',21,'Lind');
insert into team values ('B10II',22,'Spurlock');
insert into team values ('B10II',23,'Linder');
insert into team values ('B10II',24,'Tabb');
insert into team values ('B10II',25,'Wideman');
insert into team values ('B10II',26,'Musser');
insert into team values ('B10II',27,'Morris');
insert into team values ('B10II',28,'Adler');
insert into team values ('B10II',29,'McGill');

insert into team values ('B12I',1,'Ross (Craig)');
insert into team values ('B12I',2,'Litman (Seth)');
insert into team values ('B12I',3,'Ashmore (Jay)');
insert into team values ('B12I',4,' Morrison (Justin)');
insert into team values ('B12I',5,'Cary (Jason)');
insert into team values ('B12I',6,'Brown (Doug)');

insert into team values ('B12II',11,'Benjamin');
insert into team values ('B12II',12,'Elwart');
insert into team values ('B12II',13,'Lorimer');
insert into team values ('B12II',14,'Goldsztein');
insert into team values ('B12II',15,'Raffa');
insert into team values ('B12II',16,'Heron');
insert into team values ('B12II',17,'McAlvanah');
insert into team values ('B12II',18,'Campbell');
insert into team values ('B12II',19,'Free');

insert into team values ('B12II',21,'Parker');
insert into team values ('B12II',22,'Wittgen');
insert into team values ('B12II',23,'Morris');
insert into team values ('B12II',24,'Sandberg');
insert into team values ('B12II',25,'Aluisy');
insert into team values ('B12II',26,'Malott');
insert into team values ('B12II',27,'Comeaux');
insert into team values ('B12II',28,'Kelly');
insert into team values ('B12II',29,'Whaley');

insert into team values ('B14I',1,'Minetos');
insert into team values ('B14I',2,'Weiss');
insert into team values ('B14I',3,'Aarif');
insert into team values ('B14I',4,'Cole');
insert into team values ('B14I',5,'Morrison');
insert into team values ('B14I',6,'Astarita');

insert into team values ('B14II',11,'Kelly');
insert into team values ('B14II',12,'Aarif');
insert into team values ('B14II',13,'Oxman');
insert into team values ('B14II',14,'Uffner');
insert into team values ('B14II',15,'Miller');
insert into team values ('B14II',16,'McCullough');
insert into team values ('B14II',17,'Mark');
insert into team values ('B14II',18,'Krater/Miller');
insert into team values ('B14II',19,'Dotter');

insert into team values ('B14II',21,'Robinson');
insert into team values ('B14II',22,'Minetos');
insert into team values ('B14II',23,'Merrigen');
insert into team values ('B14II',24,'Vahab');
insert into team values ('B14II',25,'Mix');
insert into team values ('B14II',26,'Saylors');
insert into team values ('B14II',27,'Ehrhardt');
insert into team values ('B14II',28,'McSorely');
insert into team values ('B14II',29,'Jordan');

insert into team values ('B1618II',11,'Hughes');
insert into team values ('B1618II',12,'Dukes');
insert into team values ('B1618II',13,'Humble');
insert into team values ('B1618II',14,'Welch');
insert into team values ('B1618II',15,'Robinson');
insert into team values ('B1618II',16,'Belcher');
insert into team values ('B1618II',17,'Rockwell');
insert into team values ('B1618II',18,'Ehrhardt');
insert into team values ('B1618II',19,'Finnegan');
insert into team values ('B1618II',10,'Ratnowsky');

insert into team values ('B1618I',1,'Karcher');
insert into team values ('B1618I',2,'Humble');
insert into team values ('B1618I',3,'Storey');
insert into team values ('B1618I',4,'Morrison');
insert into team values ('B1618I',5,'Komitor');
insert into team values ('B1618I',6,'Richardson');
insert into team values ('B1618I',7,'Wright');
insert into team values ('B1618I',8,'Markowitz');

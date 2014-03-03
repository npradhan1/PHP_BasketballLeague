drop table division;
create table division (
  dcode varchar(20),
  dname varchar(50),
  primary key (dcode)
);
insert into division values ('G10','10U Girls');
insert into division values ('G13','13U Girls');
insert into division values ('G1418','14-18U Girls');
insert into division values ('B10I','10U Boys Division I');
insert into division values ('B10II','10U Boys Division II');
insert into division values ('B12I','12U Boys Division I');
insert into division values ('B12II','12U Boys Division II');
insert into division values ('B14I','14U Boys Division I');
insert into division values ('B14II','14U Boys Division II');
insert into division values ('B1618I','16-18U Boys Division I');
insert into division values ('B1618II','16-18U Boys Division II');

drop table team;
create table team (
  dcode varchar(20),
  tno integer(2),
  coach varchar(25),
  primary key (dcode,tno),
  foreign key (dcode) references division(dcode)
);
insert into team values ('B1618I',1,'Karcher');
insert into team values ('B1618I',2,'Humble');
insert into team values ('B1618I',3,'Storey');
insert into team values ('B1618I',4,'Morrison');
insert into team values ('B1618I',5,'Komitor');
insert into team values ('B1618I',6,'Richardson');
insert into team values ('B1618I',7,'Wright');
insert into team values ('B1618I',8,'Markowitz');

drop table schedule;
create table schedule (
  dcode varchar(20),
  gid integer(3),
  gametime datetime,
  gym varchar(25),
  awayteam integer(2),
  hometeam integer(2),
  awayscore integer(3),
  homescore integer(3),
  primary key (dcode,gid),
  foreign key (dcode,awayteam) references team(dcode,tno),
  foreign key (dcode,hometeam) references team(dcode,tno)
);
insert into schedule values ('B1618I',1,'2012-12-01 18:50:00','East Cobb',6,1,49,52);
insert into schedule values ('B1618I',2,'2012-12-01 10:00:00','East Cobb',7,5,50,64);

insert into schedule values ('B1618I',3,'2012-12-02 13:10:00','Walton HS Main',1,7,54,49);
insert into schedule values ('B1618I',4,'2012-12-02 14:20:00','Walton HS Main',6,4,43,40);
insert into schedule values ('B1618I',5,'2012-12-02 15:30:00','Walton HS Main',5,2,34,63);
insert into schedule values ('B1618I',6,'2012-12-02 16:40:00','Walton HS Main',8,3,47,36);

insert into schedule values ('B1618I',7,'2012-12-08 18:50:00','East Cobb',8,5,43,36);
insert into schedule values ('B1618I',8,'2012-12-08 10:00:00','East Cobb',2,3,81,77);

insert into schedule values ('B1618I',9, '2012-12-09 13:10:00','Walton HS Main',3,7,55,48);
insert into schedule values ('B1618I',10,'2012-12-09 14:20:00','Walton HS Main',5,1,35,48);
insert into schedule values ('B1618I',11,'2012-12-09 15:30:00','Walton HS Main',2,6,53,45);
insert into schedule values ('B1618I',12,'2012-12-09 16:40:00','Walton HS Main',4,8,52,51);

insert into schedule values ('B1618I',13,'2012-12-15 13:00:00','Phillips Arena',7,2,60,70);
insert into schedule values ('B1618I',14,'2012-12-15 14:20:00','Phillips Arena',3,1,55,60);
insert into schedule values ('B1618I',15,'2012-12-15 18:50:00','East Cobb',4,5,43,54);
insert into schedule values ('B1618I',16,'2012-12-15 10:00:00','East Cobb',8,6,39,32);

insert into schedule values ('B1618I',17,'2012-12-16 13:10:00','Walton HS Main',3,5,52,40);
insert into schedule values ('B1618I',18,'2012-12-16 14:20:00','Walton HS Main',7,6,59,31);
insert into schedule values ('B1618I',19,'2012-12-16 15:30:00','Walton HS Main',1,4,52,59);
insert into schedule values ('B1618I',20,'2012-12-16 16:40:00','Walton HS Main',2,8,56,42);

insert into schedule values ('B1618I',21,'2013-1-13 13:10:00','Walton HS Main',3,7,-1,-1);
insert into schedule values ('B1618I',22,'2013-1-13 14:20:00','Walton HS Main',4,5,-1,-1);
insert into schedule values ('B1618I',23,'2013-1-13 15:30:00','Walton HS Main',6,2,46,53);
insert into schedule values ('B1618I',24,'2013-1-13 16:40:00','Walton HS Main',1,8,51,50);

insert into schedule values ('B1618I',25,'2013-1-19 18:50:00','East Cobb',3,1,-1,-1);
insert into schedule values ('B1618I',26,'2013-1-19 10:00:00','East Cobb',4,7,-1,-1);

insert into schedule values ('B1618I',27,'2013-1-20 13:10:00','Walton HS Main',8,1,-1,-1);
insert into schedule values ('B1618I',28,'2013-1-20 14:20:00','Walton HS Main',5,7,-1,-1);
insert into schedule values ('B1618I',29,'2013-1-20 15:30:00','Walton HS Main',2,4,-1,-1);
insert into schedule values ('B1618I',30,'2013-1-20 16:40:00','Walton HS Main',3,6,-1,-1);

insert into schedule values ('B1618I',31,'2013-1-26 18:50:00','East Cobb',4,2,-1,-1);
insert into schedule values ('B1618I',32,'2013-1-26 10:00:00','East Cobb',6,8,-1,-1);

insert into schedule values ('B1618I',33,'2013-1-27 13:10:00','Walton HS Main',3,4,-1,-1);
insert into schedule values ('B1618I',34,'2013-1-27 14:20:00','Walton HS Main',5,6,-1,-1);
insert into schedule values ('B1618I',35,'2013-1-27 15:30:00','Walton HS Main',1,2,-1,-1);
insert into schedule values ('B1618I',36,'2013-1-27 16:40:00','Walton HS Main',7,8,-1,-1);

drop table users;
create table users (
  userid varchar(15),
  password varchar(25),
  usertype integer(1),
  divisionCode varchar(20),
  teamNumber integer(2),
  primary key (userid),
  foreign key (divisionCode,teamNumber) references team(dcode,tno)
);



insert into users values ('clark','clark',1,null,null);



insert into users values ('kari','kari',2,'G10',null);
insert into users values ('diana','diana',2,'G13',null);
insert into users values ('mark','mark',2,'G1418',null);
insert into users values ('kevin','kevin',2,'B10I',null);
insert into users values ('shane','shane',2,'B10II',null);
insert into users values ('seth','seth',2,'B12I',null);
insert into users values ('guillermo','guillenrmo',2,'B12II',null);
insert into users values ('peter','peter',2,'B14I',null);
insert into users values ('alex','alex',2,'B14II',null);
insert into users values ('chip','chip',2,'B1618I',null);
insert into users values ('brian','brian',2,'B1618II',null);



insert into users values ('humble','humble',3,'B1618I',2);







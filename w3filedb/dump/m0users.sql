drop database if exists `a2db`;
create database `a2db`;
use `a2db`;

drop user if exists 'a2cn1'@'localhost';
create user 'a2cn1'@'localhost';
set password for 'a2cn1'@'localhost' = password('m7jack');
grant select, lock tables, show view on a2db.* to 'a2cn1'@'localhost';

drop user if exists 'a2cn2'@'localhost';
create user 'a2cn2'@'localhost';
set password for 'a2cn2'@'localhost' = password('minnie7j');
grant all on a2db.* to 'a2cn2'@'localhost';

flush privileges;

source m1db.sql;


/*
drop database if exists `s1db`;
create database `s1db`;
use `s1db`;
*/

drop user if exists 'st1'@'localhost';
create user 'st1'@'localhost';
set password for 'st1'@'localhost' = password('dec25');
grant select, lock tables, show view on s1db.* to 'st1'@'localhost';

drop user if exists 'st2'@'localhost';
create user 'st2'@'localhost';
set password for 'st2'@'localhost' = password('stDec25');
grant all on s1db.* to 'st2'@'localhost';

flush privileges;




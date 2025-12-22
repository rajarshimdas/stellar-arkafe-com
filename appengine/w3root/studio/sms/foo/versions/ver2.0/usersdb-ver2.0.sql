# Upgrade to version 2.0
#----------------------------------------
# usersdb
#----------------------------------------
# purge and create a new database
drop database if exists va_usersdb;
create database va_usersdb;
use va_usersdb;

/*
+------------------+-----------------------+------+-----+---------------------+----------------+
| Field            | Type                  | Null | Key | Default             | Extra          |
+------------------+-----------------------+------+-----+---------------------+----------------+
| id               | bigint(20) unsigned   | NO   | PRI | NULL                | auto_increment |
| project_id       | mediumint(8) unsigned | NO   |     |                     |                |
| type             | tinyint(1)            | NO   |     |                     |                |
| parent_folder_id | bigint(20) unsigned   | NO   |     |                     |                |
| node_no          | tinyint(4)            | NO   |     |                     |                |
| name             | varchar(100)          | NO   |     |                     |                |
| saveas           | varchar(20)           | NO   |     | -                   |                |
| title            | varchar(250)          | NO   |     |                     |                |
| size             | varchar(100)          | NO   |     | 0                   |                |
| filetype         | varchar(15)           | NO   |     | File Folder         |                |
| created_on       | datetime              | NO   |     |                     |                |
| created_by       | varchar(150)          | NO   |     |                     |                |
| deleted_on       | datetime              | NO   |     | 0000-00-00 00:00:00 |                |
| deleted_by       | varchar(150)          | NO   |     | -                   |                |
| permissions      | smallint(6)           | NO   |     | 700                 |                |
| active           | tinyint(1)            | NO   |     | 1                   |                |
+------------------+-----------------------+------+-----+---------------------+----------------+
*/

CREATE TABLE `fg_files` (

  `id` bigint(20) unsigned NOT NULL auto_increment,
  `project_id` MEDIUMINT UNSIGNED NOT NULL,
  `type` bool not null,
  `parent_folder_id` bigint(20) unsigned not null,
  `node_no` tinyint not null,
  `name` varchar(100) not null,
  `saveas` varchar(20) not null default "-",
  `title` varchar(250) not null,
  `size` varchar(100) not null default "0",
  `filetype` varchar(15) not null default "File Folder",
  `created_on` datetime not null,
  `created_by` varchar(150) not null,
  `deleted_on` datetime not null default "0000-00-00 0000",
  `deleted_by` varchar(150) not null default "-",
  `permissions` smallint not null default 700,
  `active` bool not null default 1,  
  
  PRIMARY KEY  (id)
) ENGINE=InnoDB;


/*
+------------+--------------+------+-----+---------+-------+
| Field      | Type         | Null | Key | Default | Extra |
+------------+--------------+------+-----+---------+-------+
| session_id | varchar(30)  | NO   |     |         |       |
| tag        | varchar(25)  | NO   |     |         |       |
| value      | varchar(250) | NO   |     |         |       |
+------------+--------------+------+-----+---------+-------+
*/
CREATE TABLE `fg_sessions` (

	`session_id` varchar(30) NOT NULL,
	`tag` varchar(25) NOT NULL,
	`value` varchar(250) NOT NULL
		
) ENGINE=InnoDB;


/*
+-------------+-----------------------+------+-----+---------+-------+
| Field       | Type                  | Null | Key | Default | Extra |
+-------------+-----------------------+------+-----+---------+-------+
| session_id  | varchar(30)           | NO   |     |         |       |
| project_id  | mediumint(8) unsigned | NO   |     |         |       |
| uname       | varchar(50)           | NO   |     |         |       |
| login_from  | varchar(10)           | NO   |     |         |       |
| fg_files_id | bigint(20) unsigned   | NO   |     |         |       |
| action      | varchar(25)           | NO   |     |         |       |
| details     | varchar(250)          | NO   |     |         |       |
| extra       | varchar(100)          | NO   |     | -       |       |
| dtime       | datetime              | NO   |     |         |       |
+-------------+-----------------------+------+-----+---------+-------+
*/
CREATE TABLE `fg_activity_log` (

	`session_id` varchar(30) NOT NULL,
	`project_id` MEDIUMINT UNSIGNED NOT NULL,
	`uname` varchar(50) NOT NULL,	
	`login_from` varchar(10) NOT NULL,
	`fg_files_id` bigint(20) unsigned NOT NULL, 	
	`action` varchar(25) NOT NULL,
	`details` varchar(250) NOT NULL,
	`extra` varchar(100) NOT NULL default "-",
	`dtime` datetime NOT NULL
		
) ENGINE=InnoDB;


/*
+----------------+---------------------+------+-----+---------------------+-------+
| Field          | Type                | Null | Key | Default             | Extra |
+----------------+---------------------+------+-----+---------------------+-------+
| id             | varchar(30)         | NO   | PRI |                     |       |
| loginname      | varchar(50)         | NO   |     |                     |       |
| project_id     | bigint(20) unsigned | NO   |     |                     |       |
| projectname    | varchar(100)        | NO   |     |                     |       |
| jobcode        | varchar(50)         | NO   |     |                     |       |
| role_id        | tinyint(3) unsigned | NO   |     |                     |       |
| logintime      | datetime            | NO   |     |                     |       |
| logouttime     | datetime            | NO   |     | 0000-00-00 00:00:00 |       |
| preferences    | varchar(250)        | NO   |     | -                   |       |
| temp_user_data | varchar(250)        | NO   |     | -                   |       |
| active         | tinyint(1)          | YES  |     | NULL                |       |
+----------------+---------------------+------+-----+---------------------+-------+
*/
CREATE TABLE `sessions` (
  `id` varchar(30) NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `projectname` varchar(100) NOT NULL,
  `jobcode` varchar(50) NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL,
  `logintime` datetime NOT NULL,
  `logouttime` datetime NOT NULL default '0000-00-00 00:00:00',
  `preferences` varchar(250) NOT NULL default '-',
  `temp_user_data` varchar(250) NOT NULL default '-',
  `active` tinyint(1) default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;





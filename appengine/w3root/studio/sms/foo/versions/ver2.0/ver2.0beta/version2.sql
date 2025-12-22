/* Version 2.0 :: MySQL Database Updates */

# Create Database
create database `va_usersdb`;

# Create the Database users;
grant select on `va_usersdb`.* to `angels2k8`@`localhost` identified by "d4fSA4fsf48fuis" with grant option;
grant all on `va_usersdb`.* to `anu2k8`@`localhost` identified by "j39GJew83id93jf" with grant option;
flush privileges;

# Start using the db
use `va_usersdb`;

/* 
Date: 20080729 
Memo: user_profiles and user_projects are moved to the smsdb database

# Create Tables
CREATE TABLE user_profiles (
  
	
  	id INT UNSIGNED NOT NULL auto_increment,
  	
  	loginname varchar(50) NOT NULL, 
  	passwd varchar(150) NOT NULL default 'xyz',  
  	
  	fname varchar(50) NOT NULL,
  	lname varchar(50) NOT NULL default '-',
  	contact varchar(100) NOT NULL default '-',
  	email varchar(150) NOT NULL,  	
  	
  	organization varchar (150) NOT NULL default '-',
  	designation varchar (50) NOT NULL default '-',
  	address_door_no varchar(50) NOT NULL default '-',
  	address_street varchar(50) NOT NULL default '-',
  	address_locality varchar(50) NOT NULL default '-',
  	address_city varchar(50) NOT NULL default '-',
  	address_state varchar(50) NOT NULL default '-',
  	address_country varchar(50) NOT NULL default '-',
  	address_pincode varchar(50) NOT NULL default '-',
  	
  	mobile_no varchar(150) NOT NULL default '-',
  	phone_no varchar (150) NOT NULL default '-',
 
  	registered_on DATETIME NOT NULL,
  	dt date NOT NULL,
  	active bool NOT NULL default 1,    
  	PRIMARY KEY  (id),  
  	UNIQUE KEY loginname (loginname)
  	  
) TYPE=InnoDB;


CREATE TABLE user_projects (
	
	user_profiles_id INT UNSIGNED NOT NULL,
	project_id BIGINT UNSIGNED NOT NULL,
	role_id TINYINT UNSIGNED NOT NULL,
	role_assigned_by varchar(50) NOT NULL,
	role_assigned_on DATETIME NOT NULL,
	user_preference varchar(250) NOT NULL default '-',
	accept_terms_and_conditions bool not null default 0,
	active bool	not null default 1
	
) TYPE=InnoDB;
*/

CREATE TABLE sessions (

	/* Login history and user details */
  	id varchar(30) NOT NULL,  
  	loginname varchar(50) NOT NULL,
  	project_id BIGINT UNSIGNED NOT NULL,
  	projectname varchar(100) NOT NULL,
  	jobcode varchar(50) NOT NULL, 
  	role_id tinyint UNSIGNED NOT NULL,
  	logintime DATETIME NOT NULL,  
  	logouttime DATETIME NOT NULL default '0000-00-00 00:00:00',  
  	preferences varchar(250) NOT NULL default '-',  
  	temp_user_data varchar(250) NOT NULL default '-',
  	active bool,    
  	UNIQUE KEY id (id)
  	        
) TYPE=MyISAM;


CREATE TABLE user_x (

  user_profiles_id int(10) unsigned not null,
  project_id  MEDIUMINT UNSIGNED not null,
  `key` varchar(25) not null,
  `value` varchar(250) not null,  
  `global` bool not null default 0,
  
  FOREIGN KEY (user_profiles_id) REFERENCES user_profiles(id) on delete cascade 
  	  
) TYPE=InnoDB;


# Reset all dtime information
update dwglist set dtime = '0000-00-00';

# alter the datatype for dtime field
ALTER TABLE `vasmsdb`.`dwglist` MODIFY COLUMN `dtime` DATE NOT NULL;

/*
+-------------------------------------------------------+
| Tracking user's whims									|
+-------------------------------------------------------+
| use intval function to convert string to integer for 	|
| value field with numeric data.						|
+-------------------------------------------------------+
*/

CREATE TABLE user_x (

  user_id 		int(10) unsigned not null,
  project_id  	MEDIUMINT UNSIGNED not null,
  `key` 		varchar(25) not null,
  `value` 		varchar(250) not null,  
  `global` 		bool not null default 0,
  
  FOREIGN KEY (user_id) REFERENCES users(id) on delete cascade 
  	  
) TYPE=InnoDB;


/*
+-------------------------------------------------------+
| Tracking project specific whims						|
+-------------------------------------------------------+
| use intval function to convert string to integer for 	|
| value field with numeric data.						|
+-------------------------------------------------------+
*/

CREATE TABLE `project_setup` (

  `project_id` 	bigint unsigned not null,    
  `key` 		varchar(45) not null,
  `value` 		varchar(250) not null /*, 
  
   FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade */
  	  
) TYPE=InnoDB;


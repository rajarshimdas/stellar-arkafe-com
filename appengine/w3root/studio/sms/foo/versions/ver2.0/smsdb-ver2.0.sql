# Upgrade to version 2.0
#----------------------------------------
# smsdb
#----------------------------------------
# purge
use vasmsdb;
drop table if exists view_drawing_list;

drop table if exists `user_x`;
drop table if exists `user_projects`;
drop table if exists `user_profiles`;


/* Transname */
ALTER TABLE `transname` ADD `user_profiles_id` int(10) unsigned NOT NULL AFTER `passwd`;

/* user_profiles
+------------------+------------------+------+-----+---------+----------------+
| Field            | Type             | Null | Key | Default | Extra          |
+------------------+------------------+------+-----+---------+----------------+
| id               | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| loginname        | varchar(50)      | NO   | UNI |         |                |
| passwd           | varchar(150)     | NO   |     | xyz     |                |
| fname            | varchar(50)      | NO   |     |         |                |
| lname            | varchar(50)      | NO   |     | -       |                |
| email            | varchar(150)     | NO   |     |         |                |
| organization     | varchar(150)     | NO   |     | -       |                |
| designation      | varchar(50)      | NO   |     | -       |                |
| address_door_no  | varchar(50)      | NO   |     | -       |                |
| address_street   | varchar(50)      | NO   |     | -       |                |
| address_locality | varchar(50)      | NO   |     | -       |                |
| address_city     | varchar(50)      | NO   |     | -       |                |
| address_state    | varchar(50)      | NO   |     | -       |                |
| address_country  | varchar(50)      | NO   |     | -       |                |
| address_pincode  | varchar(50)      | NO   |     | -       |                |
| mobile_no        | varchar(150)     | NO   |     | -       |                |
| phone_no         | varchar(150)     | NO   |     | -       |                |
| registered_on    | datetime         | NO   |     |         |                |
| dt               | date             | NO   |     |         |                |
| active           | tinyint(1)       | NO   |     | 1       |                |
+------------------+------------------+------+-----+---------+----------------+
*/ 
CREATE TABLE `user_profiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `loginname` varchar(50) NOT NULL,
  `passwd` varchar(150) NOT NULL default 'xyz',
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL default '-',
  `email` varchar(150) NOT NULL,
  `organization` varchar(150) NOT NULL default '-',
  `designation` varchar(50) NOT NULL default '-',
  `address_door_no` varchar(50) NOT NULL default '-',
  `address_street` varchar(50) NOT NULL default '-',
  `address_locality` varchar(50) NOT NULL default '-',
  `address_city` varchar(50) NOT NULL default '-',
  `address_state` varchar(50) NOT NULL default '-',
  `address_country` varchar(50) NOT NULL default '-',
  `address_pincode` varchar(50) NOT NULL default '-',
  `mobile_no` varchar(150) NOT NULL default '-',
  `phone_no` varchar(150) NOT NULL default '-',
  `registered_on` datetime NOT NULL,
  `dt` date NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `loginname` (`loginname`)
) ENGINE=InnoDB;



/* user_projects
+-----------------------------+---------------------+------+-----+---------+-------+
| Field                       | Type                | Null | Key | Default | Extra |
+-----------------------------+---------------------+------+-----+---------+-------+
| user_profiles_id            | int(10) unsigned    | NO   |     |         |       |
| project_id                  | bigint(20) unsigned | NO   |     |         |       |
| role_id                     | tinyint(3) unsigned | NO   |     |         |       |
| role_assigned_by            | varchar(50)         | NO   |     |         |       |
| role_assigned_on            | datetime            | NO   |     |         |       |
| user_preference             | varchar(250)        | NO   |     | -       |       |
| accept_terms_and_conditions | tinyint(1)          | NO   |     | 0       |       |
| active                      | tinyint(1)          | NO   |     | 1       |       |
+-----------------------------+---------------------+------+-----+---------+-------+
*/

CREATE TABLE `user_projects` (
  `user_profiles_id` int(10) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL,
  `role_assigned_by` varchar(50) NOT NULL,
  `role_assigned_on` datetime NOT NULL,
  `user_preference` varchar(250) NOT NULL default '-',
  `accept_terms_and_conditions` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1',
  CONSTRAINT `user_projects_ibfk_1` FOREIGN KEY (`user_profiles_id`) REFERENCES `user_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;


/*
+------------+-----------------------+------+-----+---------+-------+
| Field      | Type                  | Null | Key | Default | Extra |
+------------+-----------------------+------+-----+---------+-------+
| user_id    | int(10) unsigned      | NO   | MUL |         |       |
| project_id | mediumint(8) unsigned | NO   |     |         |       |
| key        | varchar(25)           | NO   |     |         |       |
| value      | varchar(250)          | NO   |     |         |       |
| global     | tinyint(1)            | NO   |     | 0       |       |
+------------+-----------------------+------+-----+---------+-------+
*/

CREATE TABLE `user_x` (
  `user_profiles_id` int(10) unsigned NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL,
  `key` varchar(25) NOT NULL,
  `value` varchar(250) NOT NULL,
  `global` tinyint(1) NOT NULL default '0',
  KEY `user_profiles_id` (`user_profiles_id`),
  CONSTRAINT `user_x_ibfk_1` FOREIGN KEY (`user_profiles_id`) REFERENCES `user_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

/*
+-----------------------------------------------------------------------+
| view_drawing_list							|
+-----------------------------------------------------------------------+
*/
drop view if exists view_drawing_list;
create view view_drawing_list as
 
select 	dwglist.id,
	dwglist.project_id,
	
	dwglist.dwgidentity,
	dwglist.disciplinecode,		 
	concat(dwglist.dwgidentity,'-',dwglist.disciplinecode,'-',dwglist.unit,dwglist.part) as sheetno,
	dwglist.currentrevno as revno, 
	dwglist.title, 
	
	dwglist.newstage as stage,		
	dwglist.r0issuedt as anewtargetdt,
	dwglist.stageclosed as aissuedflag,
	
	/* Tracking commited Target dates */
	dwglist.dtime as commitdt,
	
	dwglist.r0targetdt,
	dwglist.newr0targetdt,
	dwglist.r0issuedflag,
	
	dwglist.lastissuedrevno,
	dwglist.lastissueddate,
	
	dwglist.priority as actionby,
	dwglist.remark
		
from dwglist
 
where active = 1;

/*
+-----------------------------------------------------------------------+
| view_users								|
+-----------------------------------------------------------------------+
*/
drop view if exists view_users;
create view view_users as 

select  user_profiles.id,
	user_profiles.loginname,
	user_profiles.passwd,		
	user_profiles.fname,
	user_profiles.lname,
	user_profiles.email,
		
	transadd.company as organization,
		
	user_profiles.designation,
	user_profiles.mobile_no as mobile,
	user_profiles.phone_no as phone,
	
	user_projects.project_id,
	user_projects.role_id,
	user_projects.user_preference as preferences,
	user_projects.accept_terms_and_conditions as ok,
	user_projects.active
		
from user_profiles, user_projects, transname, transadd

where 
	
	(user_profiles.id = user_projects.user_profiles_id) 

and 	(user_profiles.id = transname.user_profiles_id)

and 	(transname.transadd_id = transadd.id)

and	user_profiles.active = 1;

/*
+-----------------------------------------------------------------------+
| view_contacts								|
+-----------------------------------------------------------------------+
*/
drop view if exists view_contacts;
create view view_contacts as 

select  t1.id,
	t1.contact,
				
	t3.roles,

	t2.company,
		
	t1.phoneno,
	t1.email,
	t1.project_id	

from transname as t1, transadd as t2, roles as t3

where 
	
	(t1.transadd_id = t2.id)
	
and (t1.role_id = t3.id)

and t1.active = 1;

/*
+-----------------------------------------------------------------------+
| view_login								|
+-----------------------------------------------------------------------+
*/
drop view if exists view_login;
create view view_login as select project_id, projectname, loginname, logintime from sessi0ns order by logintime;
	


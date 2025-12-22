# Date: 29-Jul-2008


ALTER TABLE `transname` ADD `user_profiles_id` int(10) unsigned NOT NULL AFTER `passwd`;


CREATE TABLE `user_projects` (

  `user_profiles_id` int(10) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL,
  `role_assigned_by` varchar(50) NOT NULL,
  `role_assigned_on` datetime NOT NULL,
  `user_preference` varchar(250) NOT NULL default '-',
  `accept_terms_and_conditions` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '1'
  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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

) ENGINE=InnoDB DEFAULT CHARSET=latin1;


# view_users
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

and (user_profiles.id = transname.user_profiles_id)

and (transname.transadd_id = transadd.id)

and
	user_profiles.active = 1;

	
	
	
# view_contacts
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


-------------------------------------------------------------------------------
-- Rajarshi Das 															
-------------------------------------------------------------------------------
-- VA :: Studio Management System: 15th Sept, 2008						 
-------------------------------------------------------------------------------
-- RFIs
-------------------------------------------------------------------------------

DROP DATABASE IF EXISTS va_rfidb;
CREATE DATABASE va_rfidb;

grant all on `va_rfidb`.* to `rfidb_angelanu`@`localhost` identified by "dfdsfde243driwenx" with grant option;
grant select on `va_rfidb`.* to `rfidb_angels`@`localhost` identified by "jdfuia32uew8327" with grant option;
flush privileges;

use va_rfidb;


CREATE TABLE rfi (

  id BIGINT /* 18,446,744,073,709,551,615 - 8 byte */ UNSIGNED NOT NULL auto_increment,    
  project_id MEDIUMINT UNSIGNED NOT NULL,
  rfi_no int unsigned not null,  
  loginname varchar(50) not null,
  roleid tinyint unsigned not null,
  no_of_items smallint not null, 
  no_of_items_closed smallint not null default 0,
  rfi_closed bool not null default 0,  
  dtime datetime NOT NULL,
  active bool NOT NULL default 1, 
     
  PRIMARY KEY  (id)  
  
) TYPE=InnoDB;


CREATE TABLE rfi_question (

	rfi_id bigint unsigned not null,
	srno smallint not null,
	topic varchar(250) not null, 					/* short description of the issue 	*/
	question TEXT not null,							/* detailed question				*/
	attachment varchar(100) not null default '-',	/* not used for now					*/
	no_of_response smallint not null default 0,
	required_by DATE not null,
	remark varchar(250) not null default '-',
	closed bool not null default 0,
	active bool not null default 1
	
	/* FOREIGN KEY (rfi_id) REFERENCES rfi(id) on delete cascade */

) TYPE=InnoDB;


CREATE TABLE rfi_discipline (
	
	rfi_id bigint unsigned not null,
	rfi_question_srno smallint not null,
	discipline_id tinyint unsigned not null
	
	/* FOREIGN KEY (rfi_id) REFERENCES rfi(id) on delete cascade */
	
) TYPE=InnoDB;


CREATE TABLE rfi_response (
	
	rfi_id bigint unsigned not null,
	rfi_question_srno smallint not null,
	response_no smallint not null,
	response TEXT not null,
	attactment varchar(100) not null,				/* not used for now					*/	
	response_by varchar(50) not null,
	response_from_login varchar(25) not null,
	dtime datetime not null,
	active bool not null default 1
	
	/* FOREIGN KEY (rfi_id) REFERENCES rfi(id) on delete cascade */

) TYPE=InnoDB;


CREATE TABLE rfi_attachment (

	rfi_id bigint unsigned not null,
	rfi_question_srno smallint not null,
	attachment varchar(5) not null, 		/* Question - Q and Response - R */
	original_name varchar(100) not null,
	saved_as varchar(100) not null,
	file_extension varchar(5) not null,
	active bool not null default 1
	
	/* FOREIGN KEY (rfi_id) REFERENCES rfi(id) on delete cascade */
	
) TYPE=InnoDB;


CREATE TABLE mail (

	project_id MEDIUMINT UNSIGNED NOT NULL,
	discipline_id tinyint unsigned not null,
	cn_name varchar(100) not null,
	cn_emailid varchar(100) not null,
	cn_send bool not null
	
) TYPE=InnoDB;


CREATE TABLE query_log (

	project_id MEDIUMINT UNSIGNED NOT NULL,
	session_id varchar(32) not null,
	login_from varchar(20) not null,
	query text not null,
	dtime datetime not null

) TYPE=InnoDB;


CREATE TABLE setup (
	
	project_id MEDIUMINT UNSIGNED NOT NULL,	
	tag varchar(25) not null,
	x varchar(250) not null 		
		
) TYPE=InnoDB;


-- Temporay Data Tables --
CREATE TABLE temp_create (

	id BIGINT /* 18,446,744,073,709,551,615 - 8 byte */ UNSIGNED NOT NULL auto_increment,
	sessionid varchar(32) not null,
	no_of_items smallint not null,
	dtime datetime not null, 
	active bool not null,
	
	PRIMARY KEY  (id)

) TYPE=InnoDB;


CREATE TABLE temp_question (

	temp_create_id bigint unsigned not null,
	srno smallint not null,
	discipline_id varchar(100) not null, /* [ Syntax :: D:1:4:5 ] */
	topic varchar(250) not null,
	question TEXT not null,
	attachment varchar(100) not null,	
	required_by DATE not null,
	remark varchar(250) not null,	
	active bool not null,

	FOREIGN KEY (temp_create_id) REFERENCES temp_create(id) on delete cascade
	
) TYPE=InnoDB;



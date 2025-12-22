CREATE TABLE users (
  /* Internet Login Validation */ 
  id int unsigned auto_increment,
  /* id needed in case a userpreference table is to be made later */    
  loginname varchar(50) NOT NULL,  
  passwd varchar(50) NOT NULL default '',
  fullname varchar(50) NOT NULL,
  emailid varchar(150) not null,
  internaluser bool,
  remark varchar(250) not null,
  active bool,  
  PRIMARY KEY  (id), 
  UNIQUE KEY loginname (loginname)        
) TYPE=MyISAM;
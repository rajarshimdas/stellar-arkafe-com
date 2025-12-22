CREATE TABLE rfi (

  id BIGINT /* 18,446,744,073,709,551,615 - 8 byte */ UNSIGNED NOT NULL auto_increment,

  user_id int unsigned not null,  
  project_id MEDIUMINT UNSIGNED NOT NULL,
  task_id tinyint unsigned not null,

  no_of_hours tinyint not null,
  no_of_min tinyint not null,

  work text not null,
  
  dtime timestamp not null default CURRENT_TIMESTAMP,
  approved bool not null default 1,

  quality tinyint not null,
   
     
  PRIMARY KEY  (id)  
  
) TYPE=InnoDB;



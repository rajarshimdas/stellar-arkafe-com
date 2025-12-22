/* New table 23-May-2007 */
CREATE TABLE `transname` (
  `id` bigint unsigned auto_increment,
  `contact` varchar(100) NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL,
  `transadd_id` int(10) unsigned NOT NULL,
  `phoneno` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `extranetlogin` bool not null,
  `passwd` varchar(100) not null,
  `active` tinyint(1) NOT NULL,
  primary key(id)
) ENGINE=MyISAM;


/* Insert modified data from old transname */
INSERT INTO `transname` VALUES 
 (NULL,'Minaz',240,36,5,'9999999','minaz@vagroup.com',0,'minaz123',1);
 
 
 
CREATE TABLE transmittals (
  /* Global Transmittal List */
  id bigint UNSIGNED /* 18,446,744,073,709,551,615 - 8 bytes */ NOT NULL auto_increment,  
  project_id MEDIUMINT UNSIGNED not null,
  transno smallint unsigned /* 0 to 65535 - 2 bytes */ not null,  
  contact varchar(100) not null,
  company varchar(100) not null,
  address varchar(150) not null,
  sentmode varchar(20) not null,
  purpose varchar(30) not null, 
  dt date NOT NULL,  
  remark varchar(250) NOT NULL,
  loginname varchar(50) NOT NULL,
  active bool not null,    
  PRIMARY KEY (id),  
  FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade       
) TYPE=InnoDB;


CREATE TABLE translist (
  /* Items package details in the Transmittals */ 
  transmittal_id bigint UNSIGNED NOT NULL,
  srno tinyint unsigned not null,
  /* numeric codes for items like drawings, cds, tender docs etc */
  itemcode tinyint unsigned not null,
  item varchar(150)/* sheetno::X8-X-8888X-XX */ NOT NULL default '',  
  nos tinyint UNSIGNED not null,  
  description varchar(150) NOT NULL default '',
  FOREIGN KEY (transmittal_id) REFERENCES transmittals(id) on delete cascade          
) TYPE=InnoDB;


INSERT INTO transmittals VALUES 
	(NULL,1,1,'contactname','company','address','sentmode','0-0-0-0-0-0','0000-00-00', 'noremark', 'nobody',1);

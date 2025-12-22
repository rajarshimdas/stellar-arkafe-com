-------------------------------------------------------------------------------
-- Rajarshi Das 															
-------------------------------------------------------------------------------
-- VA :: Studio Management System: 11th January, 2007						 
-------------------------------------------------------------------------------
-- Create tables for vadwgdb database. 
-------------------------------------------------------------------------------
-- Major update	: 1:27 PM 06-Feb-07  (using InnoDB instead of default MyISAM)
--				: 11:19 AM 16-Mar-07 (major restructuring of tables)
--				: 11:52 AM 22-Mar-07 (Schematic stage dwglist tracking added)
--				: 10:21 AM 30-Mar-07 (Added dwglistsnapshot table) 
-- Last updated	: 10:21 AM 30-Mar-07 										
-------------------------------------------------------------------------------

CREATE TABLE projects (
  /* Global Project list */
  id MEDIUMINT UNSIGNED/* 16,777,215 - 3 byte */ NOT NULL auto_increment,    
  projectname varchar(100) NOT NULL default '',
  jobcode varchar(50) NOT NULL,
  teamleader varchar(100) NOT NULL,
  designmanager varchar(100) NOT NULL,  
  currentdwglistver varchar(15) NOT NULL default 'A',
  dt date NOT NULL,
  active bool NOT NULL,    
  PRIMARY KEY  (id),  
  UNIQUE KEY jobcode (jobcode)  
) TYPE=InnoDB;


CREATE TABLE roleinproject (
  /* Project team members and their roles */      
  project_id MEDIUMINT UNSIGNED NOT NULL,    
  loginname varchar(50) NOT NULL default '',
  roles_id tinyint UNSIGNED NOT NULL,
  active bool not null        
) TYPE=MyISAM;

  
CREATE TABLE roles (
  /* Standard roles */
  id tinyint UNSIGNED /* 255 - 1 byte */ NOT NULL,      
  roles varchar(100) NOT NULL default '',  
  PRIMARY KEY (id),  
  UNIQUE KEY roles (roles) 
) TYPE=MyISAM;


CREATE TABLE daemons (
  /* daemon users with full access rights */
  name varchar(100) not null,
  role_id tinyint UNSIGNED not null,
  UNIQUE KEY name (name)
) TYPE=MyISAM;
  

CREATE TABLE discipline (
  /* Standard Discipline codes */
  id tinyint UNSIGNED /* 255 - 1 byte */ NOT NULL,  
  disciplinecode varchar(5) NOT NULL default '',
  discipline varchar(100) NOT NULL default '',
  UNIQUE KEY disciplinecode (disciplinecode)        
) TYPE=MyISAM;


CREATE TABLE blocks (
   project_id MEDIUMINT UNSIGNED NOT NULL,
   blockno varchar(6) NOT NULL default '',
   blockname varchar(150) NOT NULL default '',   
   active bool not null  
) TYPE=MyISAM;


CREATE TABLE schedule (
  /* Schematic Stages - Schedule 					*//*
  For every edit/change set the current active to 0 and 
  add a new row with revised information with active 1 */
  id int UNSIGNED /* 4,294,967,295 - 4 byte */ not null auto_increment, 
  project_id MEDIUMINT UNSIGNED NOT NULL,
  hotstage tinyint UNSIGNED not null default 1,
  stg1dt date not null default '0000-00-00',
  stg1newdt date not null default '0000-00-00',
  stg1active bool not null default 1,
  stg2dt date not null default '0000-00-00',
  stg2newdt date not null default '0000-00-00',
  stg2active bool not null default 1,
  stg3dt date not null default '0000-00-00',
  stg3newdt date not null default '0000-00-00',
  stg3active bool not null default 1,
  stg4dt date not null default '0000-00-00',
  stg4newdt date not null default '0000-00-00',
  stg4active bool not null default 1,
  loginname varchar(50) not null,
  /* Remark field can be used for reasons or delay or any other info */
  remark varchar(255) not null,
  dtime datetime not null,
  active bool not null,
  PRIMARY KEY (id)
) TYPE=InnoDB;

-------------------------------------------------------------------------------
-- Drawing Lists and drwing revisions no tracking tables
-------------------------------------------------------------------------------
CREATE TABLE dwglist (
  /* Global Drawing List */
  id BIGINT UNSIGNED /* 18,446,744,073,709,551,615 - 8 byte */ NOT NULL auto_increment,
  /* Project Info */ 
  project_id MEDIUMINT UNSIGNED NOT NULL,
  /* Drawing specific info */
  dwgidentity varchar(6) NOT NULL default '',
  disciplinecode varchar(4) NOT NULL,
  unit varchar(6) NOT NULL,
  part varchar(2) NOT NULL,
  currentrevno varchar(5) not null default 'A',
  title varchar(150) NOT NULL,
  scaleina1 /* not used */ varchar(100) not null,
  remark varchar(150) NOT NULL,
  priority varchar(20) not null,
  /* Schematic stage tracking */
  stage tinyint UNSIGNED not null,
  newstage tinyint UNSIGNED not null,
  stageclosed bool not null default 0,
  /* R0 Target date */
  r0targetdt date NOT NULL,
  newr0targetdt date NOT NULL,  
  r0issuedflag bool not null default 0,
  r0issuedt /* not used */ date not null,
  /* Latest Revision release date */
  lastissuedrevno varchar(5) not null,
  lastissueddate date not null,   
  /* Date time of drawing entity creation and excel import timestamp */  
  dtime DATETIME NOT NULL,  
  /* On Delete set active to 0 */
  active bool not null,     
  PRIMARY KEY (id),  
  FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade  
) TYPE=InnoDB;


/* dwghistory table: 11:29 AM 16-Mar-07 (Sonu's Birthday) */
CREATE TABLE dwghistory (
  /* Individual drawing history */
  dwglist_id BIGINT UNSIGNED NOT NULL,
  /* New Revision number */
  newrevno bool not null,
  revno varchar(5) not null,
  /* Snapshots - Store old ListVer snapshot & Named snapshot*/  
  newdwglistver bool not null,  
  olddwglistver varchar(100) NOT NULL,
  title varchar(150) NOT NULL,
  /* For Transmittals remark syntax: 'ProjID:$projectidTmNo:$transid' */   
  remark varchar(250) NOT NULL,
  scaleina1 varchar(15) not null,
  lastissuedrevno varchar(5) not null,
  lastissueddt date not null,
  /* Schematic stage tracking */
  newstg bool not null,
  newstage tinyint UNSIGNED not null,
  newstgreason varchar(250) not null,
  /* R0 Target date tracking */ 
  newr0dt bool not null,
  /* r0newdt exception: 
  While creating a new listversion, r0newdt if filled with r0targetdt
  see listver-action.cgi for more details. This helps in recreation of
  old list version where we need to display the original target date.
  The details of revised R0 target date and whether it is issued is
  tracked in the r0reason fiels using the following syntax:
  r0reason = issuedflag+issueddt
  */  
  r0newdt date not null,
  r0reason varchar(250) not null,
  /* Maintainance related data */
  loginname varchar(50) not null,
  dtime DATETIME NOT NULL,
  active bool not null,
  FOREIGN KEY (dwglist_id) REFERENCES dwglist(id) on delete cascade   
) TYPE=InnoDB;


CREATE TABLE snapshots (
  /* Drawing List Version & named snapshots */
  id int UNSIGNED /* 4,294,967,295 - 4 byte */ not null auto_increment,
  namedsnapshot bool not null,
  name varchar(100) not null,
  nextdwglistver varchar(5) NOT NULL default 'A',
  dwglistver varchar(5) NOT NULL,
  hotstage tinyint UNSIGNED not null,     
  project_id MEDIUMINT UNSIGNED NOT NULL,    
  loginname varchar(50) NOT NULL default '',
  dtime DATETIME NOT NULL , 
  PRIMARY KEY (id), 
  FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade       
) TYPE=InnoDB;


CREATE TABLE snapinfo (
  /* for recreating old dwglistver or named snapshots */
  snapshot_id int UNSIGNED not null,
  project_id MEDIUMINT UNSIGNED NOT NULL,
  blockno varchar(6) NOT NULL default '',
  blockname varchar(150) NOT NULL default '',
  sysnote varchar(250) not null default '',  
  FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade       
) TYPE=InnoDB;


CREATE TABLE sketches (
  /* Global RFI List */
  id BIGINT UNSIGNED /* 18,446,744,073,709,551,615 - 8 byte */ NOT NULL auto_increment,
  project_id MEDIUMINT UNSIGNED NOT NULL,
  sketchno MEDIUMINT UNSIGNED NOT NULL,  
  title varchar(150) not null,
  remark varchar(250) not null,
  contact varchar(100) not null,
  company varchar(100) not null,
  address varchar(150) not null,  
  sentmode varchar(20) not null,
  dt date not null,
  loginname varchar(50) not null,
  active bool not null,
  PRIMARY KEY (id)
) TYPE=MyISAM;

 
-------------------------------------------------------------------------------
-- Transmittals
-------------------------------------------------------------------------------
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


CREATE TABLE tmheader (
  /* Caching temporary data while creating transmittals */
  id bigint unsigned not null auto_increment,
  sessionid varchar(30) not null,
  contact varchar(100) not null,
  sentmode varchar(20) not null,
  purpose varchar(30) not null,
  remark varchar(250) NOT NULL,
  dtime datetime not null,
  /* Maintainance: delete from tmheader where active = 0; */
  active bool not null,
  PRIMARY KEY (id)
) TYPE=InnoDB;


CREATE TABLE tmlist (
  /* Caching temporary data while creating transmittals */
  tmheader_id bigint unsigned not null,
  srno tinyint unsigned not null,
  itemcode tinyint unsigned not null,
  item varchar(150)NOT NULL default '',  
  nos tinyint UNSIGNED not null,
  description varchar(200) not null,
  active bool not null,  
  FOREIGN KEY (tmheader_id) REFERENCES tmheader(id) on delete cascade
) TYPE=InnoDB;


CREATE TABLE transadd (
  /* Transmittal addresses - Project owned for user ease */ 
  id int UNSIGNED /* 4,294,967,295 - 4 byte */ NOT NULL auto_increment,
  project_id MEDIUMINT UNSIGNED NOT NULL,    
  company varchar(100) not null default '',
  dooradd varchar(75) not null default '',
  streetadd varchar(75) not null default '',
  locality varchar(75) not null default '',
  city varchar(75) not null default '',
  statecountry varchar(75) not null default '',
  pincode varchar(75) not null default '',  
  website varchar(150) not null default '',
  /* Maintainance: delete from transadd where active = 0; */
  active bool not null,  
  PRIMARY KEY (id)       
) TYPE=MyISAM;


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


create table transitems (
  id tinyint unsigned not null,
  item varchar(50) not null
)TYPE=MyISAM; 


-------------------------------------------------------------------------------
-- RFI tracking. Using fast, non-transactional MyISAM storage engine
-------------------------------------------------------------------------------
CREATE TABLE rfi (
  /* Global RFI List */
  project_id MEDIUMINT UNSIGNED NOT NULL,
  disciplinecode varchar(5) NOT NULL default '',
  sender varchar(50) not null,
  role_id tinyint UNSIGNED,
  question text not null,
  answer text not null,
  requestdtime datetime not null,
  requiredby date not null,
  actionby varchar(50) not null,
  closingcomment text not null,
  priority_id tinyint UNSIGNED not null,
  active bool not null
) TYPE=MyISAM;


-------------------------------------------------------------------------------
-- System Usage authentication and tracking
-------------------------------------------------------------------------------
CREATE TABLE sessi0ns (
  /* Login history and user details */
  sessionid varchar(30) NOT NULL default '',  
  loginname varchar(50) NOT NULL,
  project_id MEDIUMINT UNSIGNED NOT NULL,
  projectname varchar(100) NOT NULL default '',
  jobcode varchar(50) NOT NULL, 
  role_id tinyint UNSIGNED NOT NULL,
  logintime DATETIME NOT NULL,  
  logouttime DATETIME NOT NULL,  
  intranet bool,
  usercode varchar(200) not null,  
  active bool,    
  UNIQUE KEY sessionid (sessionid)        
) TYPE=MyISAM;


CREATE TABLE excel2db ( 
  /* will track list updoads from excel files */
  project_id bigint UNSIGNED not null,   
  filename varchar(100) NOT NULL,
  originalfilename varchar(100) NOT NULL,
  noofdwgimported /* 255 */ tinyINT UNSIGNED not null,
  loginname varchar(50) not null,  
  importstamp datetime NOT NULL
  /*FOREIGN KEY (project_id) REFERENCES projects(id) on delete cascade*/   
) TYPE=InnoDB;


CREATE TABLE haystack (
  f1integer /* 65535 */ smallint UNSIGNED NOT NULL,
  f2string varchar(4) not null,
  f3blocks varchar(5) NOT NULL,
  f4gfc varchar (5) NOT NULL  
) TYPE=MyISAM;

 
-------------------------------------------------------------------------------
-- Set default system user grants 'HARD CODED'.
-------------------------------------------------------------------------------
GRANT insert, select, update 
on vasmsdb.* 
to 'mycutedaemon'@'localhost' 
identified by 'is+23+naughty+03+Arnav+2004' 
with grant option;
-------------------------------------------------------------------------------
-- Superuser -> With all privileges is not hard coded.  
-- In case of such an authentication need system will ask for superuser name 
-- and password. Superuser MEMO
-- Superuser:   My Pet dog's Name
-- Password:    City of birth + Full birthdate + Breed
-------------------------------------------------------------------------------


-------------------------------------------------------------------------------
/*  Default Data                                                             */
-------------------------------------------------------------------------------
INSERT INTO daemons VALUES  
  ('rajarshi', 1),
  ('naresh', 10),
  ('jagan', 10),
  ('ravee', 20);
  
  
INSERT INTO roles VALUES   
  (30,  'Team Leader'),
  (35,  'Design Manager'),
  (40,  'Design Manager - Jr.'),
  (50,  'Architect'),  
  (60,  'Studio Member'),
  (70,  'QS Team'),
  (100, 'Member VA'),
  (205, 'Consultant'),
  (210, 'Client'),
  (220, 'PMC'),
  (230, 'Vendor'),
  (240, 'Others');
  
  
INSERT INTO discipline VALUES
  (10, 	'A', 	'Architectural'),
  (20, 	'N', 	'Interiors'),
  (30, 	'S', 	'Structural'),
  (40, 	'E', 	'Electrical'),
  (50, 	'H', 	'HVAC - Heating Ventilation and Air Conditioning'),  
  (60, 	'P', 	'PHE - Public Health Engineering'),
  (70, 	'B', 	'BMS - Building Management Systems'),  
  (80, 	'L', 	'Landscape'),
  (90, 	'SC', 	'Integrated Services'),
  (100, 'SY', 	'Survey'),
  (110, 'FS', 	'Fire Suppression'),
  (120, 'FD', 	'Fire Detection'),
  (130, 'NT', 	'Networking'),
  (140, 'K', 	'Kitchen'),
  (150, 'AV', 	'Audio Visuals'),
  (160, 'SS', 	'Security Systems'),
  (170, 'M', 	'Gas Piping and other Mechanical Systems'),
  (180, 'AS', 'Sanctions from Local Authorities'),
  (182, 'AF', 'Fire Authority Sanctions'),
  (184, 'AE', 'Other Sanctions');

INSERT INTO projects VALUES 
	(NULL, 'Projects Template', '00.00.00.00', 'teamleader', 'designmanager', 
	'A', '0000-00-00', 1);
	 

INSERT INTO transmittals VALUES 
	(NULL,1,1,'contactname','company','address','sentmode','0-0-0-0-0-0','0000-00-00', 'noremark', 'nobody',1);

insert into transitems values
	(10, 'Drawings'),
	(20, 'cds/dvds'),
	(30, 'Tender Doccuments'),
	(40, 'Brochures'),
	(50, 'Sample Board'),
	(60, 'Sketch Detail'),
	(250, 'others');
-------------------------------------------------------------------------------
-- Empty mysql database table size (4 April 2007): 304 KB
--------------------------------- END -----------------------------------------

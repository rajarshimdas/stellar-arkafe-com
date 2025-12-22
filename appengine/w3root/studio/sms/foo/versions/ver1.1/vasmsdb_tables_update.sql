drop table discipline;

CREATE TABLE discipline (

  /* Standard Discipline codes */
  id tinyint UNSIGNED /* 255 - 1 byte */ NOT NULL,  
  disciplinecode varchar(5) NOT NULL default '',
  discipline varchar(100) NOT NULL default '',
  catagory smallint not null default 0,
  
  UNIQUE KEY disciplinecode (disciplinecode)        
) TYPE=InnoDB;

INSERT INTO `discipline` (`id`,`disciplinecode`,`discipline`,`catagory`) VALUES 
 (10,'A','Architectural',5),
 (20,'N','Interiors',5),
 (30,'S','Structural',50),
 (40,'E','Electrical',50),
 (50,'H','HVAC - Heating Ventilation and Air Conditioning',50),
 (60,'P','PHE - Public Health Engineering',50),
 (70,'B','BMS - Building Management Systems',50),
 (80,'L','Landscape',50),
 (90,'SC','Integrated Services',25),
 (100,'SY','Survey',50),
 (110,'FS','Fire Suppression',50),
 (120,'FD','Fire Detection',50),
 (130,'NT','Networking',50),
 (140,'K','Kitchen',50),
 (150,'AV','Audio Visuals',50),
 (160,'SS','Security Systems',50),
 (170,'M','Gas Piping and other Mechanical Systems',50),
 (180,'AS','Sanctions from Local Authorities',25),
 (182,'AF','Fire Authority Sanctions',25),
 (184,'AE','Other Sanctions',25);
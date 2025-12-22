--------------------------------------------------------------------------
/* Reqest for Information 												*/
--------------------------------------------------------------------------
CREATE TABLE `r4info` (
  	`id` bigint(20) unsigned NOT NULL auto_increment,  	
  	`project_id` mediumint(8) unsigned NOT NULL,
  	`rfidate` datetime not null,  	
	`disciplinecode` varchar(4) NOT NULL,	
  	`requestedby` varchar(50) NOT NULL,
  	`responseby` varchar(50) not null,  	
  	`neededby` date not null,
  	`newresponsedt` date not null,
  	`closedflag` tinyint(1) NOT NULL,
  	`closeddate` datetime not null,
  	`closedby` varchar(50) not null,
  	`question` varchar(250) not null,
  	`qattachments` smallint not null,
  	`impactontime` tinyint(1) NOT NULL,
  	`impactoncost` tinyint(1) NOT NULL,  
  	`dtime` datetime NOT NULL,
  	`active` tinyint(1) NOT NULL,  	
  	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--------------------------------------------------------------------------
/* Response View tracking 												*/
--------------------------------------------------------------------------
CREATE TABLE `rfiseenby` (
  	`r4info_id` bigint(20) unsigned NOT NULL, 	 	
	`seenby` varchar(50) not null,
	`addedresponseno` tinyint unsigned not null,
	`seendate` datetime not null,
	`active` tinyint(1) NOT NULL,    	  	
  	FOREIGN KEY (`r4info_id`) REFERENCES `r4info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--------------------------------------------------------------------------
/* Responses 															*/
--------------------------------------------------------------------------
CREATE TABLE `rfiresponse` (
  	`r4info_id` bigint(20) unsigned NOT NULL,  	 
  	`responseno` tinyint unsigned not null,
  	`answer` varchar(250) not null,
  	`attachments` tinyint unsigned not null,  	
  	FOREIGN KEY (`r4info_id`) REFERENCES `r4info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--------------------------------------------------------------------------
/* Response Attachments 												*/
--------------------------------------------------------------------------
CREATE TABLE `rfiattachments` (
  	`r4info_id` bigint(20) unsigned NOT NULL,   	 
  	`responseno` tinyint unsigned not null,
  	`attachmentno` tinyint unsigned not null,  	
  	`uploadfilename` varchar(100) not null,
  	`savedfilename` /* ProjID+Unixtimestamp */ varchar(100) not null,  	
	`filedescription` varchar(250) not null,  	
  	`active` tinyint(1) NOT NULL,  	
  	FOREIGN KEY (`r4info_id`) REFERENCES `r4info` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




--------------------------------------------------------------------------
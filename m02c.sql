CREATE DATABASE  IF NOT EXISTS `s1db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `s1db`;
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.22-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: s1db
-- ------------------------------------------------------
-- Server version	10.6.22-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocks` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `blockno` varchar(30) NOT NULL DEFAULT '',
  `blockname` varchar(150) NOT NULL DEFAULT '',
  `phase` smallint(5) unsigned NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Packages (including blocks)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks`
--

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `branch` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `branchname` varchar(250) NOT NULL,
  `streetaddress` text NOT NULL,
  `city` varchar(250) NOT NULL,
  `country` varchar(250) NOT NULL,
  `pincode` varchar(250) NOT NULL,
  `satmode` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT '0 is off, 1 is Working 1,3,5th Saturdays',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Company branch offices';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'- NA -','-','-','-','-',1,0),(11,'Bangalore','-','Bangalore','India','560 001',1,0),(12,'Bangkok','-','Bangkok','Thailand','-',0,0),(13,'Chennai','-','Chennai','India','600 006',1,0),(14,'Delhi','-','Delhi','India','110 020',1,0),(15,'Hyderabad','-','Hyderabad','India','-',1,0),(16,'Kolkatta','-','Kolkatta','India','700 107',1,0),(17,'Mumbai','-','Mumbai','India','400 020',1,1),(18,'Pune','-','Pune','India','411 016',1,1),(19,'Singapore','-','Singapore','Singapore','247971',0,0),(20,'Australia','-','Sydney','Austrailia','NSW 2000',0,0),(21,'Vietnam','-','Vietnam','Vietnam','-',0,0);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `csv_timesheets`
--

DROP TABLE IF EXISTS `csv_timesheets`;
/*!50001 DROP VIEW IF EXISTS `csv_timesheets`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `csv_timesheets` AS SELECT
 1 AS `tsid`,
  1 AS `dt`,
  1 AS `month`,
  1 AS `date`,
  1 AS `fullname`,
  1 AS `projectname`,
  1 AS `scope`,
  1 AS `milestone`,
  1 AS `work`,
  1 AS `hours`,
  1 AS `minutes`,
  1 AS `percent`,
  1 AS `worked_from` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `daemons`
--

DROP TABLE IF EXISTS `daemons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `daemons` (
  `name` varchar(100) NOT NULL COMMENT 'loginname. This user''s role_id will be overridden by the value here.  ',
  `role_id` tinyint(3) unsigned NOT NULL COMMENT 'The role_id here will override all other role_id info',
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Give specified users supercow powers';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daemons`
--

LOCK TABLES `daemons` WRITE;
/*!40000 ALTER TABLE `daemons` DISABLE KEYS */;
INSERT INTO `daemons` VALUES ('Ankit.Agrawal',1),('stellar',1);
/*!40000 ALTER TABLE `daemons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Workgroups and Organizational departments';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'NA',0,0),(2,'Business Development',100,1),(3,'Design Studio',200,1),(4,'Project Management',300,1),(5,'Quantity Survey',400,1);
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discipline`
--

DROP TABLE IF EXISTS `discipline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `discipline` (
  `id` tinyint(3) unsigned NOT NULL,
  `disciplinecode` varchar(5) NOT NULL DEFAULT '',
  `discipline` varchar(100) NOT NULL DEFAULT '' COMMENT 'Trades',
  `catagory` smallint(6) NOT NULL DEFAULT 0,
  `displayorder` smallint(6) NOT NULL DEFAULT 500,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disciplinecode` (`disciplinecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Professional discipline and craft trades';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discipline`
--

LOCK TABLES `discipline` WRITE;
/*!40000 ALTER TABLE `discipline` DISABLE KEYS */;
INSERT INTO `discipline` VALUES (1,'NA','NA',100,100,0),(10,'MP','Masterplan',5,10,1),(20,'AD','Architecture',5,20,1),(30,'ID','Interior Design',5,30,1),(40,'LD','Landscape',5,30,1);
/*!40000 ALTER TABLE `discipline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domain`
--

DROP TABLE IF EXISTS `domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `domain` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `domainname` varchar(250) NOT NULL,
  `corporatename` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL COMMENT 'Used for company address in Transmittal',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `domain_domainname` (`domainname`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='FQDN';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domain`
--

LOCK TABLES `domain` WRITE;
/*!40000 ALTER TABLE `domain` DISABLE KEYS */;
INSERT INTO `domain` VALUES (1,'domain.tld','NA','NA',0),(2,'stellar.arkafe.com','Stellar design studio','OFFICE NO. 102, PULIN CHSL, SEAWOODS WEST, SECTOR 40, NERUL, NAVI MUMBAI, MAHARASHTRA 400706. Mob. 096533 78347',1);
/*!40000 ALTER TABLE `domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dwghistory`
--

DROP TABLE IF EXISTS `dwghistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dwghistory` (
  `dwglist_id` bigint(20) unsigned NOT NULL,
  `newrevno` tinyint(1) NOT NULL DEFAULT 0,
  `revno` varchar(5) NOT NULL DEFAULT '-',
  `newdwglistver` tinyint(1) NOT NULL DEFAULT 0,
  `olddwglistver` varchar(100) NOT NULL DEFAULT '-',
  `title` varchar(150) NOT NULL DEFAULT '-',
  `remark` varchar(250) NOT NULL DEFAULT '-',
  `scaleina1` varchar(100) NOT NULL DEFAULT '-',
  `lastissuedrevno` varchar(5) NOT NULL DEFAULT '-',
  `lastissueddt` date NOT NULL DEFAULT '0000-00-00',
  `newstg` tinyint(1) NOT NULL DEFAULT 0,
  `newstage` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `newstgreason` varchar(250) NOT NULL DEFAULT '-',
  `newr0dt` tinyint(1) NOT NULL DEFAULT 0,
  `r0newdt` date NOT NULL DEFAULT '0000-00-00',
  `r0reason` varchar(250) NOT NULL DEFAULT '-',
  `loginname` varchar(50) NOT NULL DEFAULT '-',
  `dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT 0,
  KEY `dwglist_id` (`dwglist_id`),
  CONSTRAINT `dwghistory_ibfk_1` FOREIGN KEY (`dwglist_id`) REFERENCES `dwglist` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dwghistory`
--

LOCK TABLES `dwghistory` WRITE;
/*!40000 ALTER TABLE `dwghistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `dwghistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dwglist`
--

DROP TABLE IF EXISTS `dwglist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dwglist` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `dwgidentity` varchar(6) NOT NULL DEFAULT '',
  `disciplinecode` varchar(4) NOT NULL,
  `unit` varchar(15) NOT NULL,
  `part` varchar(2) NOT NULL,
  `currentrevno` varchar(5) NOT NULL DEFAULT 'A',
  `title` varchar(150) NOT NULL,
  `scaleina1` varchar(100) NOT NULL DEFAULT '-',
  `remark` varchar(150) NOT NULL DEFAULT '-',
  `priority` varchar(20) NOT NULL DEFAULT '-',
  `stage` tinyint(3) unsigned NOT NULL,
  `newstage` tinyint(3) unsigned NOT NULL,
  `stageclosed` tinyint(1) NOT NULL DEFAULT 0,
  `r0targetdt` date NOT NULL,
  `newr0targetdt` date NOT NULL,
  `r0issuedflag` tinyint(1) NOT NULL DEFAULT 0,
  `r0issuedt` date NOT NULL DEFAULT '0000-00-00',
  `lastissuedrevno` varchar(5) NOT NULL DEFAULT '-',
  `lastissueddate` date NOT NULL DEFAULT '0000-00-00',
  `dtime` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `dwglist_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Deliverables list';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dwglist`
--

LOCK TABLES `dwglist` WRITE;
/*!40000 ALTER TABLE `dwglist` DISABLE KEYS */;
/*!40000 ALTER TABLE `dwglist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `excel2db`
--

DROP TABLE IF EXISTS `excel2db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `excel2db` (
  `project_id` smallint(5) unsigned NOT NULL,
  `filename` varchar(100) NOT NULL,
  `originalfilename` varchar(100) NOT NULL,
  `noofdwgimported` tinyint(3) unsigned NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `importstamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='CSV File upload log.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `excel2db`
--

LOCK TABLES `excel2db` WRITE;
/*!40000 ALTER TABLE `excel2db` DISABLE KEYS */;
/*!40000 ALTER TABLE `excel2db` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filestore`
--

DROP TABLE IF EXISTS `filestore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `filestore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `uploaded_by_uid` mediumint(9) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filestore`
--

LOCK TABLES `filestore` WRITE;
/*!40000 ALTER TABLE `filestore` DISABLE KEYS */;
/*!40000 ALTER TABLE `filestore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `holidays` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dt` date NOT NULL,
  `holiday` varchar(45) NOT NULL,
  `branch_id` tinyint(3) unsigned NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `saturday` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Saturday or Holiday flag',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dt_UNIQUE` (`dt`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `holidays`
--

LOCK TABLES `holidays` WRITE;
/*!40000 ALTER TABLE `holidays` DISABLE KEYS */;
/*!40000 ALTER TABLE `holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `log_manhour_alloc`
--

DROP TABLE IF EXISTS `log_manhour_alloc`;
/*!50001 DROP VIEW IF EXISTS `log_manhour_alloc`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `log_manhour_alloc` AS SELECT
 1 AS `task_id`,
  1 AS `project_id`,
  1 AS `totalmin`,
  1 AS `manhours`,
  1 AS `onhold` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `manhourcost`
--

DROP TABLE IF EXISTS `manhourcost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `manhourcost` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `finyear` year(4) NOT NULL,
  `user_id` mediumint(9) NOT NULL,
  `costperhour` mediumint(9) NOT NULL DEFAULT 0 COMMENT 'in paise',
  PRIMARY KEY (`id`),
  UNIQUE KEY `manhourcost_uk` (`finyear`,`user_id`),
  KEY `manhourcost_fy` (`finyear`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manhourcost`
--

LOCK TABLES `manhourcost` WRITE;
/*!40000 ALTER TABLE `manhourcost` DISABLE KEYS */;
/*!40000 ALTER TABLE `manhourcost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'System Administration',1),(2,'Finance & Accounts',0),(3,'Management Information System',1);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moduletick`
--

DROP TABLE IF EXISTS `moduletick`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `moduletick` (
  `modules_id` tinyint(3) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moduletick`
--

LOCK TABLES `moduletick` WRITE;
/*!40000 ALTER TABLE `moduletick` DISABLE KEYS */;
/*!40000 ALTER TABLE `moduletick` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projecthistoric`
--

DROP TABLE IF EXISTS `projecthistoric`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projecthistoric` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `stage_id` smallint(5) unsigned NOT NULL,
  `manminutes` int(10) unsigned NOT NULL,
  `costinpaise` int(10) unsigned NOT NULL,
  `dtime` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projecthistoric`
--

LOCK TABLES `projecthistoric` WRITE;
/*!40000 ALTER TABLE `projecthistoric` DISABLE KEYS */;
/*!40000 ALTER TABLE `projecthistoric` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` tinyint(3) unsigned NOT NULL,
  `projectname` varchar(100) NOT NULL COMMENT 'Project name must be unique',
  `jobcode` varchar(100) NOT NULL COMMENT 'Jobcode must be unique',
  `branch_id` smallint(5) unsigned NOT NULL DEFAULT 1,
  `handover_dt` date NOT NULL DEFAULT '0000-00-00',
  `projectstatus_id` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `teamleader` varchar(100) NOT NULL,
  `designmanager` varchar(100) NOT NULL,
  `currentdwglistver` varchar(15) NOT NULL DEFAULT 'A',
  `dt` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  `projecttype_id` smallint(5) unsigned NOT NULL DEFAULT 1,
  `size` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sizeunit` varchar(10) NOT NULL DEFAULT '-',
  `blockflag` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0|1 = Single-block|Multi-block',
  `currentstage_id` tinyint(3) unsigned NOT NULL DEFAULT 11,
  `signoffdt` date NOT NULL DEFAULT '0000-00-00',
  `contractdt` date NOT NULL DEFAULT '0000-00-00',
  `contract_period_years` smallint(6) NOT NULL DEFAULT 0,
  `contract_period_months` smallint(6) NOT NULL DEFAULT 0,
  `escalation_kickoff` tinyint(4) NOT NULL DEFAULT 0,
  `escalationdt_start` date NOT NULL DEFAULT '0000-00-00',
  `escalation_rate` float NOT NULL DEFAULT 1,
  `escalation_note` varchar(250) NOT NULL DEFAULT '-',
  PRIMARY KEY (`id`),
  UNIQUE KEY `projectname_UNIQUE` (`projectname`),
  UNIQUE KEY `jobcode_UNIQUE` (`jobcode`),
  KEY `projects_unique_key` (`domain_id`,`jobcode`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Projects';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,2,'New Projects','<!-- X01 -->',0,'0000-00-00',1,'-','-','A','0000-00-00',0,0,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(2,2,'Leave | Full Day','<!-- X02 -->',0,'0000-00-00',1,'-','-','A','0000-00-00',0,0,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(3,2,'Leave | Half Day','<!-- X03 -->',1,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'-',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(4,2,'Time off','<!-- X04 -->',1,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'-',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(20,2,'Overheads | Happy Hours/Celebration','S20:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(30,2,'Overheads | Vectorworks Training','S30:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(35,2,'Overheads | Client Meeting','S35:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(40,2,'Overheads | Vendor Workshop','S40:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(50,2,'Overheads | Assessment/Monthly Schedule','S50:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(60,2,'Overheads | Interview/Induction','S60:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(70,2,'Overheads | Appraisal','S70:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(75,1,'Overheads | Allowed Time-off','S75:12:13:14:15:16:17:18:19:20:21:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-'),(80,2,'Overheads | Miscellaneous','S80:12:13:14:15:16:17:18:19:20:21:22:24:25',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'-',0,0,'0000-00-00','0000-00-00',1,0,0,'0000-00-00',0,'x'),(90,2,'Overheads | Sourcing candidates','H90:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(95,2,'Overheads | Interview','H95:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(100,2,'Overheads | Onboarding','H100:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(105,2,'Overheads | Offboarding','H105:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(110,2,'Overheads | Payroll processing','H110:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(115,2,'Overheads | Employee Engagement','H115:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(120,2,'Overheads | Performance Management','H120:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(125,2,'Overheads | Arkafe support','H125:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(130,2,'Overheads | Admin & Accounts ','H130:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(135,2,'Overheads | IT matters','H135:22',0,'0000-00-00',1,'-','-','A','0000-00-00',0,1,0,'0',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',1,'-'),(500,1,'ACME test','20240107',17,'0000-00-00',1,'-','-','A','2024-01-07',0,0,0,'-',0,2,'0000-00-00','0000-00-00',0,0,0,'0000-00-00',0,'-');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectschedule`
--

DROP TABLE IF EXISTS `projectschedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectschedule` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `stage_id` tinyint(3) unsigned NOT NULL COMMENT 'Milestone id',
  `targetdt` date NOT NULL,
  `dtime` datetime NOT NULL COMMENT 'row insertion timestamp',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Milestones';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectschedule`
--

LOCK TABLES `projectschedule` WRITE;
/*!40000 ALTER TABLE `projectschedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `projectschedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectscope`
--

DROP TABLE IF EXISTS `projectscope`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectscope` (
  `id` smallint(6) NOT NULL,
  `scope` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectscope`
--

LOCK TABLES `projectscope` WRITE;
/*!40000 ALTER TABLE `projectscope` DISABLE KEYS */;
INSERT INTO `projectscope` VALUES (1,'NA','Not Applicable',1,0),(10,'MP','Masterplan',10,1),(20,'AD','Architecture',20,1),(30,'ID','Interior Design',50,1),(40,'LD','Landscape',40,1);
/*!40000 ALTER TABLE `projectscope` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectscopemap`
--

DROP TABLE IF EXISTS `projectscopemap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectscopemap` (
  `project_id` int(11) NOT NULL,
  `activescopeids` varchar(45) NOT NULL COMMENT 'CSV of active scopes for the project.',
  UNIQUE KEY `project_id_UNIQUE` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectscopemap`
--

LOCK TABLES `projectscopemap` WRITE;
/*!40000 ALTER TABLE `projectscopemap` DISABLE KEYS */;
/*!40000 ALTER TABLE `projectscopemap` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectsrole`
--

DROP TABLE IF EXISTS `projectsrole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectsrole` (
  `id` smallint(6) NOT NULL,
  `role` varchar(45) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectsrole`
--

LOCK TABLES `projectsrole` WRITE;
/*!40000 ALTER TABLE `projectsrole` DISABLE KEYS */;
INSERT INTO `projectsrole` VALUES (1,'NA',0),(10,'Project Leader',1),(12,'Project Coordinator',1),(14,'Project Member',1);
/*!40000 ALTER TABLE `projectsrole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectstage`
--

DROP TABLE IF EXISTS `projectstage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectstage` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT 'Milestone',
  `stageno` tinyint(3) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sname` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Milestone (See timesheettasks for Tasks)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectstage`
--

LOCK TABLES `projectstage` WRITE;
/*!40000 ALTER TABLE `projectstage` DISABLE KEYS */;
INSERT INTO `projectstage` VALUES (1,'NA',0,0,'NA'),(2,'Concept Design',30,1,'CD'),(3,'Schematic Design',40,1,'SD'),(4,'Design Development',60,1,'DD'),(5,'Consultant Coordination',50,1,'CC'),(6,'Approvals',70,1,'A'),(7,'ADV WD',80,1,'AWD'),(8,'Working Drawings',90,1,'WD'),(9,'Site Supervision',100,1,'SS'),(10,'Project Closure',250,0,'PC'),(11,'Draft Masterplan',10,1,'DMP'),(12,'Final Concept Masterplan',20,1,'CMP');
/*!40000 ALTER TABLE `projectstage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectstagetasks`
--

DROP TABLE IF EXISTS `projectstagetasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectstagetasks` (
  `projectstage_id` tinyint(3) unsigned NOT NULL,
  `timesheettask_id` tinyint(3) unsigned NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Milestone and Tasks Mapping';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectstagetasks`
--

LOCK TABLES `projectstagetasks` WRITE;
/*!40000 ALTER TABLE `projectstagetasks` DISABLE KEYS */;
INSERT INTO `projectstagetasks` VALUES (2,2,200100,1,0),(2,3,200200,1,0),(2,4,200300,1,0),(2,5,200400,1,0),(2,6,200500,1,0),(2,7,200600,1,0),(2,8,200700,1,0),(2,9,200800,1,0),(2,10,200900,1,0),(2,11,201000,1,0),(2,12,201100,1,0),(2,13,201200,1,0),(2,14,201300,1,0),(2,15,201400,1,0),(2,16,201500,1,0),(2,133,201600,1,0),(3,17,300100,1,0),(3,18,300200,1,0),(3,19,300300,1,0),(3,20,300400,1,0),(3,21,300500,1,0),(3,22,300600,1,0),(3,23,300700,1,0),(3,24,300800,1,0),(3,25,300900,1,0),(3,26,301000,1,0),(3,27,301100,1,0),(3,28,301200,1,0),(3,133,301300,1,0),(6,29,600100,1,0),(6,30,600200,1,0),(6,31,600300,1,0),(6,32,600400,1,0),(6,33,600500,1,0),(6,34,600600,1,0),(6,35,600700,1,0),(6,36,600800,1,0),(6,37,600900,1,0),(6,38,601000,1,0),(6,39,601100,1,0),(6,40,601200,1,0),(6,41,601300,1,0),(6,42,601400,1,0),(6,43,601500,1,0),(6,44,601600,1,0),(6,45,601700,1,0),(6,46,601800,1,0),(6,47,601900,1,0),(6,48,602000,1,0),(6,49,602100,1,0),(6,50,602200,1,0),(6,51,602300,1,0),(6,52,602400,1,0),(6,133,602500,1,0),(4,53,400100,1,0),(4,54,400200,1,0),(4,55,400300,1,0),(4,56,400400,1,0),(4,57,400500,1,0),(4,58,400600,1,0),(4,59,400700,1,0),(4,60,400800,1,0),(4,61,400900,1,0),(4,62,401000,1,0),(4,63,401100,1,0),(4,64,401200,1,0),(4,65,401300,1,0),(4,66,401400,1,0),(4,67,401500,1,0),(4,68,401600,1,0),(4,69,401700,1,0),(4,70,401800,1,0),(4,72,401900,1,0),(4,73,402000,1,0),(4,74,402100,1,0),(4,71,402200,1,0),(4,133,402300,1,0),(5,75,500100,1,0),(5,76,500200,1,0),(5,77,500300,1,0),(5,78,500400,1,0),(5,79,500500,1,0),(5,81,500600,1,0),(5,82,500700,1,0),(5,83,500800,1,0),(5,80,500900,1,0),(5,133,501000,1,0),(7,84,700100,1,0),(7,85,700200,1,0),(7,86,700300,1,0),(7,87,700400,1,0),(7,88,700500,1,0),(7,89,700600,1,0),(7,90,700700,1,0),(7,91,700800,1,0),(7,92,700900,1,0),(7,93,701000,1,0),(7,95,701100,1,0),(7,96,701200,1,0),(7,97,701300,1,0),(7,94,701400,1,0),(7,133,701500,1,0),(8,98,800100,1,0),(8,99,800200,1,0),(8,100,800300,1,0),(8,101,800400,1,0),(8,102,800500,1,0),(8,103,800600,1,0),(8,104,800700,1,0),(8,105,800800,1,0),(8,106,800900,1,0),(8,107,801000,1,0),(8,108,801100,1,0),(8,109,801200,1,0),(8,110,801300,1,0),(8,111,801400,1,0),(8,112,801500,1,0),(8,113,801600,1,0),(8,28,801700,1,0),(8,133,801800,1,0),(9,114,900100,1,0),(9,115,900200,1,0),(9,116,900300,1,0),(9,117,900400,1,0),(9,118,900500,1,0),(9,119,900600,1,0),(9,28,900700,1,0),(9,133,900800,1,0),(10,120,1000100,1,0),(10,121,1000200,1,0),(10,122,1000300,1,0),(10,123,1000400,1,0),(10,124,1000500,1,0),(10,125,1000600,1,0),(10,126,1000700,1,0),(10,127,1000800,1,0),(10,128,1000900,1,0),(10,129,1001000,1,0),(10,130,1001100,1,0),(10,131,1001200,1,0),(10,132,1001300,1,0),(10,28,1001400,1,0),(10,133,1001500,1,0),(2,2,200100,2,1),(2,2,200100,4,1),(2,3,200200,2,1),(2,4,200300,2,1),(2,4,200300,3,1),(2,4,200300,4,1),(2,5,200400,2,1),(2,5,200400,3,1),(2,6,200500,3,1),(2,7,200600,3,1),(2,8,200700,5,1),(2,8,200700,4,1),(2,9,200800,2,1),(2,9,200800,4,1),(2,10,200900,2,1),(2,11,201000,2,1),(2,11,201000,4,1),(2,12,201100,2,1),(2,13,201200,2,1),(2,14,201300,4,1),(2,14,201300,3,1),(2,15,201400,3,1),(2,15,201400,4,1),(2,16,201500,2,1),(2,16,201500,3,1),(2,16,201500,4,1),(2,133,201600,2,1),(2,133,201600,3,1),(2,133,201600,4,1),(3,17,300100,3,1),(3,18,300200,5,1),(3,18,300200,4,1),(3,19,300300,3,1),(3,19,300300,4,1),(3,19,300300,5,1),(3,20,300400,3,1),(3,21,300500,3,1),(3,21,300500,4,1),(3,21,300500,5,1),(3,22,300600,3,1),(3,23,300700,3,1),(3,23,300700,4,1),(3,23,300700,5,1),(3,24,300800,3,1),(3,24,300800,4,1),(3,25,300900,3,1),(3,25,300900,4,1),(3,25,300900,5,1),(3,26,301000,3,1),(3,27,301100,3,1),(3,28,301200,3,1),(3,28,301200,4,1),(3,28,301200,5,1),(3,133,301300,3,1),(3,133,301300,4,1),(3,133,301300,5,1),(6,29,600100,3,1),(6,30,600200,3,1),(6,31,600300,3,1),(6,32,600400,3,1),(6,33,600500,3,1),(6,34,600600,3,1),(6,35,600700,3,1),(6,36,600800,3,1),(6,37,600900,3,1),(6,38,601000,3,1),(6,39,601100,3,1),(6,40,601200,3,1),(6,41,601300,3,1),(6,42,601400,3,1),(6,42,601400,4,1),(6,42,601400,5,1),(6,43,601500,3,1),(6,43,601500,4,1),(6,44,601600,3,1),(6,44,601600,4,1),(6,45,601700,3,1),(6,45,601700,4,1),(6,46,601800,3,1),(6,47,601900,3,1),(6,47,601900,4,1),(6,47,601900,5,1),(6,48,602000,3,1),(6,48,602000,4,1),(6,49,602100,3,1),(6,49,602100,4,1),(6,50,602200,3,1),(6,51,602300,3,1),(6,52,602400,3,1),(6,52,602400,4,1),(6,52,602400,5,1),(6,133,602500,3,1),(6,133,602500,4,1),(6,133,602500,5,1),(4,53,400100,3,1),(4,54,400200,3,1),(4,55,400300,3,1),(4,56,400400,3,1),(4,57,400500,3,1),(4,58,400600,3,1),(4,59,400700,3,1),(4,60,400800,3,1),(4,60,400800,4,1),(4,60,400800,5,1),(4,61,400900,3,1),(4,61,400900,4,1),(4,61,400900,5,1),(4,62,401000,3,1),(4,62,401000,4,1),(4,63,401100,3,1),(4,63,401100,4,1),(4,64,401200,3,1),(4,64,401200,4,1),(4,65,401300,5,1),(4,65,401300,4,1),(4,66,401400,4,1),(4,66,401400,3,1),(4,67,401500,3,1),(4,67,401500,5,1),(4,67,401500,4,1),(4,68,401600,3,1),(4,68,401600,5,1),(4,68,401600,4,1),(4,69,401700,3,1),(4,69,401700,4,1),(4,70,401800,3,1),(4,70,401800,4,1),(4,72,401900,3,1),(4,72,401900,4,1),(4,72,401900,5,1),(4,73,402000,3,1),(4,73,402000,4,1),(4,73,402000,5,1),(4,74,402100,3,1),(4,71,402200,3,1),(4,71,402200,4,1),(4,133,402300,3,1),(4,133,402300,4,1),(4,133,402300,5,1),(5,75,500100,3,1),(5,76,500200,3,1),(5,77,500300,3,1),(5,78,500400,3,1),(5,79,500500,3,1),(5,81,500600,4,1),(5,82,500700,3,1),(5,82,500700,4,1),(5,82,500700,5,1),(5,83,500800,3,1),(5,83,500800,4,1),(5,83,500800,5,1),(5,80,500900,3,1),(5,80,500900,4,1),(5,133,501000,3,1),(5,133,501000,4,1),(5,133,501000,5,1),(7,84,700100,3,1),(7,85,700200,3,1),(7,86,700300,5,1),(7,87,700400,3,1),(7,87,700400,4,1),(7,88,700500,3,1),(7,89,700600,3,1),(7,89,700600,4,1),(7,89,700600,5,1),(7,90,700700,3,1),(7,90,700700,4,1),(7,90,700700,5,1),(7,91,700800,3,1),(7,91,700800,4,1),(7,91,700800,5,1),(7,92,700900,4,1),(7,93,701000,3,1),(7,93,701000,4,1),(7,93,701000,5,1),(7,95,701100,3,1),(7,96,701200,3,1),(7,96,701200,4,1),(7,96,701200,5,1),(7,97,701300,3,1),(7,94,701400,3,1),(7,94,701400,4,1),(7,94,701400,5,1),(7,133,701500,3,1),(7,133,701500,4,1),(7,133,701500,5,1),(8,98,800100,3,1),(8,98,800100,4,1),(8,99,800200,3,1),(8,100,800300,3,1),(8,101,800400,3,1),(8,102,800500,3,1),(8,103,800600,3,1),(8,104,800700,3,1),(8,105,800800,3,1),(8,105,800800,4,1),(8,105,800800,5,1),(8,106,800900,3,1),(8,106,800900,4,1),(8,106,800900,5,1),(8,107,801000,3,1),(8,107,801000,4,1),(8,107,801000,5,1),(8,108,801100,4,1),(8,108,801100,5,1),(8,109,801200,3,1),(8,109,801200,4,1),(8,110,801300,3,1),(8,110,801300,4,1),(8,110,801300,5,1),(8,111,801400,3,1),(8,111,801400,4,1),(8,111,801400,5,1),(8,112,801500,3,1),(8,113,801600,3,1),(8,113,801600,4,1),(8,113,801600,5,1),(8,28,801700,3,1),(8,28,801700,4,1),(8,28,801700,5,1),(8,133,801800,3,1),(8,133,801800,4,1),(8,133,801800,5,1),(9,114,900100,3,1),(9,114,900100,4,1),(9,115,900200,4,1),(9,115,900200,5,1),(9,116,900300,4,1),(9,116,900300,5,1),(9,117,900400,4,1),(9,117,900400,5,1),(9,118,900500,4,1),(9,118,900500,5,1),(9,119,900600,4,1),(9,119,900600,5,1),(9,28,900700,3,1),(9,28,900700,4,1),(9,28,900700,5,1),(9,133,900800,3,1),(9,133,900800,4,1),(9,133,900800,5,1),(10,120,1000100,4,1),(10,121,1000200,3,1),(10,122,1000300,0,1),(10,123,1000400,4,1),(10,124,1000500,4,1),(10,125,1000600,4,1),(10,126,1000700,4,1),(10,127,1000800,4,1),(10,128,1000900,3,1),(10,129,1001000,4,1),(10,130,1001100,3,1),(10,130,1001100,4,1),(10,130,1001100,5,1),(10,131,1001200,3,1),(10,131,1001200,4,1),(10,132,1001300,3,1),(10,28,1001400,3,1),(10,28,1001400,4,1),(10,28,1001400,5,1),(10,133,1001500,3,1),(10,133,1001500,4,1),(10,133,1001500,5,1),(2,134,200550,2,1),(2,134,200550,3,1),(2,134,200550,4,1),(2,134,200550,1,0);
/*!40000 ALTER TABLE `projectstagetasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projectstatus`
--

DROP TABLE IF EXISTS `projectstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projectstatus` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projectstatus`
--

LOCK TABLES `projectstatus` WRITE;
/*!40000 ALTER TABLE `projectstatus` DISABLE KEYS */;
INSERT INTO `projectstatus` VALUES (1,'Pitching','-',10,1),(2,'Active','Signed off project',20,1),(3,'On Hold','-',30,1),(4,'Completed','-',40,1);
/*!40000 ALTER TABLE `projectstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_leave_add`
--

DROP TABLE IF EXISTS `rd_leave_add`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_leave_add` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` mediumint(8) unsigned NOT NULL,
  `leave_type_id` int(10) unsigned NOT NULL,
  `added_nod` smallint(6) NOT NULL COMMENT 'Added no-of-days',
  `added_by_uid` mediumint(8) unsigned NOT NULL COMMENT 'Added by user_id - HR or Automatic',
  `year` smallint(6) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `starting_balance_flag` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rd_leave_add` (`dt`,`user_id`,`leave_type_id`),
  KEY `idx_rd_leave_add_dt` (`dt`),
  KEY `idx_rd_leave_add_year` (`year`),
  KEY `idx_rd_leave_add_month` (`month`),
  KEY `idx_rd_leave_add_uid` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_leave_add`
--

LOCK TABLES `rd_leave_add` WRITE;
/*!40000 ALTER TABLE `rd_leave_add` DISABLE KEYS */;
/*!40000 ALTER TABLE `rd_leave_add` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_leave_attr`
--

DROP TABLE IF EXISTS `rd_leave_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_leave_attr` (
  `id` tinyint(4) NOT NULL,
  `attribute` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_leave_attr`
--

LOCK TABLES `rd_leave_attr` WRITE;
/*!40000 ALTER TABLE `rd_leave_attr` DISABLE KEYS */;
INSERT INTO `rd_leave_attr` VALUES (1,'NA','TBD',1),(9,'Short Leave','slr',0),(10,'Sanctioned','ok',1),(20,'Un-sanctioned','does not meet criteria',1),(24,'LWP - Reversible','lwp',0),(25,'LWP - Non Reversible','lwp',0),(30,'Informed','on day of leave before 10:30am',1),(40,'Un-informed','un informed',0);
/*!40000 ALTER TABLE `rd_leave_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_leave_log`
--

DROP TABLE IF EXISTS `rd_leave_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_leave_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_records_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'action by uid',
  `command` varchar(45) NOT NULL,
  `log` varchar(250) NOT NULL COMMENT 'store data in JSON format',
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2625 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_leave_log`
--

LOCK TABLES `rd_leave_log` WRITE;
/*!40000 ALTER TABLE `rd_leave_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `rd_leave_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_leave_records`
--

DROP TABLE IF EXISTS `rd_leave_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_leave_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL,
  `leave_type_id` smallint(6) NOT NULL,
  `leave_attr_id` tinyint(4) NOT NULL DEFAULT 1,
  `applied_on` datetime NOT NULL DEFAULT current_timestamp(),
  `from_dt` date NOT NULL,
  `from_dt_units` varchar(5) NOT NULL COMMENT 'F: Fullday | FH: First Halfday | SH: Second Halfday',
  `end_dt` date NOT NULL,
  `end_dt_units` varchar(5) NOT NULL COMMENT 'F: Fullday | FH: First Halfday | SH: Second Halfday',
  `nod_units` smallint(6) NOT NULL COMMENT 'nod X 10 eg 2.5 days = 25 units',
  `reason` varchar(45) NOT NULL,
  `status_id` smallint(6) NOT NULL,
  `revoke` smallint(6) NOT NULL DEFAULT 0 COMMENT 'flag :: 0: default | 1: Revoke Requested | 2: Revoke Approved | 3: Revoke Rejected',
  `revoke_reason` varchar(45) NOT NULL DEFAULT 'X',
  `dt_last_updated` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `year_generated` smallint(6) NOT NULL,
  `month_generated` tinyint(4) NOT NULL,
  `emoji` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0 | default and 1 | Applied and 2 | Approved and 3 | Rejected',
  PRIMARY KEY (`id`),
  KEY `idx_rd_leave_records_user_id` (`user_id`),
  KEY `idx_rd_leave_records_year_generated` (`year_generated`),
  KEY `idx_rd_leave_records_month_generated` (`month_generated`)
) ENGINE=InnoDB AUTO_INCREMENT=1261 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_leave_records`
--

LOCK TABLES `rd_leave_records` WRITE;
/*!40000 ALTER TABLE `rd_leave_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `rd_leave_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_leave_type`
--

DROP TABLE IF EXISTS `rd_leave_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_leave_type` (
  `id` smallint(6) NOT NULL,
  `type` varchar(50) NOT NULL,
  `sname` varchar(10) NOT NULL COMMENT 'Short name',
  `is_weeklyoff` tinyint(4) NOT NULL,
  `is_holiday` tinyint(4) NOT NULL,
  `applicable_days` tinyint(4) NOT NULL,
  `gender` varchar(10) NOT NULL DEFAULT 'All' COMMENT 'Applicable to All | Male | Female',
  `is_normal` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Normal leaves can be applied by employees. Special leaves are entered by HR.',
  `displayorder` smallint(6) NOT NULL DEFAULT 100,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_leave_type`
--

LOCK TABLES `rd_leave_type` WRITE;
/*!40000 ALTER TABLE `rd_leave_type` DISABLE KEYS */;
INSERT INTO `rd_leave_type` VALUES (1,'Privilege Leave','PL',0,0,4,'Male',1,10,0),(2,'Earned Leave','EL',0,0,0,'Male',1,20,1),(3,'Un-sanctioned Leave','SL',0,0,0,'Male',1,30,0),(4,'Maternity Leave','ML',0,0,0,'Female',0,40,0),(5,'Paternity Leave','PAL',0,0,0,'Male',0,50,0),(8,'Compensatory Leave','Comp',0,0,0,'Male',1,80,0),(9,'Short Leave','SHL',0,0,0,'NA',0,90,0),(24,'LWP - Reversible','LWP R',0,0,0,'NA',0,240,1),(25,'LWP - Non Reversible','LWP NR',0,0,0,'Male',0,250,1);
/*!40000 ALTER TABLE `rd_leave_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rd_status`
--

DROP TABLE IF EXISTS `rd_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rd_status` (
  `id` int(11) NOT NULL,
  `module` varchar(45) NOT NULL DEFAULT 'All' COMMENT 'Module: All | Name',
  `statuscode` varchar(45) NOT NULL,
  `status` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rd_status`
--

LOCK TABLES `rd_status` WRITE;
/*!40000 ALTER TABLE `rd_status` DISABLE KEYS */;
INSERT INTO `rd_status` VALUES (1,'All','A','Approved'),(2,'All','LR','Rejected'),(5,'All','AP','Approval Pending'),(6,'Leave','SPL','Special Leave | HR'),(10,'Leave','LA','Leave Approved'),(11,'Leave','LR','Leave Rejected'),(12,'Leave','LR','Leave Record'),(30,'Leave','RR','Revoke Requested'),(31,'Leave','RA','Revoke Approved'),(33,'Leave','RX','Revoke Rejected');
/*!40000 ALTER TABLE `rd_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `rd_view_leave_applied`
--

DROP TABLE IF EXISTS `rd_view_leave_applied`;
/*!50001 DROP VIEW IF EXISTS `rd_view_leave_applied`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `rd_view_leave_applied` AS SELECT
 1 AS `user_id`,
  1 AS `leave_type_id`,
  1 AS `year`,
  1 AS `nod` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rd_view_leave_applied_monthly`
--

DROP TABLE IF EXISTS `rd_view_leave_applied_monthly`;
/*!50001 DROP VIEW IF EXISTS `rd_view_leave_applied_monthly`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `rd_view_leave_applied_monthly` AS SELECT
 1 AS `user_id`,
  1 AS `leave_type_id`,
  1 AS `year`,
  1 AS `month`,
  1 AS `nod` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rd_view_leave_availed`
--

DROP TABLE IF EXISTS `rd_view_leave_availed`;
/*!50001 DROP VIEW IF EXISTS `rd_view_leave_availed`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `rd_view_leave_availed` AS SELECT
 1 AS `user_id`,
  1 AS `leave_type_id`,
  1 AS `leave_attr_id`,
  1 AS `year`,
  1 AS `nod` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rd_view_leave_availed_monthly`
--

DROP TABLE IF EXISTS `rd_view_leave_availed_monthly`;
/*!50001 DROP VIEW IF EXISTS `rd_view_leave_availed_monthly`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `rd_view_leave_availed_monthly` AS SELECT
 1 AS `user_id`,
  1 AS `leave_type_id`,
  1 AS `leave_attr_id`,
  1 AS `year`,
  1 AS `month`,
  1 AS `nod` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `rd_view_leaves`
--

DROP TABLE IF EXISTS `rd_view_leaves`;
/*!50001 DROP VIEW IF EXISTS `rd_view_leaves`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `rd_view_leaves` AS SELECT
 1 AS `id`,
  1 AS `user_id`,
  1 AS `leave_type_id`,
  1 AS `applied_on`,
  1 AS `from_dt`,
  1 AS `from_dt_units`,
  1 AS `end_dt`,
  1 AS `end_dt_units`,
  1 AS `nod_units`,
  1 AS `reason`,
  1 AS `status_id`,
  1 AS `revoke`,
  1 AS `revoke_reason`,
  1 AS `dt_last_updated`,
  1 AS `active`,
  1 AS `dt_applied`,
  1 AS `dt_from`,
  1 AS `dt_end`,
  1 AS `leave_type`,
  1 AS `leave_type_sname`,
  1 AS `statuscode`,
  1 AS `status`,
  1 AS `attribute`,
  1 AS `leave_attr_id`,
  1 AS `year`,
  1 AS `month`,
  1 AS `emoji` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `roleinproject`
--

DROP TABLE IF EXISTS `roleinproject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roleinproject` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `roles_id` tinyint(3) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  UNIQUE KEY `roleinproject_unique_key` (`project_id`,`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User''s role in the Project';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roleinproject`
--

LOCK TABLES `roleinproject` WRITE;
/*!40000 ALTER TABLE `roleinproject` DISABLE KEYS */;
/*!40000 ALTER TABLE `roleinproject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` tinyint(3) unsigned NOT NULL,
  `roles` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles` (`roles`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (50,'Architect'),(210,'Client'),(205,'Consultant'),(35,'Design Manager'),(70,'MEP Member'),(240,'Others'),(220,'PMC'),(30,'Project Leader'),(60,'Studio Member'),(100,'Team Member'),(230,'Vendor');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessi0ns`
--

DROP TABLE IF EXISTS `sessi0ns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessi0ns` (
  `sessionid` varchar(100) NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `projectname` varchar(100) NOT NULL DEFAULT 'x',
  `jobcode` varchar(50) NOT NULL DEFAULT 'x',
  `role_id` tinyint(3) unsigned NOT NULL DEFAULT 100,
  `logintime` datetime NOT NULL DEFAULT current_timestamp(),
  `logouttime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  UNIQUE KEY `sessionid` (`sessionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessi0ns`
--

LOCK TABLES `sessi0ns` WRITE;
/*!40000 ALTER TABLE `sessi0ns` DISABLE KEYS */;
INSERT INTO `sessi0ns` VALUES ('0qai3cbbm50ilft4vqp92q1v4d','stellar',0,'x','x',100,'2025-12-23 14:47:34','0000-00-00 00:00:00',0),('2qnnc9liduaugb9lcnjpal0g71','stellar',0,'x','x',100,'2025-12-23 14:40:48','0000-00-00 00:00:00',0),('7p81qj1dlc3ivoetupe60o1m3h','ankit.agrawal',0,'x','x',100,'2025-12-23 15:27:35','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `sessi0ns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessioncache`
--

DROP TABLE IF EXISTS `sessioncache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessioncache` (
  `sessionid` varchar(100) NOT NULL DEFAULT '',
  `key` varchar(45) NOT NULL,
  `value` varchar(250) NOT NULL,
  `dtime` datetime NOT NULL,
  PRIMARY KEY (`sessionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessioncache`
--

LOCK TABLES `sessioncache` WRITE;
/*!40000 ALTER TABLE `sessioncache` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessioncache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sketches`
--

DROP TABLE IF EXISTS `sketches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sketches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `sketchno` mediumint(8) unsigned NOT NULL,
  `blockno` varchar(10) NOT NULL,
  `disciplinecode` varchar(10) NOT NULL,
  `title` varchar(150) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `sentmode` varchar(20) NOT NULL,
  `dt` date NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sketches`
--

LOCK TABLES `sketches` WRITE;
/*!40000 ALTER TABLE `sketches` DISABLE KEYS */;
/*!40000 ALTER TABLE `sketches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `work` text NOT NULL,
  `remark` varchar(50) NOT NULL DEFAULT '-',
  `projectscope_id` tinyint(3) unsigned NOT NULL,
  `projectstage_id` tinyint(3) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `status_last_month` tinyint(4) NOT NULL DEFAULT 0,
  `status_this_month_target` tinyint(4) NOT NULL DEFAULT 0,
  `allocation_flag` tinyint(4) NOT NULL DEFAULT 0,
  `mandays` smallint(6) NOT NULL DEFAULT 0,
  `manhours` smallint(5) unsigned NOT NULL DEFAULT 0,
  `manminutes` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `dt` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `mcode` varchar(45) NOT NULL DEFAULT 'x' COMMENT 'Migration code. In case it needs to be reversed.',
  `onhold` tinyint(4) NOT NULL DEFAULT 0,
  `cm_date_flag` date NOT NULL DEFAULT '2000-01-01' COMMENT 'current month date flag',
  `cm_allotted_mh` int(11) NOT NULL DEFAULT 0 COMMENT 'current month total allotted manhours in minutes',
  `cm_added_mh` int(11) NOT NULL DEFAULT 0,
  `lm_allotted_mh` int(11) NOT NULL DEFAULT 0 COMMENT 'last month total alloted manhours in minutes',
  PRIMARY KEY (`id`),
  KEY `ix_project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task`
--

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
INSERT INTO `task` VALUES (1,1,'Work','Remark',1,1,10,100,100,0,0,0,0,'0000-00-00 00:00:00',0,'m20240806a',0,'2025-09-01',0,0,0);
/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskallocation`
--

DROP TABLE IF EXISTS `taskallocation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taskallocation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `remark` varchar(100) NOT NULL DEFAULT '-',
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `ix_task_id` (`task_id`),
  KEY `ix_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskallocation`
--

LOCK TABLES `taskallocation` WRITE;
/*!40000 ALTER TABLE `taskallocation` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskallocation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskallotmhlog`
--

DROP TABLE IF EXISTS `taskallotmhlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taskallotmhlog` (
  `task_id` int(10) unsigned NOT NULL,
  `month` date NOT NULL COMMENT 'month end allotted manhours',
  `allottedmin` int(11) NOT NULL COMMENT 'total allotted minutes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskallotmhlog`
--

LOCK TABLES `taskallotmhlog` WRITE;
/*!40000 ALTER TABLE `taskallotmhlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskallotmhlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taskmanhouralloclog`
--

DROP TABLE IF EXISTS `taskmanhouralloclog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taskmanhouralloclog` (
  `task_id` mediumint(8) unsigned NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL,
  `logdate` date NOT NULL DEFAULT current_timestamp(),
  `totalminutes` int(11) unsigned NOT NULL COMMENT 'manhours converted to minutes',
  `manhours` varchar(45) NOT NULL COMMENT 'For quick display',
  `onhold` tinyint(4) NOT NULL,
  KEY `taskmanhouralloclog_dt` (`logdate`),
  KEY `taskmanhouralloclog_pid` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taskmanhouralloclog`
--

LOCK TABLES `taskmanhouralloclog` WRITE;
/*!40000 ALTER TABLE `taskmanhouralloclog` DISABLE KEYS */;
/*!40000 ALTER TABLE `taskmanhouralloclog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timeestimate`
--

DROP TABLE IF EXISTS `timeestimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timeestimate` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `stage_id` tinyint(3) unsigned NOT NULL COMMENT 'projectstage table',
  `task_id` smallint(5) unsigned NOT NULL COMMENT 'timesheettasks table',
  `hrgroup_id` tinyint(3) unsigned NOT NULL COMMENT 'userhrgroup table',
  `manhours` smallint(5) unsigned NOT NULL,
  `version` varchar(100) NOT NULL DEFAULT '-' COMMENT 'CSV Filename',
  `dtime` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Store Fee Calculator Time Estimate';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeestimate`
--

LOCK TABLES `timeestimate` WRITE;
/*!40000 ALTER TABLE `timeestimate` DISABLE KEYS */;
/*!40000 ALTER TABLE `timeestimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timeestimaterate`
--

DROP TABLE IF EXISTS `timeestimaterate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timeestimaterate` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `hrgroup_id` tinyint(3) unsigned NOT NULL,
  `rateperhour` smallint(5) unsigned NOT NULL,
  `hrgroup_name` varchar(45) NOT NULL,
  `version` varchar(45) NOT NULL COMMENT 'csv file name',
  `dtime` datetime NOT NULL COMMENT 'Timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeestimaterate`
--

LOCK TABLES `timeestimaterate` WRITE;
/*!40000 ALTER TABLE `timeestimaterate` DISABLE KEYS */;
/*!40000 ALTER TABLE `timeestimaterate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timeestimateversion`
--

DROP TABLE IF EXISTS `timeestimateversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timeestimateversion` (
  `project_id` mediumint(8) unsigned NOT NULL,
  `version` varchar(45) NOT NULL COMMENT 'csv file name',
  `dtime` datetime NOT NULL COMMENT 'Timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeestimateversion`
--

LOCK TABLES `timeestimateversion` WRITE;
/*!40000 ALTER TABLE `timeestimateversion` DISABLE KEYS */;
/*!40000 ALTER TABLE `timeestimateversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheet`
--

DROP TABLE IF EXISTS `timesheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheet` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dt` date NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL,
  `projectscope_id` smallint(6) NOT NULL DEFAULT 1,
  `projectstage_id` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `department_id` tinyint(3) unsigned NOT NULL DEFAULT 3 COMMENT 'Workgroup',
  `task_id` int(10) unsigned NOT NULL DEFAULT 1,
  `rd_leave_records_id` int(10) unsigned NOT NULL DEFAULT 0,
  `subtask` varchar(15) NOT NULL DEFAULT '-',
  `no_of_hours` tinyint(4) NOT NULL,
  `no_of_min` tinyint(4) NOT NULL,
  `work` text NOT NULL DEFAULT '-',
  `worked_from` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 means approval is pending',
  `quality` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 means not approved by PM',
  `tmstamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `percent` tinyint(4) NOT NULL DEFAULT 0,
  `pm_review_flag` tinyint(4) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `timesheet_date` (`dt`),
  KEY `timesheet_pid` (`project_id`),
  KEY `timesheet_uid` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheet`
--

LOCK TABLES `timesheet` WRITE;
/*!40000 ALTER TABLE `timesheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `timesheet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheetcache`
--

DROP TABLE IF EXISTS `timesheetcache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheetcache` (
  `user_id` mediumint(8) unsigned NOT NULL COMMENT 'user.id',
  `sessionid` varchar(32) NOT NULL COMMENT 'Session id updating this cache',
  `project_id` mediumint(8) unsigned NOT NULL COMMENT 'Last selected project id ',
  `calanderdate` varchar(45) NOT NULL COMMENT 'Calendar date format',
  `stage_id` tinyint(3) unsigned NOT NULL COMMENT 'projectstage.id',
  `memo` varchar(200) NOT NULL DEFAULT '-' COMMENT 'Extra Field',
  UNIQUE KEY `timesheetcache_unique_key` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Timesheet Form data Cache';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheetcache`
--

LOCK TABLES `timesheetcache` WRITE;
/*!40000 ALTER TABLE `timesheetcache` DISABLE KEYS */;
/*!40000 ALTER TABLE `timesheetcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheetlockdt`
--

DROP TABLE IF EXISTS `timesheetlockdt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheetlockdt` (
  `lockdt` date NOT NULL,
  `lastupdatedon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheetlockdt`
--

LOCK TABLES `timesheetlockdt` WRITE;
/*!40000 ALTER TABLE `timesheetlockdt` DISABLE KEYS */;
INSERT INTO `timesheetlockdt` VALUES ('2025-12-17','2025-12-23');
/*!40000 ALTER TABLE `timesheetlockdt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheetlockdtoverride`
--

DROP TABLE IF EXISTS `timesheetlockdtoverride`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheetlockdtoverride` (
  `user_id` mediumint(8) unsigned NOT NULL COMMENT 'Who is allowed to update timesheet',
  `templockdt` date NOT NULL COMMENT 'Provisional Cutoffdate ',
  `admin_uid` mediumint(8) unsigned NOT NULL COMMENT 'Who allowed this override',
  `reason` text NOT NULL COMMENT 'Reason for this override',
  `dtime` datetime NOT NULL COMMENT 'Valid for 24 hours from this timestamp',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheetlockdtoverride`
--

LOCK TABLES `timesheetlockdtoverride` WRITE;
/*!40000 ALTER TABLE `timesheetlockdtoverride` DISABLE KEYS */;
/*!40000 ALTER TABLE `timesheetlockdtoverride` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheetlogs`
--

DROP TABLE IF EXISTS `timesheetlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheetlogs` (
  `timesheet_id` bigint(20) unsigned NOT NULL,
  `pm_uid` mediumint(8) unsigned NOT NULL COMMENT 'project manager''s user_id',
  `department_id` tinyint(3) unsigned NOT NULL,
  `stage_id` tinyint(3) unsigned NOT NULL,
  `task_id` smallint(5) unsigned NOT NULL,
  `no_of_hours` tinyint(3) unsigned NOT NULL,
  `no_of_min` tinyint(3) unsigned NOT NULL,
  `work` text NOT NULL,
  `dtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheetlogs`
--

LOCK TABLES `timesheetlogs` WRITE;
/*!40000 ALTER TABLE `timesheetlogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `timesheetlogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheettasks`
--

DROP TABLE IF EXISTS `timesheettasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheettasks` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1|0 Billable|Non-billable',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tasks (See projectstagetasks for mapping)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheettasks`
--

LOCK TABLES `timesheettasks` WRITE;
/*!40000 ALTER TABLE `timesheettasks` DISABLE KEYS */;
INSERT INTO `timesheettasks` VALUES (1,'NA',0,1),(2,'Initial Site Tour and Site data collection',0,1),(3,'Pitches and Design Presentation',0,1),(4,'Coordination Meetings with Client and Internal team',0,1),(5,'RFP and Profile Development',0,1),(6,'Develop Base Maps',0,1),(7,'Develop Preliminary Concept Plans',0,1),(8,'Prepare Budgetary Cost Estimate',0,1),(9,'Develop Scope, Process and Project Schedule',0,1),(10,'Submit for Approval',0,1),(11,'Contract Cordination with Services Consultants',0,1),(12,'Contract Documentation',0,1),(13,'Contract Sign Off meeting',0,1),(14,'List of Work packages',0,1),(15,'Drawing List Preparation for all work packages',0,1),(16,'Correspondence, Phone Calls, Emails',0,1),(17,'Develop Concept alternatives',1,1),(18,'Prepare preliminary Cost Estimate',1,1),(19,'Submittals/Meetings for Approval',1,1),(20,'Coordination markups',1,1),(21,'Coordination meetings',1,1),(22,'Material Palettes',1,1),(23,'Rework on Concept Stage',1,1),(24,'Summary Design  Report for Client approval',1,1),(25,'Design Refinements after Reviews',1,1),(26,'Prefered Option Closure',1,1),(27,'Final Illustrative layout confirmation of design Intent',1,1),(28,'Correspondence, Phone Calls, Emails',1,1),(29,'Schematic Architectural Drawing Set',1,1),(30,'Schedule of Finishes',1,1),(31,'Schedule of D/W, Openings and Glazing',1,1),(32,'Detailed Design of Areas',1,1),(33,'Spot Details Sheet development',1,1),(34,'Site Plan Development',1,1),(35,'Review Palette of Building and Landscape Materials and Colors',1,1),(36,'Landscape Standards (Parks, Gardens, Courts and Plazas)',1,1),(37,'Develop a Landscape Framework Report (Draft) Plan & Text',1,1),(38,'Recommend Energy Conservation Measures',1,1),(39,'Develop Final Lighting Plan',1,1),(40,'Landscape Consultant Coordination',1,1),(41,'Rework / Abortive Works',1,1),(42,'Additional works not scoped',1,1),(43,'Structural Coordination',1,1),(44,'MEP Coordination Drawings',1,1),(45,'Shop Drgs Dev coordination',1,1),(46,'3D Presentation/Perspective Views, Models /Sims',1,1),(47,'Project Documentation, Estimation and Correspondence',1,1),(48,'Coordination with Approval Authorities during construction',1,1),(49,'Review/Approval meetings as reqd during process',1,1),(50,'Coordination markups',1,1),(51,'Coordination meetings',1,1),(52,'Correspondence, Phone Calls, Emails',1,1),(53,'Detailed Architectural Drawing Set',1,1),(54,'Schedule of Finishes',1,1),(55,'Schedule of D/W, Openings and Glazing',1,1),(56,'Detailed Design of Areas',1,1),(57,'Spot Details Sheet development',1,1),(58,'Site Plan Development',1,1),(59,'Landscape Consultant Coordination',1,1),(60,'Rework / Abortive Works',1,1),(61,'Additional works not scoped',1,1),(62,'Structural Coordination',1,1),(63,'MEP Coordination Drawings',1,1),(64,'Shop Drgs Dev coordination',1,1),(65,'Detailed Cost Estimates',1,1),(66,'List of Work Packages',1,1),(67,'Internal Review Meeting with Team',1,1),(68,'Review Meeting with CLIENT and Team',1,1),(69,'Prepare Final Arch Drawings for MEP/Structure sendout',1,1),(70,'Submittals for Approval',1,1),(71,'Correspondence, Phone Calls, Emails',1,1),(72,'Coordination External Consultants/ Markups/Meetings',1,1),(73,'Additional works not scoped',1,1),(74,'3D Presentation/Simulation, Perspective Views, Models',1,1),(75,'Regulatory Requirements Study',1,1),(76,'Prepare Drawings/Documents Submittal for Municipal Approval',1,1),(77,'Fire Approval documents and drawings',1,1),(78,'EIA Approval documentation',1,1),(79,'Submit to CLIENT for Approval from concerned authority',1,1),(80,'Correspondence, Phone Calls, Emails',1,1),(81,'Coordination with Approval Authorities during construction',1,1),(82,'Sundry Approval Meetings as required',1,1),(83,'Revisions',1,1),(84,'Prepare Detailed Interior and Exterior Drawings',1,1),(85,'Develop Tender Drawing Sets',1,1),(86,'Prepare Detailed Cost Estimate',1,1),(87,'Prepare Project Implementation schedule',1,1),(88,'Prepare Tender Documents',1,1),(89,'Review Meeting with Client and Team',1,1),(90,'Tender Floating and Scrutiny',1,1),(91,'Assistance with Pre Contract Meeting/Negotiation',1,1),(92,'Work Order Preparation',1,1),(93,'Submittals/Meetings for Approval',1,1),(94,'Correspondence, Phone Calls, Emails',1,1),(95,'Coordination markup drawings',1,1),(96,'Coordination meetings',1,1),(97,'Project Documentation',1,1),(98,'Internal Team Review and Coordination',1,1),(99,'Prepare Detailed Interior and Exterior Drawings',1,1),(100,'Preparing Coordinated GFC Architectural Drawings',1,1),(101,'Preparing Coordinated GFC Structural Drawings',1,1),(102,'Preparing Coordinated GFC MEP Drawings',1,1),(103,'Detailed Design and Spot details',1,1),(104,'Design of Material palletes',1,1),(105,'Vendor Coordination Markups,Phone calls Meetings',1,1),(106,'Additional works not scoped',1,1),(107,'Site Supervisory Attendance, Visits & Site Meetings',1,1),(108,'Bill checking and certification',1,1),(109,'Submittal work',1,1),(110,'Client Meetings for Approval',1,1),(111,'Consultant Coordination markups,meetings phone Calls',1,1),(112,'3D Presentation/Simulation, Perspective Views, Models',1,1),(113,'Rework / Abortive Works',1,1),(114,'Mobilization and Site take-over',1,1),(115,'Quantity offtakes, Procurement Coordination',1,1),(116,'Site coordination activity',1,1),(117,'Vendor Coordination',1,1),(118,'Site Supervisory Attendance, Visits & Site Meetings',1,1),(119,'Bill Verification Visits and Work',1,1),(120,'Handover Coordination',1,1),(121,'Graphics Development',1,1),(122,'Presentation development',1,1),(123,'Prepare Defects list/Technical Audit',1,1),(124,'Coordination for Snagging and Defect removal',1,1),(125,'Testing Coordination',1,1),(126,'Rectification Coordination',1,1),(127,'Bill Verification',1,1),(128,'Prepare As built Drawings/Coordination for Services Manuals',1,1),(129,'Certificate of Completion and Appreciation',1,1),(130,'Refinements and Rework',1,1),(131,'Report generation',1,1),(132,'Photo shoot',1,1),(133,'Travel',1,1),(134,'Feasibility Study',1,1);
/*!40000 ALTER TABLE `timesheettasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheetworkedfrom`
--

DROP TABLE IF EXISTS `timesheetworkedfrom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheetworkedfrom` (
  `id` smallint(6) NOT NULL,
  `workedfrom` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheetworkedfrom`
--

LOCK TABLES `timesheetworkedfrom` WRITE;
/*!40000 ALTER TABLE `timesheetworkedfrom` DISABLE KEYS */;
INSERT INTO `timesheetworkedfrom` VALUES (10,'Office'),(20,'Remote Location'),(30,'Leave');
/*!40000 ALTER TABLE `timesheetworkedfrom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmheader`
--

DROP TABLE IF EXISTS `tmheader`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tmheader` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(100) NOT NULL,
  `wizardstepno` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `startingsrno` smallint(5) unsigned NOT NULL DEFAULT 1,
  `contact` varchar(100) NOT NULL,
  `sentmode` varchar(20) NOT NULL,
  `purpose` varchar(30) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `dtime` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmheader`
--

LOCK TABLES `tmheader` WRITE;
/*!40000 ALTER TABLE `tmheader` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmheader` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmlist`
--

DROP TABLE IF EXISTS `tmlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tmlist` (
  `tmheader_id` bigint(20) unsigned NOT NULL,
  `srno` tinyint(3) unsigned NOT NULL,
  `itemcode` tinyint(3) unsigned NOT NULL,
  `item` varchar(150) NOT NULL DEFAULT '',
  `nos` tinyint(3) unsigned NOT NULL,
  `description` varchar(200) NOT NULL,
  `active` tinyint(1) NOT NULL,
  KEY `tmheader_id` (`tmheader_id`),
  CONSTRAINT `tmlist_ibfk_1` FOREIGN KEY (`tmheader_id`) REFERENCES `tmheader` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmlist`
--

LOCK TABLES `tmlist` WRITE;
/*!40000 ALTER TABLE `tmlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transadd`
--

DROP TABLE IF EXISTS `transadd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transadd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `company` varchar(100) NOT NULL DEFAULT '',
  `dooradd` varchar(75) NOT NULL DEFAULT '',
  `streetadd` varchar(75) NOT NULL DEFAULT '',
  `locality` varchar(75) NOT NULL DEFAULT '',
  `city` varchar(75) NOT NULL DEFAULT '',
  `statecountry` varchar(75) NOT NULL DEFAULT '',
  `pincode` varchar(75) NOT NULL DEFAULT '',
  `website` varchar(150) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transadd`
--

LOCK TABLES `transadd` WRITE;
/*!40000 ALTER TABLE `transadd` DISABLE KEYS */;
/*!40000 ALTER TABLE `transadd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transitems`
--

DROP TABLE IF EXISTS `transitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transitems` (
  `id` tinyint(3) unsigned NOT NULL,
  `item` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transitems`
--

LOCK TABLES `transitems` WRITE;
/*!40000 ALTER TABLE `transitems` DISABLE KEYS */;
INSERT INTO `transitems` VALUES (10,'Drawings'),(20,'cds/dvds'),(30,'Tender Documents'),(40,'Brochures'),(50,'Sample Board'),(60,'Sketch Detail'),(250,'others');
/*!40000 ALTER TABLE `transitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translist`
--

DROP TABLE IF EXISTS `translist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `translist` (
  `transmittal_id` bigint(20) unsigned NOT NULL,
  `srno` tinyint(3) unsigned NOT NULL,
  `itemcode` tinyint(3) unsigned NOT NULL,
  `item` varchar(150) NOT NULL DEFAULT '',
  `nos` tinyint(3) unsigned NOT NULL,
  `description` varchar(150) NOT NULL DEFAULT '',
  KEY `transmittal_id` (`transmittal_id`),
  CONSTRAINT `translist_ibfk_1` FOREIGN KEY (`transmittal_id`) REFERENCES `transmittals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translist`
--

LOCK TABLES `translist` WRITE;
/*!40000 ALTER TABLE `translist` DISABLE KEYS */;
/*!40000 ALTER TABLE `translist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transmittals`
--

DROP TABLE IF EXISTS `transmittals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transmittals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` mediumint(8) unsigned NOT NULL,
  `transno` smallint(5) unsigned NOT NULL,
  `contact` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `sentmode` varchar(20) NOT NULL,
  `purpose` varchar(30) NOT NULL,
  `dtime` datetime NOT NULL,
  `remark` varchar(250) NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `startingsrno` smallint(5) unsigned NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `transmittals_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transmittals`
--

LOCK TABLES `transmittals` WRITE;
/*!40000 ALTER TABLE `transmittals` DISABLE KEYS */;
/*!40000 ALTER TABLE `transmittals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transname`
--

DROP TABLE IF EXISTS `transname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `transname` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact` varchar(100) NOT NULL,
  `role_id` tinyint(3) unsigned NOT NULL,
  `project_id` mediumint(8) unsigned NOT NULL,
  `transadd_id` int(10) unsigned NOT NULL,
  `phoneno` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `extranetlogin` tinyint(1) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `user_profiles_id` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Project contact list. Table name is misleading.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transname`
--

LOCK TABLES `transname` WRITE;
/*!40000 ALTER TABLE `transname` DISABLE KEYS */;
/*!40000 ALTER TABLE `transname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userhrgroup`
--

DROP TABLE IF EXISTS `userhrgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `userhrgroup` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `displayorder` smallint(5) unsigned NOT NULL,
  `defaultrate` smallint(5) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='HR Groups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userhrgroup`
--

LOCK TABLES `userhrgroup` WRITE;
/*!40000 ALTER TABLE `userhrgroup` DISABLE KEYS */;
INSERT INTO `userhrgroup` VALUES (1,'NA',0,0,0),(12,'Principal Architect',10,1500,1),(13,'Senior Associate Architect',20,1200,1),(14,'Senior Architect',25,800,1),(15,'Project Coordinator',30,450,1),(16,'Project Architect',35,460,1),(17,'Jr. Architect Grade I',64,450,1),(18,'Jr. Architect Grade II',63,350,1),(20,'Jr. Architect Grade III',62,1000,1),(21,'Jr. Architect Grade IV',61,800,1),(22,'HR & Admin Executive',100,300,1),(23,'Manager - Admin & Accounts',110,100,1),(24,'Trainee',75,600,1),(25,'Jr. Associate Architect',22,1000,1),(26,'Office Boy',120,100,1),(27,'Draftsman',70,150,1);
/*!40000 ALTER TABLE `userhrgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` smallint(5) unsigned NOT NULL,
  `loginname` varchar(50) NOT NULL COMMENT 'Login name must be unique',
  `passwd` varchar(50) NOT NULL DEFAULT '',
  `fullname` varchar(50) NOT NULL,
  `emailid` varchar(150) NOT NULL,
  `internaluser` tinyint(1) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loginname_UNIQUE` (`loginname`),
  KEY `users_unique_key` (`domain_id`,`loginname`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,'Arkbot','e4793$#5','NA','a@b.com',1,'do not delete',0),(150,0,'stellar','office#102','Stellar','a@b.com',1,'bootstrap daemon',0),(151,2,'Ankit.Agrawal','office#102','Ankit Agrawal','user@domain.tld',1,'-',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_a`
--

DROP TABLE IF EXISTS `users_a`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_a` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `employee_code` varchar(45) NOT NULL DEFAULT '-',
  `employment_type` enum('full_time','part_time','contract','intern','freelancer') NOT NULL DEFAULT 'full_time',
  `fname` varchar(45) NOT NULL DEFAULT '-',
  `lname` varchar(45) NOT NULL DEFAULT '-',
  `mname` varchar(45) NOT NULL DEFAULT '-',
  `mobile` varchar(45) NOT NULL DEFAULT '-',
  `bloodgroup` varchar(15) NOT NULL DEFAULT '-',
  `dob` date NOT NULL DEFAULT '1900-01-01',
  `gender` varchar(5) NOT NULL DEFAULT '-',
  `designation` varchar(50) NOT NULL DEFAULT '-',
  `department_id` int(10) unsigned NOT NULL DEFAULT 1,
  `reports_to_user_id` int(10) unsigned NOT NULL,
  `aadhaar` varchar(45) NOT NULL DEFAULT '-',
  `pan_no` varchar(50) NOT NULL DEFAULT '-',
  `bank_name` varchar(45) NOT NULL DEFAULT '-',
  `bank_account_no` varchar(45) NOT NULL DEFAULT '-',
  `bank_account_ifsc` varchar(45) NOT NULL DEFAULT '-',
  `pf_no` varchar(50) NOT NULL DEFAULT '-',
  `esi_no` varchar(50) NOT NULL DEFAULT '-',
  `uan_no` varchar(50) NOT NULL DEFAULT '-',
  `email_personal` varchar(50) NOT NULL DEFAULT '-',
  `avatar` varchar(150) NOT NULL DEFAULT '-',
  `dt_of_joining` date NOT NULL DEFAULT '1900-01-01',
  `dt_of_confirmation` date NOT NULL DEFAULT '1900-01-01',
  `dt_of_termination` date NOT NULL DEFAULT '2050-12-31',
  `probation` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` smallint(5) unsigned NOT NULL DEFAULT 1,
  `userhrgroup_id` tinyint(3) unsigned NOT NULL,
  `salary` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Monthly salary in paise',
  `incentives` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Annual incentive in paise',
  `hourly_rate` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Hourly rate in paise',
  `emergency_contact_name` varchar(45) NOT NULL DEFAULT '-',
  `emergency_contact_phone` varchar(45) NOT NULL DEFAULT '-',
  `status` enum('active','inactive','resigned','terminated','on_leave') NOT NULL DEFAULT 'active',
  `appraisal_link` tinyint(1) NOT NULL DEFAULT 0,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='User Details';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_a`
--

LOCK TABLES `users_a` WRITE;
/*!40000 ALTER TABLE `users_a` DISABLE KEYS */;
INSERT INTO `users_a` VALUES (1,'-','full_time','','','-','-','-','1900-01-01','M','Sysadmin',3,1,'-','-','-','-','-','-','-','-','-','-','1900-01-01','1900-01-01','2050-12-31',1,14,12,0,0,0,'-','-','active',0),(150,'-','full_time','-','-','-','-','-','1900-01-01','-','-',3,1,'-','-','-','-','-','-','-','-','-','-','1900-01-01','1900-01-01','2050-12-31',1,17,1,0,0,0,'-','-','active',0),(151,'-','full_time','-','-','-','-','-','1977-11-23','-','-',3,1,'-','-','-','-','-','-','-','-','-','-','2025-01-01','1900-01-01','2050-12-31',1,17,12,0,0,0,'-','-','active',0);
/*!40000 ALTER TABLE `users_a` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_address`
--

DROP TABLE IF EXISTS `users_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `type` enum('current','permanent') NOT NULL DEFAULT 'current',
  `address_line1` varchar(150) NOT NULL,
  `address_line2` varchar(150) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `users_address_ibfk_1` (`user_id`),
  CONSTRAINT `users_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_address`
--

LOCK TABLES `users_address` WRITE;
/*!40000 ALTER TABLE `users_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_drawing_list`
--

DROP TABLE IF EXISTS `view_drawing_list`;
/*!50001 DROP VIEW IF EXISTS `view_drawing_list`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_drawing_list` AS SELECT
 1 AS `id`,
  1 AS `project_id`,
  1 AS `dwgidentity`,
  1 AS `disciplinecode`,
  1 AS `sheetno`,
  1 AS `revno`,
  1 AS `title`,
  1 AS `stage`,
  1 AS `stage_sn`,
  1 AS `r0issuedt`,
  1 AS `newr0targetdts`,
  1 AS `aissuedflag`,
  1 AS `commitdt`,
  1 AS `r0targetdt`,
  1 AS `newr0targetdt`,
  1 AS `r0issuedflag`,
  1 AS `lastissuedrevno`,
  1 AS `lastissueddate`,
  1 AS `actionby`,
  1 AS `remark`,
  1 AS `active` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_project_team`
--

DROP TABLE IF EXISTS `view_project_team`;
/*!50001 DROP VIEW IF EXISTS `view_project_team`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_project_team` AS SELECT
 1 AS `project_id`,
  1 AS `user_id`,
  1 AS `roles_id`,
  1 AS `fullname`,
  1 AS `loginname`,
  1 AS `role` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_project_timesheet_sum`
--

DROP TABLE IF EXISTS `view_project_timesheet_sum`;
/*!50001 DROP VIEW IF EXISTS `view_project_timesheet_sum`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_project_timesheet_sum` AS SELECT
 1 AS `pid`,
  1 AS `month`,
  1 AS `total_min`,
  1 AS `quality` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_projects`
--

DROP TABLE IF EXISTS `view_projects`;
/*!50001 DROP VIEW IF EXISTS `view_projects`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_projects` AS SELECT
 1 AS `pid`,
  1 AS `projectname`,
  1 AS `jobcode`,
  1 AS `branch_id`,
  1 AS `branchname`,
  1 AS `active`,
  1 AS `projectleader_id`,
  1 AS `projectleader`,
  1 AS `corporatename`,
  1 AS `domain_id`,
  1 AS `contractdt`,
  1 AS `contract_period_years`,
  1 AS `contract_period_months`,
  1 AS `escalation_kickoff`,
  1 AS `escalationdt_start`,
  1 AS `escalation_rate`,
  1 AS `escalation_note` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_task_allocation`
--

DROP TABLE IF EXISTS `view_task_allocation`;
/*!50001 DROP VIEW IF EXISTS `view_task_allocation`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_task_allocation` AS SELECT
 1 AS `task_id`,
  1 AS `user_id`,
  1 AS `loginname`,
  1 AS `fullname` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_tasks`
--

DROP TABLE IF EXISTS `view_tasks`;
/*!50001 DROP VIEW IF EXISTS `view_tasks`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_tasks` AS SELECT
 1 AS `task_id`,
  1 AS `project_id`,
  1 AS `projectname`,
  1 AS `work`,
  1 AS `scope_id`,
  1 AS `scope`,
  1 AS `scope_sn`,
  1 AS `scope_with_id`,
  1 AS `stage_id`,
  1 AS `stage`,
  1 AS `stage_sn`,
  1 AS `stage_with_id`,
  1 AS `displayorder`,
  1 AS `active`,
  1 AS `allocation_flag`,
  1 AS `allocated_to`,
  1 AS `status_last_month`,
  1 AS `status_this_month_target`,
  1 AS `mandays`,
  1 AS `manhours`,
  1 AS `manminutes`,
  1 AS `onhold`,
  1 AS `dt`,
  1 AS `dt_month`,
  1 AS `cm_date_flag`,
  1 AS `cm_allotted_mh`,
  1 AS `lm_allotted_mh`,
  1 AS `cm_added_mh` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_tasks_my`
--

DROP TABLE IF EXISTS `view_tasks_my`;
/*!50001 DROP VIEW IF EXISTS `view_tasks_my`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_tasks_my` AS SELECT
 1 AS `user_id`,
  1 AS `fullname`,
  1 AS `task_id`,
  1 AS `project_id`,
  1 AS `projectname`,
  1 AS `work`,
  1 AS `scope_id`,
  1 AS `scope`,
  1 AS `scope_sn`,
  1 AS `scope_with_id`,
  1 AS `stage_id`,
  1 AS `stage`,
  1 AS `stage_sn`,
  1 AS `stage_with_id`,
  1 AS `displayorder`,
  1 AS `active`,
  1 AS `allocation_flag`,
  1 AS `onhold` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_timesheets`
--

DROP TABLE IF EXISTS `view_timesheets`;
/*!50001 DROP VIEW IF EXISTS `view_timesheets`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_timesheets` AS SELECT
 1 AS `user_id`,
  1 AS `timesheet_id`,
  1 AS `project_id`,
  1 AS `projectstage_id`,
  1 AS `task_id`,
  1 AS `no_of_hours`,
  1 AS `no_of_min`,
  1 AS `work`,
  1 AS `approved`,
  1 AS `quality`,
  1 AS `tmstamp`,
  1 AS `dtmysql`,
  1 AS `dow`,
  1 AS `date`,
  1 AS `month`,
  1 AS `worked_from`,
  1 AS `stage`,
  1 AS `projectname`,
  1 AS `jobcode`,
  1 AS `sname`,
  1 AS `percent`,
  1 AS `scope_id`,
  1 AS `scope`,
  1 AS `scope_name`,
  1 AS `pm_review_flag` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_users`
--

DROP TABLE IF EXISTS `view_users`;
/*!50001 DROP VIEW IF EXISTS `view_users`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `view_users` AS SELECT
 1 AS `user_id`,
  1 AS `domain_id`,
  1 AS `displayname`,
  1 AS `loginname`,
  1 AS `employee_code`,
  1 AS `employment_type`,
  1 AS `fname`,
  1 AS `mname`,
  1 AS `lname`,
  1 AS `dob`,
  1 AS `dateofbirth`,
  1 AS `mobile`,
  1 AS `bloodgroup`,
  1 AS `emailid`,
  1 AS `gender`,
  1 AS `department_id`,
  1 AS `departmentname`,
  1 AS `branch_id`,
  1 AS `branchname`,
  1 AS `designation`,
  1 AS `userhrgroup_id`,
  1 AS `hrgroup`,
  1 AS `reports_to_user_id`,
  1 AS `reports_to`,
  1 AS `avatar`,
  1 AS `salary`,
  1 AS `incentives`,
  1 AS `hourly_rate`,
  1 AS `doj`,
  1 AS `dt_doj`,
  1 AS `doe`,
  1 AS `dt_doe`,
  1 AS `aadhaar`,
  1 AS `pan_no`,
  1 AS `bank_name`,
  1 AS `bank_account_no`,
  1 AS `bank_account_ifsc`,
  1 AS `pf_no`,
  1 AS `esi_no`,
  1 AS `uan_no`,
  1 AS `emergency_contact_name`,
  1 AS `emergency_contact_phone`,
  1 AS `status`,
  1 AS `email_personal`,
  1 AS `appraisal_link`,
  1 AS `active` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `worked_from`
--

DROP TABLE IF EXISTS `worked_from`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `worked_from` (
  `id` smallint(6) NOT NULL,
  `location` varchar(45) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `worked_from`
--

LOCK TABLES `worked_from` WRITE;
/*!40000 ALTER TABLE `worked_from` DISABLE KEYS */;
INSERT INTO `worked_from` VALUES (1,'NA',1,0),(10,'Office',10,1),(20,'Remote Location',20,1);
/*!40000 ALTER TABLE `worked_from` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 's1db'
--

--
-- Final view structure for view `csv_timesheets`
--

/*!50001 DROP VIEW IF EXISTS `csv_timesheets`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `csv_timesheets` AS select `t1`.`id` AS `tsid`,`t1`.`dt` AS `dt`,date_format(`t1`.`dt`,'%Y-%m') AS `month`,date_format(`t1`.`dt`,'%d-%b-%y') AS `date`,`t2`.`fullname` AS `fullname`,`t3`.`projectname` AS `projectname`,`t4`.`description` AS `scope`,`t5`.`name` AS `milestone`,`t1`.`work` AS `work`,`t1`.`no_of_hours` AS `hours`,`t1`.`no_of_min` AS `minutes`,`t1`.`percent` AS `percent`,`t6`.`location` AS `worked_from` from (((((`timesheet` `t1` join `users` `t2`) join `projects` `t3`) join `projectscope` `t4`) join `projectstage` `t5`) join `worked_from` `t6`) where `t1`.`user_id` = `t2`.`id` and `t1`.`project_id` = `t3`.`id` and `t1`.`active` > 0 and `t1`.`quality` < 1 and `t1`.`projectscope_id` = `t4`.`id` and `t1`.`projectstage_id` = `t5`.`id` and `t1`.`worked_from` = `t6`.`id` and `t1`.`project_id` > 15 order by `t1`.`dt` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `log_manhour_alloc`
--

/*!50001 DROP VIEW IF EXISTS `log_manhour_alloc`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `log_manhour_alloc` AS select `t1`.`id` AS `task_id`,`t2`.`id` AS `project_id`,`t1`.`manhours` * 60 + `t1`.`manminutes` AS `totalmin`,if(`t1`.`manminutes` > 9,concat(`t1`.`manhours`,':',`t1`.`manminutes`),concat(`t1`.`manhours`,':0',`t1`.`manminutes`)) AS `manhours`,`t1`.`onhold` AS `onhold` from (`task` `t1` join `projects` `t2`) where `t1`.`project_id` = `t2`.`id` and `t1`.`active` > 0 and `t2`.`active` > 0 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rd_view_leave_applied`
--

/*!50001 DROP VIEW IF EXISTS `rd_view_leave_applied`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rd_view_leave_applied` AS select `t1`.`user_id` AS `user_id`,`t1`.`leave_type_id` AS `leave_type_id`,`t1`.`year_generated` AS `year`,sum(`t1`.`nod_units`) AS `nod` from `rd_leave_records` `t1` where `t1`.`status_id` = '5' and `t1`.`active` > 0 group by `t1`.`user_id`,`t1`.`leave_type_id`,`t1`.`year_generated` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rd_view_leave_applied_monthly`
--

/*!50001 DROP VIEW IF EXISTS `rd_view_leave_applied_monthly`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rd_view_leave_applied_monthly` AS select `t1`.`user_id` AS `user_id`,`t1`.`leave_type_id` AS `leave_type_id`,`t1`.`year_generated` AS `year`,`t1`.`month_generated` AS `month`,sum(`t1`.`nod_units`) AS `nod` from `rd_leave_records` `t1` where `t1`.`status_id` = '5' and `t1`.`active` > 0 group by `t1`.`user_id`,`t1`.`leave_type_id`,`t1`.`year_generated`,`t1`.`month_generated` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rd_view_leave_availed`
--

/*!50001 DROP VIEW IF EXISTS `rd_view_leave_availed`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rd_view_leave_availed` AS select `t1`.`user_id` AS `user_id`,`t1`.`leave_type_id` AS `leave_type_id`,`t1`.`leave_attr_id` AS `leave_attr_id`,`t1`.`year_generated` AS `year`,sum(`t1`.`nod_units`) AS `nod` from `rd_leave_records` `t1` where `t1`.`status_id` in ('10','30','33') and `t1`.`active` > 0 group by `t1`.`user_id`,`t1`.`leave_type_id`,`t1`.`leave_attr_id`,`t1`.`year_generated` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rd_view_leave_availed_monthly`
--

/*!50001 DROP VIEW IF EXISTS `rd_view_leave_availed_monthly`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rd_view_leave_availed_monthly` AS select `t1`.`user_id` AS `user_id`,`t1`.`leave_type_id` AS `leave_type_id`,`t1`.`leave_attr_id` AS `leave_attr_id`,`t1`.`year_generated` AS `year`,`t1`.`month_generated` AS `month`,sum(`t1`.`nod_units`) AS `nod` from `rd_leave_records` `t1` where `t1`.`status_id` in ('10','30','33') and `t1`.`active` > 0 group by `t1`.`user_id`,`t1`.`leave_type_id`,`t1`.`leave_attr_id`,`t1`.`year_generated`,`t1`.`month_generated` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `rd_view_leaves`
--

/*!50001 DROP VIEW IF EXISTS `rd_view_leaves`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rd_view_leaves` AS select `t1`.`id` AS `id`,`t1`.`user_id` AS `user_id`,`t1`.`leave_type_id` AS `leave_type_id`,`t1`.`applied_on` AS `applied_on`,`t1`.`from_dt` AS `from_dt`,`t1`.`from_dt_units` AS `from_dt_units`,`t1`.`end_dt` AS `end_dt`,`t1`.`end_dt_units` AS `end_dt_units`,round(`t1`.`nod_units` / 100,2) AS `nod_units`,`t1`.`reason` AS `reason`,`t1`.`status_id` AS `status_id`,`t1`.`revoke` AS `revoke`,`t1`.`revoke_reason` AS `revoke_reason`,`t1`.`dt_last_updated` AS `dt_last_updated`,`t1`.`active` AS `active`,date_format(`t1`.`applied_on`,'%d-%b-%y') AS `dt_applied`,date_format(`t1`.`from_dt`,'%d-%b-%y') AS `dt_from`,date_format(`t1`.`end_dt`,'%d-%b-%y') AS `dt_end`,`t2`.`type` AS `leave_type`,`t2`.`sname` AS `leave_type_sname`,`t3`.`statuscode` AS `statuscode`,`t3`.`status` AS `status`,`t4`.`attribute` AS `attribute`,`t1`.`leave_attr_id` AS `leave_attr_id`,`t1`.`year_generated` AS `year`,`t1`.`month_generated` AS `month`,`t1`.`emoji` AS `emoji` from (((`rd_leave_records` `t1` join `rd_leave_type` `t2`) join `rd_status` `t3`) join `rd_leave_attr` `t4`) where `t1`.`leave_type_id` = `t2`.`id` and `t1`.`status_id` = `t3`.`id` and `t1`.`leave_attr_id` = `t4`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_drawing_list`
--

/*!50001 DROP VIEW IF EXISTS `view_drawing_list`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_drawing_list` AS select `t1`.`id` AS `id`,`t1`.`project_id` AS `project_id`,`t1`.`dwgidentity` AS `dwgidentity`,`t1`.`disciplinecode` AS `disciplinecode`,if(`t1`.`part` = '',concat(`t1`.`dwgidentity`,'-',`t1`.`disciplinecode`,'-',`t1`.`unit`),concat(`t1`.`dwgidentity`,'-',`t1`.`disciplinecode`,'-',`t1`.`unit`,'-',`t1`.`part`)) AS `sheetno`,`t1`.`currentrevno` AS `revno`,`t1`.`title` AS `title`,`t1`.`newstage` AS `stage`,`t2`.`sname` AS `stage_sn`,`t1`.`r0issuedt` AS `r0issuedt`,`t1`.`r0issuedt` AS `newr0targetdts`,`t1`.`stageclosed` AS `aissuedflag`,`t1`.`dtime` AS `commitdt`,`t1`.`r0targetdt` AS `r0targetdt`,`t1`.`newr0targetdt` AS `newr0targetdt`,`t1`.`r0issuedflag` AS `r0issuedflag`,`t1`.`lastissuedrevno` AS `lastissuedrevno`,`t1`.`lastissueddate` AS `lastissueddate`,`t1`.`priority` AS `actionby`,`t1`.`remark` AS `remark`,`t1`.`active` AS `active` from (`dwglist` `t1` join `projectstage` `t2`) where `t1`.`newstage` = `t2`.`stageno` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_project_team`
--

/*!50001 DROP VIEW IF EXISTS `view_project_team`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_project_team` AS select `t1`.`project_id` AS `project_id`,`t1`.`user_id` AS `user_id`,`t1`.`roles_id` AS `roles_id`,`t3`.`fullname` AS `fullname`,`t3`.`loginname` AS `loginname`,`t2`.`role` AS `role` from ((`roleinproject` `t1` join `users` `t3`) join `projectsrole` `t2`) where `t1`.`user_id` = `t3`.`id` and `t1`.`active` > 0 and `t3`.`active` > 0 and `t1`.`roles_id` = `t2`.`id` order by `t3`.`fullname` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_project_timesheet_sum`
--

/*!50001 DROP VIEW IF EXISTS `view_project_timesheet_sum`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_project_timesheet_sum` AS select `t1`.`project_id` AS `pid`,date_format(`t1`.`dt`,'%Y-%m') AS `month`,sum(`t1`.`no_of_hours`) * 60 + sum(`t1`.`no_of_min`) AS `total_min`,`t1`.`quality` AS `quality` from `timesheet` `t1` where `t1`.`active` > 0 group by `t1`.`project_id`,date_format(`t1`.`dt`,'%Y-%m'),`t1`.`quality` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_projects`
--

/*!50001 DROP VIEW IF EXISTS `view_projects`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_projects` AS select `t1`.`id` AS `pid`,`t1`.`projectname` AS `projectname`,`t1`.`jobcode` AS `jobcode`,`t1`.`branch_id` AS `branch_id`,`t2`.`branchname` AS `branchname`,`t1`.`active` AS `active`,`t3`.`user_id` AS `projectleader_id`,`t4`.`fullname` AS `projectleader`,`t5`.`corporatename` AS `corporatename`,`t1`.`domain_id` AS `domain_id`,`t1`.`contractdt` AS `contractdt`,`t1`.`contract_period_years` AS `contract_period_years`,`t1`.`contract_period_months` AS `contract_period_months`,`t1`.`escalation_kickoff` AS `escalation_kickoff`,`t1`.`escalationdt_start` AS `escalationdt_start`,`t1`.`escalation_rate` AS `escalation_rate`,`t1`.`escalation_note` AS `escalation_note` from ((((`projects` `t1` join `branch` `t2`) join `roleinproject` `t3`) join `users` `t4`) join `domain` `t5`) where `t1`.`branch_id` = `t2`.`id` and `t1`.`id` = `t3`.`project_id` and `t3`.`user_id` = `t4`.`id` and `t1`.`domain_id` = `t5`.`id` and `t3`.`roles_id` = 10 and `t1`.`domain_id` = 2 order by `t1`.`jobcode` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_task_allocation`
--

/*!50001 DROP VIEW IF EXISTS `view_task_allocation`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_task_allocation` AS select `t1`.`task_id` AS `task_id`,`t1`.`user_id` AS `user_id`,`t2`.`loginname` AS `loginname`,`t2`.`fullname` AS `fullname` from (`taskallocation` `t1` join `users` `t2`) where `t1`.`user_id` = `t2`.`id` and `t1`.`active` = 1 and `t2`.`active` = 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_tasks`
--

/*!50001 DROP VIEW IF EXISTS `view_tasks`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_tasks` AS select `t1`.`id` AS `task_id`,`t1`.`project_id` AS `project_id`,`t2`.`projectname` AS `projectname`,`t1`.`work` AS `work`,`t1`.`projectscope_id` AS `scope_id`,`t3`.`description` AS `scope`,`t3`.`scope` AS `scope_sn`,concat(`t3`.`scope`,' - ',`t3`.`description`) AS `scope_with_id`,`t1`.`projectstage_id` AS `stage_id`,`t4`.`name` AS `stage`,`t4`.`sname` AS `stage_sn`,concat(`t4`.`sname`,' - ',`t4`.`name`) AS `stage_with_id`,`t1`.`displayorder` AS `displayorder`,`t1`.`active` AS `active`,`t1`.`allocation_flag` AS `allocation_flag`,if(`t1`.`allocation_flag` < 1,'Team','Individual(s)') AS `allocated_to`,`t1`.`status_last_month` AS `status_last_month`,`t1`.`status_this_month_target` AS `status_this_month_target`,`t1`.`mandays` AS `mandays`,`t1`.`manhours` AS `manhours`,if(`t1`.`manminutes` < 10,concat('0',`t1`.`manminutes`),`t1`.`manminutes`) AS `manminutes`,`t1`.`onhold` AS `onhold`,`t1`.`dt` AS `dt`,date_format(`t1`.`dt`,'%b %y') AS `dt_month`,`t1`.`cm_date_flag` AS `cm_date_flag`,`t1`.`cm_allotted_mh` AS `cm_allotted_mh`,`t1`.`lm_allotted_mh` AS `lm_allotted_mh`,`t1`.`cm_added_mh` AS `cm_added_mh` from (((`task` `t1` join `projects` `t2`) join `projectscope` `t3`) join `projectstage` `t4`) where `t1`.`project_id` = `t2`.`id` and `t1`.`projectscope_id` = `t3`.`id` and `t1`.`projectstage_id` = `t4`.`id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_tasks_my`
--

/*!50001 DROP VIEW IF EXISTS `view_tasks_my`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_tasks_my` AS select `t1`.`user_id` AS `user_id`,`t1`.`fullname` AS `fullname`,`t2`.`task_id` AS `task_id`,`t2`.`project_id` AS `project_id`,`t2`.`projectname` AS `projectname`,`t2`.`work` AS `work`,`t2`.`scope_id` AS `scope_id`,`t2`.`scope` AS `scope`,`t2`.`scope_sn` AS `scope_sn`,`t2`.`scope_with_id` AS `scope_with_id`,`t2`.`stage_id` AS `stage_id`,`t2`.`stage` AS `stage`,`t2`.`stage_sn` AS `stage_sn`,`t2`.`stage_with_id` AS `stage_with_id`,`t2`.`displayorder` AS `displayorder`,`t2`.`active` AS `active`,`t2`.`allocation_flag` AS `allocation_flag`,`t2`.`onhold` AS `onhold` from (`view_task_allocation` `t1` join `view_tasks` `t2`) where `t1`.`task_id` = `t2`.`task_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_timesheets`
--

/*!50001 DROP VIEW IF EXISTS `view_timesheets`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_timesheets` AS select `t1`.`user_id` AS `user_id`,`t1`.`id` AS `timesheet_id`,`t1`.`project_id` AS `project_id`,`t1`.`projectstage_id` AS `projectstage_id`,`t1`.`task_id` AS `task_id`,`t1`.`no_of_hours` AS `no_of_hours`,`t1`.`no_of_min` AS `no_of_min`,`t1`.`work` AS `work`,`t1`.`approved` AS `approved`,`t1`.`quality` AS `quality`,`t1`.`tmstamp` AS `tmstamp`,`t1`.`dt` AS `dtmysql`,date_format(`t1`.`dt`,'%a') AS `dow`,date_format(`t1`.`dt`,'%d-%b-%y') AS `date`,date_format(`t1`.`dt`,'%Y-%m') AS `month`,`t1`.`worked_from` AS `worked_from`,`t3`.`name` AS `stage`,`t4`.`projectname` AS `projectname`,`t4`.`jobcode` AS `jobcode`,`t3`.`sname` AS `sname`,`t1`.`percent` AS `percent`,`t5`.`id` AS `scope_id`,`t5`.`scope` AS `scope`,`t5`.`description` AS `scope_name`,`t1`.`pm_review_flag` AS `pm_review_flag` from (((`timesheet` `t1` join `projectstage` `t3`) join `projects` `t4`) join `projectscope` `t5`) where `t1`.`active` > 0 and `t1`.`projectstage_id` = `t3`.`id` and `t1`.`project_id` = `t4`.`id` and `t1`.`projectscope_id` = `t5`.`id` order by `t1`.`id` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_users`
--

/*!50001 DROP VIEW IF EXISTS `view_users`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_users` AS select `t1`.`id` AS `user_id`,`t1`.`domain_id` AS `domain_id`,`t1`.`fullname` AS `displayname`,`t1`.`loginname` AS `loginname`,`t2`.`employee_code` AS `employee_code`,`t2`.`employment_type` AS `employment_type`,`t2`.`fname` AS `fname`,`t2`.`mname` AS `mname`,`t2`.`lname` AS `lname`,if(`t2`.`dob` > '1900-01-01',`t2`.`dob`,'NA') AS `dob`,if(`t2`.`dob` > '1900-01-01',date_format(`t2`.`dob`,'%d-%b-%y'),'NA') AS `dateofbirth`,`t2`.`mobile` AS `mobile`,`t2`.`bloodgroup` AS `bloodgroup`,concat(`t1`.`loginname`,'@',`t7`.`domainname`) AS `emailid`,`t2`.`gender` AS `gender`,`t2`.`department_id` AS `department_id`,`t4`.`name` AS `departmentname`,`t2`.`branch_id` AS `branch_id`,`t3`.`branchname` AS `branchname`,`t2`.`designation` AS `designation`,`t2`.`userhrgroup_id` AS `userhrgroup_id`,`t6`.`name` AS `hrgroup`,`t2`.`reports_to_user_id` AS `reports_to_user_id`,`t5`.`fullname` AS `reports_to`,`t2`.`avatar` AS `avatar`,`t2`.`salary` AS `salary`,`t2`.`incentives` AS `incentives`,`t2`.`hourly_rate` AS `hourly_rate`,if(`t2`.`dt_of_joining` > '1900-01-01',date_format(`t2`.`dt_of_joining`,'%d-%b-%y'),'NA') AS `doj`,`t2`.`dt_of_joining` AS `dt_doj`,if(`t2`.`dt_of_termination` < '2050-01-01',date_format(`t2`.`dt_of_termination`,'%d-%b-%y'),'NA') AS `doe`,`t2`.`dt_of_termination` AS `dt_doe`,`t2`.`aadhaar` AS `aadhaar`,`t2`.`pan_no` AS `pan_no`,`t2`.`bank_name` AS `bank_name`,`t2`.`bank_account_no` AS `bank_account_no`,`t2`.`bank_account_ifsc` AS `bank_account_ifsc`,`t2`.`pf_no` AS `pf_no`,`t2`.`esi_no` AS `esi_no`,`t2`.`uan_no` AS `uan_no`,`t2`.`emergency_contact_name` AS `emergency_contact_name`,`t2`.`emergency_contact_phone` AS `emergency_contact_phone`,`t2`.`status` AS `status`,`t2`.`email_personal` AS `email_personal`,`t2`.`appraisal_link` AS `appraisal_link`,`t1`.`active` AS `active` from ((((((`users` `t1` join `users_a` `t2`) join `branch` `t3`) join `department` `t4`) join `users` `t5`) join `userhrgroup` `t6`) join `domain` `t7`) where `t1`.`id` = `t2`.`user_id` and `t2`.`branch_id` = `t3`.`id` and `t2`.`department_id` = `t4`.`id` and `t2`.`reports_to_user_id` = `t5`.`id` and `t2`.`userhrgroup_id` = `t6`.`id` and `t1`.`domain_id` = `t7`.`id` and `t1`.`domain_id` = 2 order by `t1`.`fullname` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-23 15:30:59

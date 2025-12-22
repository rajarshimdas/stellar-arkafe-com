/* Alter Sessions table */
ALTER TABLE `vasmsdb`.`sessi0ns` 
 CHANGE COLUMN `usercode` `userpreference` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

/* Alter dwglist Table - default values of columns */
ALTER TABLE `vasmsdb`.`dwglist` MODIFY COLUMN `scaleina1` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `remark` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `priority` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `r0issuedt` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `lastissuedrevno` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `lastissueddate` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `active` TINYINT(1) NOT NULL DEFAULT 1;


/* Alter dwghistory Table - default values of columns */
ALTER TABLE `vasmsdb`.`dwghistory` MODIFY COLUMN `newrevno` TINYINT(1) NOT NULL DEFAULT 0,
 MODIFY COLUMN `revno` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `newdwglistver` TINYINT(1) NOT NULL DEFAULT 0,
 MODIFY COLUMN `olddwglistver` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `title` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `remark` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `scaleina1` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `lastissuedrevno` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `lastissueddt` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `newstg` TINYINT(1) NOT NULL DEFAULT 0,
 MODIFY COLUMN `newstage` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
 MODIFY COLUMN `newstgreason` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `newr0dt` TINYINT(1) NOT NULL DEFAULT 0,
 MODIFY COLUMN `r0newdt` DATE NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `r0reason` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `loginname` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
 MODIFY COLUMN `dtime` DATETIME NOT NULL DEFAULT '0000-00-00',
 MODIFY COLUMN `active` TINYINT(1) NOT NULL DEFAULT 0;

/* Create a select only user */
GRANT select 
on vasmsdb.* 
to 'angels'@'localhost' 
identified by 'are+all+vidhyakunj+girls+of+92+batch' 
with grant option;
flush privileges;
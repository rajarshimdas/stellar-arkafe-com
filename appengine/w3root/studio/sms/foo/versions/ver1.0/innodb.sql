# Rajarshi Das
# 
/* Change all tables (except sessi0ns & haystack) to InnoDB */
ALTER TABLE `vasmsdb`.`blocks` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`daemons` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`discipline` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`haystack` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`roleinproject` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`roles` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`sketches` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`transadd` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`transitems` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`transname` ENGINE = InnoDB;
ALTER TABLE `vasmsdb`.`users` ENGINE = InnoDB;

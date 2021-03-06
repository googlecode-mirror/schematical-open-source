<?php
SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `ContestEntry` (" .
  "`idContestEntry` int(11) NOT NULL AUTO_INCREMENT," .
  "`idApp` int(11) DEFAULT NULL," .
  "`Fbuid` varchar(32) DEFAULT NULL," .
  "`creDate` datetime DEFAULT NULL," .
  "`delDate` datetime DEFAULT NULL," .
  "PRIMARY KEY (`idContestEntry`)," .
  "KEY `idApp` (`idApp`)" .
  ") ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;");

SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `ContestFormFieldType` (" .
  "`idContestFormFieldType` int(11) NOT NULL AUTO_INCREMENT," .
  "`shortDesc` varchar(32) DEFAULT NULL," .
  "PRIMARY KEY (`idContestFormFieldType`)" .
  ") ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;");
$intRowCount = mysql_num_rows(SApplication::ExicuteOnDB("SELECT * FROM `ContestFormFieldType`;"));

if($intRowCount == 0){
	SApplication::ExicuteOnDB("INSERT INTO `ContestFormFieldType` VALUES ('1', 'text');");
	SApplication::ExicuteOnDB("INSERT INTO `ContestFormFieldType` VALUES ('2', 'longText');");
	SApplication::ExicuteOnDB("INSERT INTO `ContestFormFieldType` VALUES ('3', 'upload');");
	SApplication::ExicuteOnDB("INSERT INTO `ContestFormFieldType` VALUES ('4', 'youtube');");
	SApplication::ExicuteOnDB("INSERT INTO `ContestFormFieldType` VALUES ('5', 'select');");
	
}
SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `ContestFormAnswer` (" .
  "`idFormAnswer` int(11) NOT NULL AUTO_INCREMENT," .
  "`idContestEntry` int(11) DEFAULT NULL," .
  "`idContestFormFieldType` int(11) DEFAULT NULL," .
  "`name` varchar(32) DEFAULT NULL," .
  "`value` longtext," .
  "PRIMARY KEY (`idFormAnswer`)," .
  "KEY `fk_ContestFormAnswer_idContestEntry` (`idContestEntry`)," .
  "KEY `fk_ContestFormAnswer_idContestFormFieldType` (`idContestFormFieldType`)," .
  "CONSTRAINT `fk_ContestFormAnswer_idContestEntry` FOREIGN KEY (`idContestEntry`) REFERENCES `ContestEntry` (`idContestEntry`) ON DELETE NO ACTION ON UPDATE NO ACTION," .
  "CONSTRAINT `fk_ContestFormAnswer_idContestFormFieldType` FOREIGN KEY (`idContestFormFieldType`) REFERENCES `ContestFormFieldType` (`idContestFormFieldType`) ON DELETE NO ACTION ON UPDATE NO ACTION" .
  ") ENGINE=InnoDB AUTO_INCREMENT=856 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 6144 kB; (`idContestFormField`) REFER `MFB/Cont';");

<?php


SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `FeedPost` (" .
  "`idFeedPost` int(11) NOT NULL AUTO_INCREMENT," .
  "`idApp` int(11) DEFAULT NULL," .
  "`senderFbuid` varchar(32) DEFAULT NULL," .
  "`reciverFbuid` varchar(32) DEFAULT NULL," .
  "`data` longtext," .
  "`creDate` datetime DEFAULT NULL," .
  "`tag` varchar(32) DEFAULT NULL," .
  "PRIMARY KEY (`idFeedPost`)," .
  "KEY `fk_FeedPost_idApp` (`idApp`)" .
  ") ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;");

SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `App` (" .
  "`idApp` int(11) NOT NULL auto_increment," .
  "`name` varchar(128) default NULL," .
  "`creDate` datetime default NULL," .
  "PRIMARY KEY  (`idApp`)" .
  ") ENGINE=MyISAM DEFAULT CHARSET=latin1;");

SApplication::ExicuteOnDB("CREATE TABLE IF NOT EXISTS `Attachment` (" .
  "`idAttachment` int(11) NOT NULL auto_increment," .
  "`shortDesc` varchar(128) default NULL," .
  "`fileLoc` varchar(256) default NULL," .
  "`creDate` datetime default NULL," .
  "`fbuid` varchar(128) default NULL," .
  "PRIMARY KEY  (`idAttachment`)" .
  ") ENGINE=MyISAM DEFAULT CHARSET=latin1;");
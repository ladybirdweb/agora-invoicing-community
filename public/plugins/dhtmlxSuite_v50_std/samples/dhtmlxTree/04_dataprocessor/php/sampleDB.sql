DROP TABLE IF EXISTS `samples_tree`;
CREATE TABLE `samples_tree` (
  `item_id` int(10) unsigned NOT NULL auto_increment,
  `item_nm` varchar(200) default '0',
  `item_order` int(10) unsigned default '0',
  `item_desc` text,
  `item_parent_id` int(10) unsigned default '0',
  `GUID` varchar(50),
  `SYS_TS` TIMESTAMP default CURRENT_TIMESTAMP(),
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Table structure for table `xdir_coupon`
#

CREATE TABLE `xdir_coupon` (
  `couponid` int(12) unsigned NOT NULL auto_increment,
  `lid` int(11) unsigned NOT NULL default '0',
  `description` text NOT NULL,
  `image` text NOT NULL,
  `publish` int(10) unsigned NOT NULL default '0',
  `expire` int(10) unsigned NOT NULL default '0',
  `heading` text NOT NULL,
  `lbr` int(1) NOT NULL default '0',
  `counter` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`couponid`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Alter Table structure for table `xdir_links`
#

ALTER TABLE `xdir_links` ADD `cidalt1` INT( 5 ) UNSIGNED NOT NULL AFTER `cid` ,
ADD `cidalt2` INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt1` ,
ADD `cidalt3` INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt2` ,
ADD `cidalt4` INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt3` ;
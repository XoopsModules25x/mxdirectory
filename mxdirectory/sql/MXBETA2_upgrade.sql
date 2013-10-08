# Alter Table structure for table `xdir_links`
#

ALTER TABLE `xdir_links` ADD `mfhrs` varchar(20) NOT NULL default '' AFTER 'country',  
ADD `sathrs` varchar(20) NOT NULL default '' AFTER 'mfhrs',
ADD `sunhrs` varchar(20) NOT NULL default '' AFTER 'sathrs',
ADD `mobile` varchar(35) NOT NULL default '' AFTER 'fax', 
ADD `home` varchar(35) NOT NULL default '' AFTER 'mobile',
ADD `tollfree` varchar(35) NOT NULL default '' AFTER 'home',
ADD `admcontname` varchar(35) NOT NULL default '' AFTER 'url',
ADD `admcontnumb` varchar(35) NOT NULL default '' AFTER 'admcontname';

# --------------------------------------------------------

#
# Alter Table structure for table `xdir_mod`
#

ALTER TABLE `xdir_mod` ADD `mfhrs` varchar(20) NOT NULL default '' AFTER 'country',  
ADD `sathrs` varchar(20) NOT NULL default '' AFTER 'mfhrs',
ADD `sunhrs` varchar(20) NOT NULL default '' AFTER 'sathrs',
ADD `mobile` varchar(35) NOT NULL default '' AFTER 'fax', 
ADD `home` varchar(35) NOT NULL default '' AFTER 'mobile',
ADD `tollfree` varchar(35) NOT NULL default '' AFTER 'home',
ADD `admcontname` varchar(35) NOT NULL default '' AFTER 'url',
ADD `admcontnumb` varchar(35) NOT NULL default '' AFTER 'admcontname';
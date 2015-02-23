Everything in this folder can be deleted after install or upgrade has completed successfully.

Use the upgrade script (see upgrade.txt in module root) to upgrade from x-directory or a previous mx-directory version.  The files in this folder are for reference and custom modification purposes.

---------------------------------RC1:-----------------------------------------
 In addition to Beta 2 info below, The MySQL.SQL file contains additional fields in the xdir_link & the xdir_mod table (admcontname, admcontnumb, mfhrs, sathrs, sunhrs, mobile, home, tollfree).

This query (in xoops form) IS included in the Upgrade.php script in the admin directory and unless you comment it out will assign all of the old default links to the new configurable default category. Here is a mysql query which will change all of the old default links to the new configurable default category.  If you do not update your databse so that no listings have the premium value of 0, you may encounter problems.

UPDATE `YOURTABLENAME_xdir_links` SET `premium` = '1' WHERE `premium` = '0'

----------------------------------BETA2 INFO-----------------------------------
BETA2:The MySQL.SQL file contains additional fields in the xdir_link table (cidalt1, cidalt2, cidalt3, cidalt4) as well as a new xdir_coupon table. 

The MXDIR_cats.sql file contains a baseline directory structure which may be manually inserted into the xdir_cats table via php MyAdmin.  Doing so may delete your current categories if you have an existing xdirectory installation.  This file is provided as a jumpstart for creating a directory.

This beta uses an Upgrade to handle migration from x-directory without loosing any categories or data. See readme1st.txt for upgrade information.
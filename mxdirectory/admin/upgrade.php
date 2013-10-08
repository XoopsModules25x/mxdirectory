<?php
// $Id: upgrade.php 11970 2013-08-24 14:20:57Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include_once '../../../include/cp_header.php';
xoops_cp_header();
//$mydirname = $xoopsModule->getVar('dirname');
$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$updatedirname = basename( dirname( dirname( __FILE__ ) ) ) ;
include_once XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/include/functions.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
	include "../language/".$xoopsConfig['language']."/main.php";
} else {
	include "../language/english/main.php";
}

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid()))
{
	$errors=0;
// 1) Create the xdir_coupon table IFF it does not exist 
	if(!TableExists($xoopsDB->prefix('xdir_coupon')))
	{
		$sql = "CREATE TABLE ".$xoopsDB->prefix('xdir_coupon')." (
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
		) TYPE=MyISAM";
		if (!$xoopsDB->queryF($sql)) {
            echo '<br />' . _MD_MXDIR_UPGRADEFAILED.' '._MD_MXDIR_UPGRADEFAILED1;
	    	$errors++;
		}
	}

// 2) Add the new fields to the xdir_links table
	if (!FieldExists('cidalt1',$xoopsDB->prefix('xdir_links'))) {
		AddField("cidalt1 INT( 5 ) UNSIGNED NOT NULL AFTER `cid`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('cidalt2',$xoopsDB->prefix('xdir_links'))) {
		AddField("cidalt2 INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt1`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('cidalt3',$xoopsDB->prefix('xdir_links'))) {
		AddField("cidalt3 INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt2`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('cidalt4',$xoopsDB->prefix('xdir_links'))) {
		AddField("cidalt4 INT( 5 ) UNSIGNED NOT NULL AFTER `cidalt3`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('mfhrs',$xoopsDB->prefix('xdir_links'))) {
		AddField("mfhrs VARCHAR( 20 ) NOT NULL AFTER `country`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('sathrs',$xoopsDB->prefix('xdir_links'))) {
		AddField("sathrs VARCHAR( 20 ) NOT NULL AFTER `mfhrs`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('sunhrs',$xoopsDB->prefix('xdir_links'))) {
		AddField("sunhrs VARCHAR( 20 ) NOT NULL AFTER `sathrs`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('mobile',$xoopsDB->prefix('xdir_links'))) {
		AddField("mobile VARCHAR( 35 ) NOT NULL AFTER `fax`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('home',$xoopsDB->prefix('xdir_links'))) {
		AddField("home VARCHAR( 35 )  NOT NULL AFTER `mobile`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('tollfree',$xoopsDB->prefix('xdir_links'))) {
		AddField("tollfree VARCHAR( 35 ) NOT NULL AFTER `home`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('admcontname',$xoopsDB->prefix('xdir_links'))) {
		AddField("admcontname VARCHAR( 35 ) NOT NULL AFTER `url`",$xoopsDB->prefix('xdir_links'));
	}
	if (!FieldExists('admcontnumb',$xoopsDB->prefix('xdir_links'))) {
		AddField("admcontnumb VARCHAR( 35 ) NOT NULL AFTER `admcontname`",$xoopsDB->prefix('xdir_links'));
	}
	
// 3) Add the new cidalt fields to the xdir_mod table	
	if (!FieldExists('mfhrs',$xoopsDB->prefix('xdir_mod'))) {
		AddField("mfhrs VARCHAR( 15 ) NOT NULL AFTER `country`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('sathrs',$xoopsDB->prefix('xdir_mod'))) {
		AddField("sathrs VARCHAR( 15 ) NOT NULL AFTER `mfhrs`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('sunhrs',$xoopsDB->prefix('xdir_mod'))) {
		AddField("sunhrs VARCHAR( 15 ) NOT NULL AFTER `sathrs`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('mobile',$xoopsDB->prefix('xdir_mod'))) {
		AddField("mobile VARCHAR( 35 ) NOT NULL AFTER `fax`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('home',$xoopsDB->prefix('xdir_mod'))) {
		AddField("home VARCHAR( 35 )  NOT NULL AFTER `mobile`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('tollfree',$xoopsDB->prefix('xdir_mod'))) {
		AddField("tollfree VARCHAR( 35 ) NOT NULL AFTER `home`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('admcontname',$xoopsDB->prefix('xdir_mod'))) {
		AddField("admcontname VARCHAR( 35 ) NOT NULL AFTER `url`",$xoopsDB->prefix('xdir_mod'));
	}
	if (!FieldExists('admcontnumb',$xoopsDB->prefix('xdir_mod'))) {
		AddField("admcontnumb VARCHAR( 35 ) NOT NULL AFTER `admcontname`",$xoopsDB->prefix('xdir_mod'));
	}

	//Change all OLD Default Category (premium lelvel 0) Listings to the new default category (premium lelvel 1) via premium key (0->1)
	if (FieldExists('premium',$xoopsDB->prefix('xdir_links'))) {
		UpdateaField("`premium`","0","1",$xoopsDB->prefix('xdir_links'));
	}	
    // At the end, if there were errors, show them or redirect user to the module's upgrade page
	if($errors) {
        echo "<H1>" . _MD_MXDIR_UPGRADEFAILED . "</H1>";
      		echo "<br />" . _MD_MXDIR_UPGRADEFAILED0;
      	} else {
      		echo _MD_MXDIR_UPGRADECOMPLETE." - <a href='".XOOPS_URL."/modules/system/admin.php?fct=modulesadmin&op=update&module=" .$updatedirname. "'>"._MD_MXDIR_UPDATEMODULE."</a>";
      	}
      } else {
	printf("<H2>%s</H2>\n",_MD_MXDIR_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();

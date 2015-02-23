<?php
// $Id: notification.inc.php,v 1.2 2003/03/30 01:51:59 mvandam Exp $
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
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //

function xdir_notify_iteminfo($category, $item_id)
{
	global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
	$mydirname = (basename ( dirname ( dirname( __FILE__ ) ) , "a" ) ) ;

	if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != $mydirname) {	
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($mydirname);
		$config_handler =& xoops_gethandler('config');
		$config =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
	} else {
		$module =& $xoopsModule;
		$config =& $xoopsModuleConfig;
	}

	//include_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/main.php';

	global $xoopsDB;
	$item = array();
	
	switch ($category) {
		case 'global':
			$item['name'] = '';
			$item['url'] = '';
			break;
		case 'category':
			// Assume we have a valid category id
			$sql = 'SELECT title FROM ' . $xoopsDB->prefix('xdir_cat') . ' WHERE cid = '.$item_id;
			$result = $xoopsDB->query($sql); // TODO: error check
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['title'];
			$item['url'] = XOOPS_URL . '/modules/' . $mydirname . '/viewcat.php?cid=' . $item_id;
			break;
		case 'link':
			// Assume we have a valid link id
			$sql = 'SELECT cid,title FROM '.$xoopsDB->prefix('xdir_links') . ' WHERE lid = ' . $item_id;
			$result = $xoopsDB->query($sql); // TODO: error check
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['title'];
			$item['url'] = XOOPS_URL . '/modules/' . $mydirname . '/singlelink.php?cid=' . $result_array['cid'] . '&amp;lid=' . $item_id;
			break;
		default:
	}
	return $item;
}
?>
<?php
// $Id: menu.php 11970 2013-08-24 14:20:57Z beckmi $
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


defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
$i++;

$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU1;
$adminmenu[$i]['link'] = "admin/main.php?op=xdir";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU9;
$adminmenu[$i]['link'] = "admin/main.php?op=multicat";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU2;
$adminmenu[$i]['link'] = "admin/main.php?op=linksConfigMenu";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/view_text.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU6;
$adminmenu[$i]['link'] = "admin/coupon.php?op=menu";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/discount.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU3;
$adminmenu[$i]['link'] = "admin/main.php?op=listNewLinks";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/submittedlink.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU4;
$adminmenu[$i]['link'] = "admin/main.php?op=listBrokenLinks";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/brokenlink.png';
$i++;
$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU5;
$adminmenu[$i]['link'] = "admin/main.php?op=listModReq";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/modifiedlink.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_MXDIR_ADMENU10;
//$adminmenu[$i]['link'] = "admin/myblocksadmin.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';

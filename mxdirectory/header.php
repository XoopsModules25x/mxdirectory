<?php
// $Id: header.php 11970 2013-08-24 14:20:57Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
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

include_once "../../mainfile.php";

//$whatdir = basename ( dirname( __FILE__ ) ) ;
//if (preg_match("#[\]#", $whatdir)) {
//	$split = preg_split('/[^\]/',$whatdir);
//} else {
//	$split = preg_split('/[^/]/',$whatdir);
//}
//$count = count($split) - 1;
//$mydirname = $split[$count];

$mydirname = basename ( dirname( __FILE__ ) ) ;

include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/include/functions.php";

$xoops_module_header = '<link rel="stylesheet" type="text/css" href="'.XOOPS_URL.'/modules/'.$mydirname.'/images/style.css" />';

if(empty($xoopsModuleConfig['rss_enable'])){
	$xoops_module_header .= '
	<link rel="alternate" type="application/rss+xml" title="'.$mydirname.'" href="'.XOOPS_URL.'/modules/'.$mydirname.'/mxdir_rss.php" />
	';
}
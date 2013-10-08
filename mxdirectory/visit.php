<?php
// $Id: visit.php,v 1.6 2003/03/25 11:08:22 buennagel Exp $
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
include '../../mainfile.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$lid = intval($_GET['lid']);
$cid = intval($_GET['cid']);
$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("xdir_links"), $lid);
$xoopsDB->queryF($sql);
$result = $xoopsDB->query("select url from ".$xoopsDB->prefix("xdir_links")." where lid=$lid and status>0");
list($url) = $xoopsDB->fetchRow($result);

if ( $xoopsModuleConfig['frame'] != "" ) {
	header('Content-Type:text/html; charset='._CHARSET);
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	echo "<html><head>
		<title>".$xoopsConfig['sitename']."</title>
		</head>
		<frameset rows='70px,100%' cols='*' border='0' frameborder='0' framespacing='0' >
		<frame src='myheader.php?url=$url&amp;cid=$cid&amp;lid=$lid' frame name='xoopshead' scrolling='no' target='main' Noresize>
		<frame src='".$myts->oopsHtmlSpecialChars($url)."' frame name='main' scrolling='auto' target='Main'>
		</frameset></html>";
} else {
	echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$myts->oopsHtmlSpecialChars($url)."\"></meta></head><body></body></html>";
}
exit();

?>

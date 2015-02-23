<?php
// $Id: index.php,v 1.12 2003/03/27 12:11:06 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //
include "../../mainfile.php";
include "header.php";
$mydirname = basename( dirname( __FILE__ ) ) ;
//added global to test coupon under 2.2
global $xoopsDB;
include_once "class/coupon.php";

$lid = isset($_GET['lid']) ? $_GET['lid'] : 0;
$cid = isset($_GET['cid']) ? $_GET['cid'] : 0;

$xoopsOption['template_main'] = 'xdir_savings.html';
include XOOPS_ROOT_PATH."/header.php";

$coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

if ($lid) {
    $categories = $coupon_handler->getByLink($lid);
}
else {
    $categories = $coupon_handler->getByCategory($cid);
}
$xoopsTpl->assign('categories', $coupon_handler->prepare2show($categories));
if ($xoopsUser) {
    $xoopsTpl->assign('admin', $xoopsUser->isAdmin($xoopsModule->mid()));
}
	//Smarty directory autodetect
	$xoopsTpl->assign('smartydir', $mydirname);

include XOOPS_ROOT_PATH.'/footer.php';
?>
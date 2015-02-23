<?php
// $Id: print.php,v 1.6 2004/05/25 08:20:00 hthouzard Exp $
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

include '../../mainfile.php';
include XOOPS_ROOT_PATH."/header.php";
$mydirname = basename( dirname( __FILE__ ) ) ;
global $xoopsTpl;
$coupid = isset($_GET['coupid']) ? intval($_GET['coupid']) : 0;
if (!($coupid > 0) ) {
    redirect_header("index.php");
}

function PrintPage($coupid)
{
    global $xoopsModule, $xoopsTpl, $xoopsModuleConfig, $mydirname;
    include "./class/coupon.php";
    $coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);

//    $coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
    $coupon_handler->increment($coupid);
    $coupon = $coupon_handler->getLinkedCoupon($coupid);
    $coupon_arr = $coupon_handler->prepare2show($coupon);
    $xoopsTpl->assign('coupon_footer', $xoopsModuleConfig['coupon_footer']);
    $xoopsTpl->assign('coupon', $coupon_arr[$coupon[0]['cid']]['coupons'][0]);
//    $xoopsTpl->template_dir = XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname();
    $xoopsTpl->display('db:xdir_print_savings.html');
}
	//Smarty directory autodetect
	$smartydir = $mydirname;
	$xoopsTpl->assign('smartydir', $smartydir);
PrintPage($coupid);
?>
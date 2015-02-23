<?php
// $Id: addcoupon.php 11970 2013-08-24 14:20:57Z beckmi $
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
global $xoopsDB;
include "header.php";
include "./class/coupon.php";
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

$couponid = isset($_GET['couponid']) ? intval($_GET['couponid']) : 0;

if ($couponid > 0) {

    $coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
    $coupon =& $coupon_handler->get($couponid);


//    $coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
//    $coupon =& $coupon_handler->get($couponid);
    $lid = $coupon->getVar('lid');
//    $myts =& MyTextSanitizer::getInstance();
    $lbr = $coupon->getVar('lbr');
    $coupon->setVar('dohtml', 1);
    $coupon->setVar('dobr', $lbr);
    $descr = $coupon->getVar('description', 'E');
    $image = $coupon->getVar('image', 'E');
	  $heading = $coupon->getVar('heading', 'E');
    $publish = $coupon->getVar('publish') > 0 ? $coupon->getVar('publish') : time();
    $expire = $coupon->getVar('expire');
    if ($expire > 0) {
        $setexpire = 1;
    }
    else {
        $setexpire = 0;
        $expire = time() + 3600 * 24 * 7;
    }
}
else {
    $lid = isset($_POST['lid']) ? intval($_POST['lid']) : (isset($_GET['lid']) ? intval($_GET['lid']) : 0);
    $couponid = isset($_POST['couponid']) ? $_POST['couponid'] : null;
    $descr = isset($_POST['descr']) ? $_POST['descr'] : "";
    $publish = isset($_POST['publish']) ? $_POST['publish'] : 0;
    $image = isset($_POST['image']) ? $_POST['image'] : "";
		$expire = isset($_POST['expire']) ? $_POST['expire'] : 0;
    $heading = isset($_POST['heading']) ? $_POST['heading'] : "";
    $lbr = isset($_POST['lbr']) ? $lbr : 0;
    if ($expire > 0) {
        $setexpire = 1;
    }
    else {
        $setexpire = 0;
        $expire = time() + 3600 * 24 * 7;
    }
    
}

if ((empty($xoopsUser)) || !$xoopsUser->isAdmin($xoopsModule->mid()) || ($lid == 0 && empty($_POST['delete']))) {
    redirect_header('index.php', 3, _NOPERM);
    exit();
}

if (!empty($_POST['submit'])) {
    $coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
//    $coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
    if (isset($_POST['couponid'])) {
        $thiscoupon =& $coupon_handler->get($_POST['couponid']);
        $message = _MD_MXDIR_COUPONEDITED;
    }
    else {
        $thiscoupon =& $coupon_handler->create();
        $message = _MD_MXDIR_COUPONADDED;
    }
    $thiscoupon->setVar('description', $_POST['descr']);
    $thiscoupon->setVar('image', $_POST['image']);
    $thiscoupon->setVar('lid', $_POST['lid']);
    $thiscoupon->setVar('publish', strtotime($_POST['publish']['date']) + $_POST['publish']['time']);
    $lbr = isset($_POST['lbr']) ? $lbr : 0;
    $thiscoupon->setVar('lbr', $lbr);
    if (isset($_POST['expire_enable']) && ($_POST['expire_enable'] == 1)) {
        $thiscoupon->setVar('expire', strtotime($_POST['expire']['date']) + $_POST['expire']['time']);
    }
    else {
        $thiscoupon->setVar('expire', 0);
    }
    $thiscoupon->setVar('heading', $_POST['heading']);
    if ($coupon_handler->insert($thiscoupon)) {
        redirect_header('singlelink.php?lid='.$thiscoupon->getVar('lid'), 3, $message);
        exit();
    }
}
elseif (!empty($_POST['delete'])) {
    if ( !empty($_POST['ok']) ) {
        if (empty($_POST['couponid'])) {
            redirect_header('index.php',2,"error");
            exit();
        }
        $coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
//        $coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
        $coupon =& $coupon_handler->get($_POST['couponid']);
        $lid = $coupon->getVar('lid');
        if ($coupon_handler->delete($coupon)) {
            redirect_header("singlelink.php?lid=".$lid,3, _MD_MXDIR_COUPONDELETED);
            exit();
        }
    }
    else {
        include XOOPS_ROOT_PATH.'/header.php';
        xoops_confirm(array('delete' => 'yes', 'couponid' => intval($_POST['couponid']), 'ok' => 1), 'addcoupon.php', _MD_MXDIR_COUPONRUSURE);
        include_once XOOPS_ROOT_PATH.'/footer.php';
        exit();
    }
}
include XOOPS_ROOT_PATH.'/header.php';
include 'include/couponform.php';
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
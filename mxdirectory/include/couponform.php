<?php
// $Id: couponform.php 11970 2013-08-24 14:20:57Z beckmi $
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
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";

$uploadirectory = "/uploads/";

$coupform = new XoopsThemeForm(_MD_MXDIR_COUPONFORM, 'couponform', $_SERVER['PHP_SELF'], "POST");
$linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/uploads/");
$coupform->addElement(new XoopsFormHidden('lid', $lid));
$coupform->addElement(new XoopsFormText(_MD_MXDIR_COUPONHEADER, 'heading', 25, 30, $heading), true);
$coupform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC, 'descr', $descr));
$image_option=new XoopsFormSelect(_MD_MXDIR_COUPONIMGMGR."<br />"._MD_MXDIR_COUPONIMG."<br />", 'image', $image);
$image_option->addOptionArray($linkimg_array);
$imgtray = new XoopsFormElementTray(_MD_MXDIR_COUPSEL,'<br />');

$image_option->setExtra("onchange='showImgSelected(\"imagex\", \"image\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
$imgtray->addElement($image_option,false);
$imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='".XOOPS_URL.$uploadirectory . "/" . $image . "' name='imagex' id='imagex' alt='' />" ) );
//$coupform->addElement($image_option);
$coupform->addElement($imgtray);
$coupform->addelement(new XoopsFormRadioYN(_MD_MXDIR_CONVERTLBR, 'lbr', $lbr));
$coupform->addElement(new XoopsFormDateTime(_MD_MXDIR_PUBLISHCOUPON, 'publish', 25, $publish));
$expire_option = new XoopsFormCheckBox(_MD_MXDIR_SETEXPIRATION, 'expire_enable', $setexpire);
$expire_option->addOption(1, _YES);
$coupform->addElement($expire_option);
$coupform->addElement(new XoopsFormDateTime(_MD_MXDIR_EXPIRECOUPON, 'expire', 25, $expire));

$button_tray = new XoopsFormElementTray('');
$button_tray->addElement(new XoopsFormButton('', 'submit', _MD_MXDIR_SUBMIT, 'submit'));

if ($couponid) {
    $button_tray->addElement(new XoopsFormButton('', 'delete', _MD_MXDIR_DELETE, 'submit'));
    $coupform->addElement(new XoopsFormHidden('couponid', $couponid));
}
$coupform->addElement($button_tray);
$coupform->display();

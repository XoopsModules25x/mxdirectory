<?php
// $Id: submit.php 11970 2013-08-24 14:20:57Z beckmi $
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

include "header.php";
include_once XOOPS_ROOT_PATH.'/header.php';

global $xoopsDB, $eh, $xoopsConfig, $xoopsModuleConfig, $xoopsUser;
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include "include/securitycheck.php";
include 'class/formtime.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
$mydirname = basename ( dirname( __FILE__ ) )  ;
$uploadirectory="modules/" . $mydirname. "/images/shots";
$eh = new ErrorHandler; //ErrorHandler object

//$captcha_anon = $xoopsModuleConfig['captcha_anon'];          //check captcha for annon users?

if (empty($xoopsUser) && !$xoopsModuleConfig['anonpost']) {
    redirect_header(XOOPS_URL."/user.php",2,_MD_MXDIR_MUSTREGFIRST);
    exit();
}

if (!empty($_POST['submit'])) {
    // Form posted - evaluate the results & put into the dB for approval
    //xoops security class before captcha eval.
        if (!$GLOBALS['xoopsSecurity']->check()) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
        }
    // next check captcha security
    if (empty($_POST['captcha_stat'] ) ) {
        redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
  }
    switch ($_POST['captcha_stat'] ) {
        case false:
        case 0:
            redirect_header(XOOPS_URL."./",5,_MD_MXDIR_FAIL_GD_LOAD);
            break;
        case 1:
        case 2:
            if (empty($_POST["security"]) || empty($_POST["sec_hidden"])) {
                $eh->show("0008");
            } else {
            // values are set - now verify
                $sec_post = $myts->addSlashes($_POST["security"]);
                $sec_post_hidden = $myts->addSlashes($_POST["sec_hidden"]);
                $spass = mx_security_check($sec_post,$sec_post_hidden);
                if ($spass === false){
                redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
                }
        }
            break;
        default:
            break;
    }
    // end of security graphic validation check

    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

    $notify = !empty($_POST['notify']) ? 1 : 0;
    $cid = ( !empty($_POST['cid']) ) ? intval($_POST['cid']) : 0;
    
    // Check if title is invalid - will only happen if someone tries to spoof the form
    if ( !isset($_POST["title"]) || (trim($_POST["title"])=="") ) {
        redirect_header("./",5,_MD_MXDIR_FIELDEMPTY);
    } elseif ( !isset($_POST["submitter"] ) ) {
        //		$url = $myts->formatURL($_POST["url"]);
        //		$url = urlencode($url);
        redirect_header("./",5,_MD_MXDIR_NOPERM);
    }

    $status = ( $xoopsModuleConfig['autoapprove'] == 1 ) ? 1 : 0 ;

    if (!empty($_FILES["logoup"]["name"])){
            $thislogo = $_FILES["logoup"]["name"];
            $logourl = $myts->addSlashes($thislogo);
    }    elseif(!empty($POST["logourl"])) {
        $logourl = $myts->addSlashes($_POST["logourl"]);
            $logourl = ( basename($logourl) == "0") ? "" : $logourl ;
    } else {
    $logourl = '';
    }
    $title = trim($myts->addSlashes($_POST["title"]));
    $address = trim($myts->addSlashes($_POST["address"]));
    $address2 = trim($myts->addSlashes($_POST["address2"]));
    $city = trim($myts->addSlashes($_POST["city"]));
    $state = trim($myts->addSlashes($_POST["state"]));
    $zip = trim($myts->addSlashes($_POST["zip"]));
    $country = trim($myts->addSlashes($_POST["country"]));
    $mfhrs = trim($myts->addSlashes($_POST["mfhrs"]));
    $sathrs =    trim($myts->addSlashes($_POST["sathrs"]));
    $sunhrs =    trim($myts->addSlashes($_POST["sunhrs"]));
    $phone = trim($myts->addSlashes($_POST["phone"]));
    $fax = trim($myts->addSlashes($_POST["fax"]));
    $mobile = trim($myts->addSlashes($_POST["mobile"]));
    $home = trim($myts->addSlashes($_POST["home"]));
    $tollfree = trim($myts->addSlashes($_POST["tollfree"]));
    $email = trim($myts->addSlashes($_POST["email"]));
    $url = trim($myts->addSlashes($_POST["url"]));
    $admcontname = trim($myts->addSlashes($_POST["admcontname"]));
    $admcontnumb = trim($myts->addSlashes($_POST["admcontnumb"]));
    $premium = trim($myts->addSlashes($_POST["premium"]));
    $moddesc = trim($myts->addSlashes($_POST["moddesc"]));
    $date = time();
    
    // now check for a valid image
    $domain = XOOPS_URL;
    $path = './images/shots/';   //path to targetfolder
    $path_after_domain = '/modules/' . $mydirname . '/images/shots/';   //path to targetfolder for use in url
    $max_size = $xoopsModuleConfig['logo_maxfilesize'];          //maximum filesize
    $ferror = false;
    if ((isset($_FILES['logoup'])) &&  (is_uploaded_file($_FILES['logoup']['tmp_name']))) {
        if ($_FILES['logoup']['size']>$max_size) {
            $ferror = _MD_MXDIR_ELOGOSIZE; // file too big
        } else {
            if (($_FILES['logoup']['type']=="image/gif") || ($_FILES['logoup']['type']=="image/png") || ($_FILES['logoup']['type']=="image/jpeg")) {
                if (file_exists($path . $_FILES['logoup']['name'])) {
                    $ferror = _MD_MXDIR_ELOGOSAMENAME;  // file exists
                } else {
                    if (!copy($_FILES['logoup']['tmp_name'], $path .$_FILES['logoup']['name'])) {
                    $ferror = _MD_MXDIR_ELOGOTEMP;
                }
                }
            } else {
                $ferror = _MD_MXDIR_ELOGOTYPE;  // wrong file type
            }
        }
//	} elseif ((isset($_FILES['logoup'])) &&  ($_FILES['logoup']['error'] != 0)) {
//		$ferror = sprintf(_MD_MXDIR_ELOGOUNK , $_FILES['logoup']['error']);
    }

    if ($ferror) { redirect_header("./",3,$ferror); exit(); }  // exit if error
    //
    // everything's okay so put it in the dB
    //
    $newid = $xoopsDB->genId($xoopsDB->prefix("xdir_links")."_requestid_seq");

    $sql = sprintf("INSERT INTO %s (lid, cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, submitter, status, date, hits, rating, votes, comments, premium) VALUES (%u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix("xdir_links"), $newid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $submitter, $status, $date, 0, 0, 0, 0, $premium);
    $xoopsDB->query($sql) or $eh->show("0013");
    $newid = ($newid == 0) ? $newid = $xoopsDB->getInsertId() : $newid ;
    $sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix("xdir_text"), $newid, $moddesc);
    $xoopsDB->query($sql) or $eh->show("0013");
    //
    // and finally set the notification
    //
    $notification_handler =& xoops_gethandler('notification');
    $tags = array();
    $tags['LINK_NAME'] = $title;
    $tags['LINK_URL'] = XOOPS_URL . '/modules/'. $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&lid=' . $newid;
    $sql = "SELECT title FROM " . $xoopsDB->prefix("xdir_cat") . " WHERE cid=" . $cid;
    $result = $xoopsDB->query($sql);
    $row = $xoopsDB->fetchArray($result);
    $tags['CATEGORY_NAME'] = $row['title'];
    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
    if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
        $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
        $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
        redirect_header("index.php",2,_MD_MXDIR_RECEIVED."<br />"._MD_MXDIR_ISAPPROVED."");
    }else{
        $tags['WAITINGLINKS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=listNewLinks';
        $notification_handler->triggerEvent('global', 0, 'link_submit', $tags);
        $notification_handler->triggerEvent('category', $cid, 'link_submit', $tags);
        if ($notify) {
            include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
            $notification_handler->subscribe('link', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
        }
        redirect_header("index.php",10,_MD_MXDIR_RECEIVED);
    }
    exit();

} else {    // Display submit form

    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : _MD_MXDIR_ANON;
    $arry1 = array( 0 =>_MD_MXDIR_NONE);
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
    $linkimg_array = array_merge($arry1 , $linkimg_array);
    $logourl = '';  // clear logo
    
    $sql = "SELECT cid, title FROM ".$xoopsDB->prefix("xdir_cat");
    $result = $xoopsDB->query($sql);

    $modlinkform = new XoopsThemeForm(_MD_MXDIR_ADDNEWLINK, 'submitform', $_SERVER['PHP_SELF'], 'POST',true);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_SITETITLE , 'title', 50, 100, ''),true);
    
    $addrtray = new XoopsFormElementTray(_MD_MXDIR_BUSADDRESS,'<br />');
    $addrtray->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS1 , 'address', 38, 200, ''));
    $addrtray->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS2 , 'address2', 38, 100, ''));
    $modlinkform->addElement($addrtray);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSCITY , 'city', 50, 80, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSSTATE , 'state', 50, 80, ''));// EVU CODE changed size and max size
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSZIP , 'zip', 15, 15, ''));
    $modlinkform->addElement(new XoopsFormSelectCountry(_MD_MXDIR_BUSCOUNTRY , 'country', ''));
    $hrtray = new XoopsFormElementTray(_MD_MXDIR_BUSHRS ,'<br />');
    $hrtray->addElement(new XoopsFormTime(_MD_MXDIR_BUSMFHRS , 'mfhrs', 15, ''));
    $hrtray->addElement(new XoopsFormTime(_MD_MXDIR_BUSSATHRS , 'sathrs', 15, ''));
    $hrtray->addElement(new XoopsFormTime(_MD_MXDIR_BUSSUNHRS , 'sunhrs', 15, ''));
    $modlinkform->addElement($hrtray);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSPHONE , 'phone', 15, 35, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSFAX , 'fax', 15, 35, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSMOBILE , 'mobile', 15, 35, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSHOME , 'home', 15, 35, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSTOLLFREE , 'tollfree', 15, 35, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSEMAIL , 'email', 50, 100, ''));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_SITEURL , 'url', 50, 250, ''));
    $contray = new XoopsFormElementTray(_MD_MXDIR_BUSADMCONT , '<br />');
    $contray->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNAME , 'admcontname', 28, 35, ''),true);
    $contray->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNUMB , 'admcontnumb', 28, 35, ''),true);
    $modlinkform->addElement($contray);
    $sel_cat = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'cid', '', 1, false));
    $tree = $mytree->getChildTreeArray(0,"title ASC");
    foreach ($tree as $branch ) {
        $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $sel_cat -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
    }
    $modlinkform->addElement($sel_cat);
    $modlinkform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC , 'moddesc' , null , 8, 50), false);

    $modlinkform->addElement(new XoopsFormFile(_MD_MXDIR_LOGOUP , 'logoup', $xoopsModuleConfig['logo_maxfilesize']));

    $premtray = new XoopsFormElementTray(_MD_MXDIR_PREMIUM ,'');
    
    $premopts = getlvlselects();
    $premmenu = new XoopsFormSelect(_MD_MXDIR_PREMIUM , 'premium', '', '', false);
    $premmenu->addOptionArray($premopts);
    //$modlinkform->addElement($premmenu);
    $premtray->addElement($premmenu);
    $whaturl = XOOPS_URL."/modules/".$mydirname."/matrix.php";
    $premtray->addElement(new XoopsFormLabel("","<a href=\"" . $whaturl . "\">" . _MD_MXDIR_WHATTHIS ."</a>"));
    $modlinkform->addElement($premtray);
    //
    // zyspec - add code for security graphic
    // modified from original code from XoopsContact v1.6 by IBDeeming
    //
    // captcha_stat =
    //								0 or false, gd not loaded - validate captcha
    //								1, gd loaded, - validate captcha
    //								2, gd2 loaded - validate captcha
    //								else, - don't validate captcha
    //
    if ( empty($xoopsUser) && $xoopsModuleConfig['captcha_anon'] ) {
//	if (($xoopsModuleConfig['captcha_anon'])) {          //check captcha for annon users?
        $gd = ( extension_loaded('gd') ) ? 1 : false ;
        $gd = ( extension_loaded('gd2') ) ? 2 : $gd ;
        $captcha_stat = $gd;
        if ( $gd ){
            mt_srand((double)microtime()*10000);
            $random_num = mt_rand(0, 100000);
            $security = "<img src='getgfx.php?random_num=$random_num&amp;gd=$gd' border='1' alt='"._MD_MXDIR_SECURITY_CODE."' title='"._MD_MXDIR_SECURITY_CODE."' />&nbsp;&nbsp;"
                                    ."<img src='images/no-spam.jpg' alt='"._MD_MXDIR_NO_SPAM."' title='"._MD_MXDIR_NO_SPAM."' />";
            $captchatray = new XoopsFormElementTray('','<br />');
            $captchatray->addElement(new XoopsFormLabel('' , $security));
            $captchatray->addElement(new XoopsFormHidden('sec_hidden',$random_num));
            $captchatray->addElement(new XoopsFormText('','security',15,10,''));
            $modlinkform->addElement($captchatray);
        }
    } else {
        $captcha_stat = 99;
    }
    $modlinkform->addElement(new XoopsFormHidden('captcha_stat', $captcha_stat));
    //	zyspec - end of security graphic code

    $submittray = new XoopsFormElementTray('','');
    $regtray = new XoopsFormElementTray('','');
    $regtray->addElement(new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit'));
    $regtray->addElement(new XoopsFormHidden('submit',true));
    $regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));
    $submittray->addElement($regtray);

//	$modlinkform->addElement(new XoopsFormHidden('description', $desc));
    $modlinkform->addElement(new XoopsFormHidden('mfhrs', ''));
    $modlinkform->addElement(new XoopsFormHidden('sathrs', ''));
    $modlinkform->addElement(new XoopsFormHidden('sunhrs', ''));
    $modlinkform->addElement(new XoopsFormHidden('submitter', $submitter));
    $modlinkform->addElement($submittray);

    $modlinkform->setExtra('enctype="multipart/form-data"');
    $modlinkform->display();
  include XOOPS_ROOT_PATH.'/footer.php';
}

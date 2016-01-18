<?php
// $Id: modlink.php 11970 2013-08-24 14:20:57Z beckmi $
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

include "header.php";
include_once XOOPS_ROOT_PATH.'/header.php';

global $xoopsDB, $eh, $xoopsConfig, $xoopsModuleConfig, $xoopsUser;
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include "include/securitycheck.php";
include 'class/formtime.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
$mydirname = basename ( dirname( __FILE__ ) )  ;
$uploadirectory="modules/" . $mydirname. "/images/shots";

if (!empty($_POST['submit'])) {
    // Form posted - evaluate the results & put into the dB for approval
    //xoops security class before captcha eval.
        if (!$GLOBALS['xoopsSecurity']->check()) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
        }
    // check captcha security
    if ( empty($_POST['captcha_stat']) ) {
        redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
  }
    switch ($_POST['captcha_stat']) {
        case false:
        case 0:
            redirect_header(XOOPS_URL."./",5,_MD_MXDIR_FAIL_GD_LOAD);
            break;
        case 1:
        case 2:
            if (empty($_POST["security"]) || empty($_POST["sec_hidden"])) {
                redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
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
    
    $cid = intval($_POST["cid"]);
    $lid = intval($_POST["lid"]);
    if ( !isset($_POST["title"]) || (trim($_POST["title"])=="") ) {
        redirect_header("./",5,_MD_MXDIR_FIELDEMPTY);
    } elseif ( (!isset($_POST["modifysubmitter"]) || (intval($_POST["modifysubmitter"])<= 0) ) && $xoopsModuleConfig['showmod'] != 1) {
        //		$url = $myts->formatURL($_POST["url"]);
        //		$url = urlencode($url);
        redirect_header("./",5,_MD_MXDIR_NOPERM);
    }
    if (!empty($_FILES["logoup"]["name"])){
            $thislogo = $_FILES["logoup"]["name"];
            $logourl = $myts->addSlashes($thislogo);
    }    else {
            $logourl = $myts->addSlashes($_POST["logourl"]);
            if (basename($logourl) == "0") {
                $logourl = "";
            }
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
    $modifysubmitter = trim($myts->addSlashes($_POST["modifysubmitter"]));
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
                    $ferror = _MD_MXDIR_ELOGOTEMP;  //can't r/w/ temp rile
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
    $newid = $xoopsDB->genId($xoopsDB->prefix("xdir_mod")."_requestid_seq");
    $sql = sprintf("INSERT INTO %s (requestid, lid, cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, logourl, admcontname, admcontnumb, premium, description, modifysubmitter) VALUES (%u, %u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u)", $xoopsDB->prefix("xdir_mod"), $newid, $lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $admcontname, $admcontnumb, $premium, $moddesc, $modifysubmitter);
    $xoopsDB->query($sql) or $eh->show("0013");
    //
    // and finally set the notification
    //
    $tags = array();
    $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=listModReq';
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'link_modify', $tags);
    redirect_header("index.php",2,_MD_MXDIR_THANKSFORINFO);

} else {    // Display modification form for lid

    if (!isset($_GET["lid"]) || intval($_GET["lid"] == 0) ) {
        redirect_header("./",5,_MD_MXDIR_NOLISTAVAIL);
    }else {
        $lid = intval($_GET["lid"]);
    }
    $thisuser = (empty($xoopsUser)) ?  redirect_header("./",5,_MD_MXDIR_MUSTREGFIRST) : $xoopsUser->uid() ;

    $arry1 = array( 0 =>_MD_MXDIR_NONE);
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
    $linkimg_array = array_merge($arry1 , $linkimg_array);
    $result = $xoopsDB->query("select cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, premium from ".$xoopsDB->prefix("xdir_links")." where lid=$lid") or $eh->show("0013");
    list($cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $premium) = $xoopsDB->fetchRow($result);

    $title = $myts->htmlSpecialChars($title);
    $address = $myts->htmlSpecialChars($address);
    $address2 = $myts->htmlSpecialChars($address2);
    $city = $myts->htmlSpecialChars($city);
    $state = $myts->htmlSpecialChars($state);
    $zip = $myts->htmlSpecialChars($zip);
    $country = $myts->htmlSpecialChars($country);
    $mfhrs = $myts->htmlSpecialChars($mfhrs);
    $sathrs = $myts->htmlSpecialChars($sathrs);
    $sunhrs = $myts->htmlSpecialChars($sunhrs);
    $phone = $myts->htmlSpecialChars($phone);
    $fax = $myts->htmlSpecialChars($fax);
    $mobile = $myts->htmlSpecialChars($mobile);
    $home = $myts->htmlSpecialChars($home);
    $tollfree = $myts->htmlSpecialChars($tollfree);
    $email = $myts->htmlSpecialChars($email);
    $url = $myts->htmlSpecialChars($url);
    //   	$url = urldecode($url);
    $admcontname = $myts->htmlSpecialChars($admcontname);
    $admcontnumb = $myts->htmlSpecialChars($admcontnumb);
    $logourl = $myts->htmlSpecialChars($logourl);
    $premium = $myts->htmlSpecialChars($premium);
    //  	$logourl = urldecode($logourl);
    $resultdesc = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
    list($moddesc)=$xoopsDB->fetchRow($resultdesc);
    $GLOBALS['moddesc'] = $myts->htmlSpecialChars($moddesc);
    
    $sql = "SELECT cid, title FROM ".$xoopsDB->prefix("xdir_cat");
    $result = $xoopsDB->query($sql);

    $modlinkform = new XoopsThemeForm(_MD_MXDIR_MODLINK, 'modlinkform', $_SERVER['PHP_SELF'], 'POST', true);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_SITETITLE , 'title', 50, 100, $title),true);
    
    $addrtray = new XoopsFormElementTray(_MD_MXDIR_BUSADDRESS,'<br />');
    $addrtray->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS1 , 'address', 38, 200, $address));
    $addrtray->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS2 , 'address2', 38, 100, $address2));
    $modlinkform->addElement($addrtray);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSCITY , 'city', 50, 80, $city));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSSTATE , 'state', 50, 80, $state)); // EVU CODE changed size and max size
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSZIP , 'zip', 15, 15, $zip));
    $modlinkform->addElement(new XoopsFormSelectCountry(_MD_MXDIR_BUSCOUNTRY , 'country', $country));
    $modlinkform->addElement(new XoopsFormTime(_MD_MXDIR_BUSMFHRS , 'mfhrs', 15, $mfhrs));
    $modlinkform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSATHRS , 'sathrs', 15, $sathrs));
    $modlinkform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSUNHRS , 'sunhrs', 15, $sunhrs));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSPHONE , 'phone', 15, 35, $phone));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSFAX , 'fax', 15, 35, $fax));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSMOBILE , 'mobile', 15, 35, $mobile));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSHOME , 'home', 15, 35, $home));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSTOLLFREE , 'tollfree', 15, 35, $tollfree));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSEMAIL , 'email', 50, 100, $email));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_SITEURL , 'url', 50, 250, $url));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNAME , 'admcontname', 50, 35, $admcontname));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNUMB , 'admcontnumb', 50, 35, $admcontnumb));
    $sel_cat = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'cid', $cid, 1, false));
    $tree = $mytree->getChildTreeArray(0,"title ASC");
    foreach ($tree as $branch ) {
        $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $sel_cat -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
    }
    $modlinkform->addElement($sel_cat);
    $modlinkform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC , 'moddesc' , $moddesc , 8, 50 ), false);
//	$modlinkform->addElement(new XoopsFormHidden('description', $moddesc));
    //img choose & display(display currently funked up)
    $image_option=new XoopsFormSelect(_MD_MXDIR_SHOTIMAGE, 'logourl', $logourl);
    $image_option->addOptionArray($linkimg_array);
    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
    $image_option->setExtra("onchange='showImgSelected(\"logourlex\", \"logourl\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $imgtray->addElement($image_option,false);
    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='" . XOOPS_URL ."/". $uploadirectory ."/". $logourl . "' name='logourlex' id='logourlex' alt='' />" ) );
    $modlinkform->addElement($imgtray);
    //Img Upload
    $modlinkform->addElement(new XoopsFormFile(_MD_MXDIR_LOGOUP, 'logoup',$xoopsModuleConfig['logo_maximgwidth']));

    $premopts = getlvlselects();
    $premmenu = new XoopsFormSelect(_MD_MXDIR_PREMIUM, 'premium', $premium, 1, false);
    $premmenu->addOptionArray($premopts);
    $modlinkform->addElement($premmenu);
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
    if ( empty($xoopsUser) && $xoopsModuleConfig['captcha_anon']) {
//	if ($xoopsModuleConfig['captcha_anon']) {
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

//	$modlinkform->addElement(new XoopsFormHidden('lid', $lid));
//	$modlinkform->addElement(new XoopsFormHidden('modifysubmitter', $thisuser));
    $submittray = new XoopsFormElementTray(_MD_MXDIR_MODIFY,'');
    $regtray = new XoopsFormElementTray('','');
    $regtray->addElement(new XoopsFormButton('', '', _MD_MXDIR_MODIFY, 'submit'));
//	$regtray->addElement(new XoopsFormHidden('op', 'modLinkS'));
    $regtray->addElement(new XoopsFormHidden('submit',true));
    $regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));

    $submittray->addElement($regtray);

//	$submittray->addElement(new XoopsFormHidden('op', 'modLinkS'));
    $modlinkform->addElement(new XoopsFormHidden('mfhrs', $mfhrs));
    $modlinkform->addElement(new XoopsFormHidden('sathrs', $sathrs));
    $modlinkform->addElement(new XoopsFormHidden('sunhrs', $sunhrs));
    $modlinkform->addElement(new XoopsFormHidden('openstrip', ''));
    $modlinkform->addElement(new XoopsFormHidden('closestrip', ''));
    $modlinkform->addElement(new XoopsFormHidden('op', 'modLinkS'));
    $modlinkform->addElement(new XoopsFormHidden('lid', $lid));
    $modlinkform->addElement(new XoopsFormHidden('modifysubmitter', $thisuser));
//	$modlinkform->addElement(new XoopsFormHidden('description', $moddescription));
    $modlinkform->addElement($submittray);

    $modlinkform->setExtra('enctype="multipart/form-data"');
    $modlinkform->display();
  include XOOPS_ROOT_PATH.'/footer.php';
}

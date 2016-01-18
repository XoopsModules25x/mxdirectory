<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:                                                                 //
// myPHPNUKE Web Portal System - http://myphpnuke.com/                       //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		                 //
// Thatware - http://thatware.org/					                         //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com                                               //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.                                    //
// ------------------------------------------------------------------------- //

include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
    include "../language/".$xoopsConfig['language']."/main.php";
} else {
    include "../language/english/main.php";
}
include '../include/functions.php';
//include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
$mydirname = basename ( dirname(dirname( __FILE__ ) ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH.'/class/module.errorhandler.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/uploader.php";
include '../class/formtime.php';
$myts =& MyTextSanitizer::getInstance();
$eh = new ErrorHandler;
$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
$mydirname = basename ( dirname( dirname( __FILE__ ) ) )  ;
include 'functions.php';

    xoops_cp_header();
//	adminmenu(-1);
//Begin encapsulated adminmenu
function xdir()
{
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $mydirname;
    
    $xdirform = new XoopsThemeForm(_MD_MXDIR_WEBLINKSCONF, 'xdirform', $_SERVER['PHP_SELF'], 'POST');
// Temporarily 'homeless' links
        $result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_broken")."");
        list($totalbrokenlinks) = $xoopsDB->fetchRow($result);
    if($totalbrokenlinks>0){
        $totalbrokenlinks = "<span style='color: #ff0000; font-weight: bold'>$totalbrokenlinks</span>";
    }
        $result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_mod")."");
        list($totalmodrequests) = $xoopsDB->fetchRow($result2);
    if($totalmodrequests>0){
        $totalmodrequests = "<span style='color: #ff0000; font-weight: bold'>$totalmodrequests</span>";
    }
    $result3 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status=0");
            list($totalnewlinks) = $xoopsDB->fetchRow($result3);
    if($totalnewlinks>0){
        $totalnewlinks = "<span style='color: #ff0000; font-weight: bold'>$totalnewlinks</span>";
    }

    $xdirform->addElement(new XoopsFormLabel( _MD_MXDIR_ADDMODDELETE, "<a href=main.php?op=linksConfigMenu>"._MD_MXDIR_ADDMODDELETE."</a>"));
    $xdirform->addElement(new XoopsFormLabel(_MD_MXDIR_LINKSWAITING, "<a href=main.php?op=listNewLinks> ($totalnewlinks)</a>"));
    $xdirform->addElement(new XoopsFormLabel(_MD_MXDIR_BROKENREPORTS, " <a href=main.php?op=listBrokenLinks> ($totalbrokenlinks)</a>"));
    $xdirform->addElement(new XoopsFormLabel(_MD_MXDIR_MODREQUESTS, " <a href=main.php?op=listModReq> ($totalmodrequests)</a>"));
        $result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status>0");
        list($numrows) = $xoopsDB->fetchRow($result);
    $xdirform->display();
    echo"<div style=\"text-align: center;\">";
    printf(_MD_MXDIR_THEREARE,$numrows);    echo "</div><br />";
//Level Option Table
    $actvlvls = getlvlselects();
//	$als = count ($actvlvls);
  if ($actvlvls != false) {
    echo"<span style='font-size:xx-small;'><table class=\"outer\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\"><tr class=\"head\"><td width=\"12%\" >"._MI_MXDIR_PREMIUM_ACTV."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_ON."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_SLLI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_SLSLI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_SLLF."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_CALI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_CALF."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_LOLI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_LOLF."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_LLSLI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_LLCSLI."</td><td width=\"8%\">"._MI_MXDIR_PREMIUM_UON."</td></tr></table></span>";
    reset($actvlvls);
    while (list($key, $val) = each($actvlvls)) {
      $actvopts = getPremiumOptions($key);
      echo"<span style='font-size:xx-small;'><table class=\"outer\" width=\"100%\"><tr class=\"even\"><td width=\"12%\" >$val</td>";
      while (list ($okey, $oval) = each($actvopts)){
        echo"<td width=\"8%\" ><div style=\"text-align: center;\">$oval</div></td>";
      }
      echo "</tr></table></span>";
    }
  }
    xoops_cp_footer();
}

function listNewLinks()
{
// List links waiting for validation
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree, $mydirname, $xoopsModuleConfig;
    $uploadirectory="/modules/" . $mydirname. "/images/shots/";
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
        $result = $xoopsDB->query("select lid, cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, submitter, premium from ".$xoopsDB->prefix("xdir_links")." where status=0 order by date DESC");
        $numrows = $xoopsDB->getRowsNum($result);

    echo "<h4>"._MD_MXDIR_WEBLINKSCONF."</h4>";
    echo "<h4>"._MD_MXDIR_LINKSWAITING." ($numrows)</h4><br />";
        if ( $numrows > 0 ) {
        while(list($lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $submitterid, $premium) = $xoopsDB->fetchRow($result)) {
            $result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
            list($description) = $xoopsDB->fetchRow($result2);
            $btitle = $myts->htmlSpecialChars($title);
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
            $admcontname = $myts->htmlSpecialChars($admcontname);
            $admcontnumb = $myts->htmlSpecialChars($admcontnumb);
            $logourl = $myts->htmlSpecialChars($logourl);
            $description = $myts->htmlSpecialChars($description);
            $submitter = XoopsUser::getUnameFromId($submitterid);
            $premium = $myts->htmlSpecialChars($premium);

    $addwaitingform = new XoopsThemeForm(_MD_MXDIR_ADDNEWLINK, 'addwaitingform', $_SERVER['PHP_SELF'], 'POST', true);
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_SITETITLE , 'title', 50, 100, $btitle));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS , 'address', 50, 200, $address));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS2 , 'address2', 50, 100, $address2));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSCITY , 'city', 50, 80, $city));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSSTATE , 'state', 50, 80, $state));// EVU CODE changed size and max size
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSZIP , 'zip', 15, 15, $zip));
    $addwaitingform->addElement(new XoopsFormSelectCountry(_MD_MXDIR_BUSCOUNTRY , 'country', $country));
    $addwaitingform->addElement(new XoopsFormTime(_MD_MXDIR_BUSMFHRS , 'mfhrs', 15, $mfhrs));
    $addwaitingform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSATHRS , 'sathrs', 15, $sathrs));
    $addwaitingform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSUNHRS , 'sunhrs', 15, $sunhrs));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSPHONE , 'phone', 15, 35, $phone));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSFAX , 'fax', 15, 35, $fax));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSMOBILE , 'mobile', 15, 35, $mobile));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSHOME , 'home', 15, 35, $home));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSTOLLFREE , 'tollfree', 15, 35, $tollfree));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSEMAIL , 'email', 50, 100, $email));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_SITEURL , 'url', 50, 250, $url));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNAME , 'admcontname', 50, 35, $admcontname));
    $addwaitingform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNUMB , 'amcontnumb', 50, 35, $admcontnumb));
    $sel_cat = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'cid', $cid, 1, false));
        $tree = $mytree->getChildTreeArray(0,"title ASC");
        foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $sel_cat -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
    $addwaitingform->addElement($sel_cat);
    $addwaitingform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC , 'description' , $description , 8, 100, $description), false);

    $image_option=new XoopsFormSelect(_MD_MXDIR_SHOTIMAGE, 'logourl', $logourl);
    $image_option->addOption('',_MD_MXDIR_NONE);
    $image_option->addOptionArray($linkimg_array);
    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
    $image_option->setExtra("onchange='showImgSelected(\"logourlex\", \"logourl\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $imgtray->addElement($image_option,false);
    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='../images/shots/" . $logourl . "' name='logourlex' id='logourlex' alt='' />" ) );
    $addwaitingform->addElement($imgtray);

    $addwaitingform->addElement(new XoopsFormFile(_MD_MXDIR_LOGOUP, 'logoup',$xoopsModuleConfig['logo_maxfilesize']));
        $premopts = array(
           '1' => $xoopsModuleConfig['premium_listing1'],
           '2' => $xoopsModuleConfig['premium_listing2'],
           '3' => $xoopsModuleConfig['premium_listing3'],
           '4' => $xoopsModuleConfig['premium_listing4'],
           '5' => $xoopsModuleConfig['premium_listing5'],
        );
    $premmenu = new XoopsFormSelect(_MD_MXDIR_PREMIUM, 'premium', $premium, 1, false);
    $premmenu->addOptionArray($premopts);
    $addwaitingform->addElement($premmenu);
    $addwaitingform->setExtra('enctype="multipart/form-data"');
    $addwaitingform->addElement(new XoopsFormHidden('openstrip', ''));
    $addwaitingform->addElement(new XoopsFormHidden('closestrip', ''));
    $addwaitingform->addElement(new XoopsFormHidden('mfhrs', $mfhrs));
    $addwaitingform->addElement(new XoopsFormHidden('sathrs', $sathrs));
    $addwaitingform->addElement(new XoopsFormHidden('op', ''));
    $addwaitingform->addElement(new XoopsFormHidden('sunhrs', $sunhrs));
    $addwaitingform->addElement(new XoopsFormHidden('lid', $lid));
//	$addwaitingform->addElement(new XoopsFormHidden('op', 'approve'));
    
    $wraptray = new XoopsFormElementTray(_MD_MXDIR_MODIFY,'');
    
    $deltray = new XoopsFormElementTray('');
    $dbtn=new XoopsFormButton('', '', _MD_MXDIR_DELETE, 'submit');
    $dbtn->setExtra('onclick="this.form.elements.op.value=\'delNewLink\'"');
    $deltray->addElement($dbtn);
    
    $regtray = new XoopsFormElementTray('');
    $sbtn=new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit');
    $sbtn->setExtra('onclick="this.form.elements.op.value=\'approve\'"');
//	$regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));
    $regtray->addElement($sbtn);
    
    $wraptray->addElement($regtray);
    $wraptray->addElement($deltray);

    $addwaitingform->addElement($wraptray);
//	$addwaitingform->addElement(new XoopsFormButton('', '', _MD_MXDIR_APPROVE, 'submit'));
    $addwaitingform->display();
//	echo myTextForm("main.php?op=delNewLink&lid=$lid&t=".$GLOBALS['xoopsSecurity']->createToken()."",_MD_MXDIR_DELETE);

        }
    } else {
        echo ""._MD_MXDIR_NOSUBMITTED."";
    }
    xoops_cp_footer();
}

//Add/Edit/Delete Menu as a single, big, stupid, -I'm too lazy to bust it up right now- function
function linksConfigMenu()
{
    global $xoopsDB,$xoopsConfig, $myts, $eh, $mytree, $mydirname, $xoopsModuleConfig;
//Common querys in || function linksConfigMenu()
    $uploadirectory="/modules/" . $mydirname. "/images/shots/";
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
    $sql = "SELECT cid, title FROM ".$xoopsDB->prefix("xdir_cat");
    $result = $xoopsDB->query($sql);
         
// Modify Link
    $result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")."");
    list($numrows2) = $xoopsDB->fetchRow($result2);
    if ( $numrows2 > 0 ) {
    $lid = "";
        $sql = "SELECT lid, title FROM ".$xoopsDB->prefix("xdir_links")." order by title asc";
      $result3 = $xoopsDB->query($sql);
    
      $modlinkform = new XoopsThemeForm(_MD_MXDIR_MODLINK, 'modlinkform', $_SERVER['PHP_SELF'], 'POST', true);
      $select_link = (new XoopsFormSelect(_MD_MXDIR_LINKID , 'lid', $lid, 1, false));
      while (list($lid, $title) = $xoopsDB->fetchRow($result3) ) {
        $select_link->addOption($lid, $title);
      }
      $modlinkform->addElement($select_link);
      $modlinkform->addElement(new XoopsFormHidden('op', ''));
      $modlinkform->addElement(new XoopsFormHidden('openstrip', ''));
      $modlinkform->addElement(new XoopsFormHidden('closestrip', ''));
      $wraptray = new XoopsFormElementTray(_MD_MXDIR_MODIFY,'');
    
        $deltray = new XoopsFormElementTray('');
        $dbtn=new XoopsFormButton('', '', _MD_MXDIR_DELETE, 'submit');
        $dbtn->setExtra('onclick="this.form.elements.op.value=\'delLink\'"');
        $deltray->addElement($dbtn);
        
        $regtray = new XoopsFormElementTray('');
        $sbtn=new XoopsFormButton('', '', _MD_MXDIR_MODIFY, 'submit');
        $sbtn->setExtra('onclick="this.form.elements.op.value=\'modLink\'"');
        $regtray->addElement($sbtn);
        
        $wraptray->addElement($regtray);
        $wraptray->addElement($deltray);

      $modlinkform->addElement($wraptray);
      $modlinkform->display();
    }
// If there is a category, add a New Record
    $result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_cat")."");
    list($numrows)=$xoopsDB->fetchRow($result);

    $mfhrs = 0;
    $sathrs = 0;
    $sunhrs= 0;
    $logourl= '';
    
    if ( $numrows > 0 ) {
    $addrecordform = new XoopsThemeForm(_MD_MXDIR_ADDNEWLINK, 'addrecordform', $_SERVER['PHP_SELF'], 'POST', true);

    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_SITETITLE , 'title', 50, 100));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS , 'address', 50, 200));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS2 , 'address2', 50, 100));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSCITY , 'city', 50, 80));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSSTATE , 'state', 50, 80));// EVU CODE changed size and max size
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSZIP , 'zip', 15, 15));
    $addrecordform->addElement(new XoopsFormSelectCountry(_MD_MXDIR_BUSCOUNTRY , 'country'));
    $addrecordform->addElement(new XoopsFormTime(_MD_MXDIR_BUSMFHRS , 'mfhrs', 15, $mfhrs));
    $addrecordform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSATHRS , 'sathrs', 15, $sathrs));
    $addrecordform->addElement(new XoopsFormTime(_MD_MXDIR_BUSSUNHRS , 'sunhrs', 15, $sunhrs));

    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSPHONE , 'phone', 15, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSFAX , 'fax', 15, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSMOBILE , 'mobile', 15, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSHOME , 'home', 15, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSTOLLFREE , 'tollfree', 15, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSEMAIL , 'email', 50, 100));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_SITEURL , 'url', 50, 250, 'http://'));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNAME , 'admcontname', 50, 35));
    $addrecordform->addElement(new XoopsFormText(_MD_MXDIR_BUSADMCONTNUMB , 'admcontnumb', 50, 35));
    $sel_cat = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'cid', null, 1, false));
        $tree = $mytree->getChildTreeArray(0,"title ASC");
        foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $sel_cat -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
    $addrecordform->addElement($sel_cat);
    $addrecordform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC , 'adddesc' , '', 8, 100), false);
    $image_option=new XoopsFormSelect(_MD_MXDIR_SHOTIMAGE, 'logourl', $logourl);
    $image_option->addOption('',_MD_MXDIR_NONE);
    $image_option->addOptionArray($linkimg_array);
    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
    $image_option->setExtra("onchange='showImgSelected(\"logourlex\", \"logourl\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $imgtray->addElement($image_option,false);
    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='../images/shots/" . $logourl . "' name='logourlex' id='logourlex' alt='' />" ) );
    $addrecordform->addElement($imgtray);
    //Img Upload
    $addrecordform->addElement(new XoopsFormFile(_MD_MXDIR_LOGOUP, 'logoup',$xoopsModuleConfig['logo_maxfilesize']));
    $premopts = array(
       '1' => $xoopsModuleConfig['premium_listing1'],
       '2' => $xoopsModuleConfig['premium_listing2'],
       '3' => $xoopsModuleConfig['premium_listing3'],
       '4' => $xoopsModuleConfig['premium_listing4'],
       '5' => $xoopsModuleConfig['premium_listing5'],
        );
    $premmenu = new XoopsFormSelect(_MD_MXDIR_PREMIUM, 'premium', null, 1, false);
        $premmenu->addOptionArray($premopts);
    $addrecordform->addElement($premmenu);
    $addrecordform->setExtra('enctype="multipart/form-data"');
    $addrecordform->addElement(new XoopsFormHidden('mfhrs', $mfhrs));
    $addrecordform->addElement(new XoopsFormHidden('sathrs', $sathrs));
    $addrecordform->addElement(new XoopsFormHidden('sunhrs', $sunhrs));
    $addrecordform->addElement(new XoopsFormHidden('op', 'addLink'));
    $addrecordform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_ADD, 'submit'));
    $addrecordform->display();
    } else {
    echo "<table width='100%' border='0' cellspacing='1' class='outer'>";
    echo "<tr class=\"head\"><td>";
        echo "<h4>"._MD_MXDIR_LISTINGS."</h4><br />";
        echo _MD_MXDIR_CATSNOEXIST;
        echo "</td></tr></table>";
    }
    
    xoops_cp_footer();
}

function multicat()
{
    global $xoopsDB, $_GET, $myts, $eh, $mytree, $xoopsConfig, $mydirname, $xoopsModuleConfig;
    $lid = empty($_GET['lid']) ? '' : intval($_GET['lid']);
    if ($lid == '') {
    $uploadirectory="/modules/" . $mydirname. "/images/shots/";
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
    $sql = "SELECT cid, title FROM ".$xoopsDB->prefix("xdir_cat");
    $result = $xoopsDB->query($sql);
echo "<table><tr><td valign=top>";
// Add a New Main Category
    $addcatform = new XoopsThemeForm(_MD_MXDIR_ADDMAIN, 'addcatform', $_SERVER['PHP_SELF'], 'POST', true);
    $addcatform->addElement(new XoopsFormText(_MD_MXDIR_TITLEC , 'title', 100, 100));
    
    $addcattray = new XoopsFormElementTray('');
    $addcattray->addElement(new XoopsFormText(_MD_MXDIR_IMGURL , 'imgurl', 50, 150));
    $addcattray->addElement(new XoopsFormLabel('' , ''));
    $addcatform->addElement($addcattray);
    
    $addcatform->addElement(new XoopsFormHidden('cid', '0'));
    $addcatform->addElement(new XoopsFormHidden('op', 'addCat'));
    $addcatform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_ADD, 'submit'));
    $addcatform->display();
echo "</td><td colspan=\"1\" valign=top>";
// Add a New Sub-Category
    $result=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_cat")."");
    list($numrows)=$xoopsDB->fetchRow($result);
// If there is a Parent category, add a Sub
    if ( $numrows > 0 ) {
    $addsubcatform = new XoopsThemeForm(_MD_MXDIR_ADDSUB, 'addsubcatform', $_SERVER['PHP_SELF'], 'POST', true);
    $addsubcatform->addElement(new XoopsFormText(_MD_MXDIR_TITLEC , 'title', 100, 50));
    
    $addsubtray = new XoopsFormElementTray('');
    $select_subcats = (new XoopsFormSelect('<br />' , 'cid', null, 1, false));
        $tree = $mytree->getChildTreeArray(0,"title ASC");
        foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $select_subcats -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
    $addsubtray->addElement(new XoopsFormLabel(_MD_MXDIR_IN , ''));
    $addsubtray->addElement($select_subcats);
    $addsubcatform->addElement($addsubtray);
    
    $addsubcatform->addElement(new XoopsFormHidden('op', 'addCat'));
    $addsubcatform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_ADD, 'submit'));
    $addsubcatform->display();
    }
echo "</td></tr>";
//echo "<tr><td valign=top>";
// Modify Category
    
    $modcatform = new XoopsThemeForm(_MD_MXDIR_MODCAT, 'addsubcatform', $_SERVER['PHP_SELF'], 'POST', true);
    $select_modcats = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'cid', null, 1, false));
    $tree = $mytree->getChildTreeArray(0,"title ASC");
    foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
    $branch['prefix'] = str_replace(".","--",$branch['prefix']);
    $select_modcats -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
    $modcatform->addElement($select_modcats);
    
        $modcatform->addElement(new XoopsFormHidden('op', ''));
    
    $wraptray = new XoopsFormElementTray(_MD_MXDIR_MODCAT,'');
    
        $deltray = new XoopsFormElementTray('');
            $dbtn=new XoopsFormButton('', '', _MD_MXDIR_DELETE, 'submit');
            $dbtn->setExtra('onclick="this.form.elements.op.value=\'delCat\'"');
        $deltray->addElement($dbtn);
        
        $regtray = new XoopsFormElementTray('');
            $sbtn=new XoopsFormButton('', '', _MD_MXDIR_MODCAT, 'submit');
            $sbtn->setExtra('onclick="this.form.elements.op.value=\'modCat\'"');
        $regtray->addElement($sbtn);
        
        $wraptray->addElement($regtray);
        $wraptray->addElement($deltray);

    $modcatform->addElement($wraptray);
    
    
    //$modcatform->addElement(new XoopsFormHidden('op', 'modCat'));
    //$modcatform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_MODIFY, 'submit'));
    
    $modcatform->display();
echo "</td><td valign=top>";

//choose link id
        $result2 = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")."");
        list($numrows2) = $xoopsDB->fetchRow($result2);
        if ( $numrows2 > 0 ) {
        
    $sql = "SELECT lid, title FROM ".$xoopsDB->prefix("xdir_links")." order by title asc";
    $result3 = $xoopsDB->query($sql);
    $multicatform = new XoopsThemeForm(_MD_MXDIR_MULTICATMGR, 'addsubcatform', $_SERVER['PHP_SELF'], 'GET', true);
    $select_link = (new XoopsFormSelect(_MD_MXDIR_LINKID , 'lid', $lid, 1, false));
    while (list($lid, $title) = $xoopsDB->fetchRow($result3) ) {
    $select_link->addOption($lid, $title);}
    $multicatform->addElement($select_link);
    $multicatform->addElement(new XoopsFormHidden('fct', 'xdir'));
    $multicatform->addElement(new XoopsFormHidden('op', 'multicat'));
    $multicatform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_MODIFY, 'submit'));
    $multicatform->display();
echo "</td></tr></table>";
      }
     }
        else{

//calling current cats
            $result = $xoopsDB->query("select cid, cidalt1, cidalt2, cidalt3, cidalt4, title from ".$xoopsDB->prefix("xdir_links")." where lid=$lid") or $eh->show("0013");
            list($cid, $cidalt1, $cidalt2, $cidalt3, $cidalt4, $title) = $xoopsDB->fetchRow($result);

    $multicatform = new XoopsThemeForm(_MD_MXDIR_MULTICATMGR, 'multicatform', $_SERVER['PHP_SELF'], 'POST', true);
    $multicatform->addElement(new XoopsFormLabel(_MD_MXDIR_LINKID , $lid));
    $multicatform->addElement(new XoopsFormLabel(_MD_MXDIR_SITETITLE , $title));
    $select_cat1 = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'valcid', $cid, 1, false));
    $select_cat2 = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC1 , 'valcidalt1', $cidalt1 , 1, false));
    $select_cat3 = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC2 , 'valcidalt2', $cidalt2, 1, false));
    $select_cat4 = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC3 , 'valcidalt3', $cidalt3, 1, false));
    $select_cat5 = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC4 , 'valcidalt4', $cidalt4, 1, false));

        $select_cat1->addOption('',_MD_MXDIR_NONE);
        $select_cat2->addOption('',_MD_MXDIR_NONE);
        $select_cat3->addOption('',_MD_MXDIR_NONE);
        $select_cat4->addOption('',_MD_MXDIR_NONE);
        $select_cat5->addOption('',_MD_MXDIR_NONE);
    
        $tree = $mytree->getChildTreeArray(0,"title ASC");
        foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $select_cat1 -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
        $select_cat2 -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
        $select_cat3 -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
        $select_cat4 -> addOption($branch['cid'],$branch['prefix'].$branch['title']);
        $select_cat5 -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
  
    $multicatform->addElement($select_cat1);
    $multicatform->addElement($select_cat2);
    $multicatform->addElement($select_cat3);
    $multicatform->addElement($select_cat4);
    $multicatform->addElement($select_cat5);
    $multicatform->addElement(new XoopsFormHidden('lid', $lid));
    $multicatform->addElement(new XoopsFormHidden('op', 'multicatS'));
    $multicatform->addElement(new XoopsFormButton('', 'add', _MD_MXDIR_MODIFY, 'submit'));
    $multicatform->display();
echo "</td></tr></table>";
     }
    xoops_cp_footer();
}

function multicatS()
{
        global $xoopsDB, $_POST, $myts, $eh;
        //XoopsSecurity Check
    if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
    }

        $lid = $_POST['lid'];
        $cid = $_POST['valcid'];
    $cidalt1 = $_POST['valcidalt1'];
    $cidalt2 = $_POST['valcidalt2'];
    $cidalt3 = $_POST['valcidalt3'];
    $cidalt4 = $_POST['valcidalt4'];
        $xoopsDB->query("update ".$xoopsDB->prefix("xdir_links")." set cid='$cid', cidalt1='$cidalt1', cidalt2='$cidalt2',  cidalt3='$cidalt3', cidalt4='$cidalt4' where lid=".$_POST['lid']."")  or $eh->show("0013");
        redirect_header("main.php?op=multicat",1,_MD_MXDIR_DBUPDATED);
    exit();
}

function modLink()
{
    global $xoopsDB, $_POST, $_GET, $myts, $eh, $mytree, $xoopsConfig, $mydirname, $xoopsModuleConfig;
//	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    $uploadirectory="/modules/" . $mydirname. "/images/shots/";
$lid =  isset($_POST['lid']) ? intval($_POST['lid']) : intval($_GET['lid']);
    
    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH."/modules/".$mydirname."/images/shots/");
// 	$lid = $_POST['lid'];
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
    $moddesc = $myts->htmlSpecialChars($moddesc);

 $sql = "SELECT cid, title FROM ".$xoopsDB->prefix("xdir_cat");
 $result = $xoopsDB->query($sql);

    $modlinkform = new XoopsThemeForm(_MD_MXDIR_MODLINK, 'modlinkform', $_SERVER['PHP_SELF'], 'POST', true);
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_SITETITLE , 'title', 50, 100, $title));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS , 'address', 50, 200, $address));
    $modlinkform->addElement(new XoopsFormText(_MD_MXDIR_BUSADDRESS2 , 'address2', 50, 100, $address2));
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
        foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
        $branch['prefix'] = str_replace(".","--",$branch['prefix']);
        $sel_cat -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}
    $modlinkform->addElement($sel_cat);
    $modlinkform->addElement(new XoopsFormDhtmlTextArea(_MD_MXDIR_DESCRIPTIONC , 'moddesc' , $moddesc , 8, 100), false);

    $image_option=new XoopsFormSelect(_MD_MXDIR_SHOTIMAGE, 'logourl', $logourl);
    $image_option->addOption('',_MD_MXDIR_NONE);
    $image_option->addOptionArray($linkimg_array);
    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
    $image_option->setExtra("onchange='showImgSelected(\"logourlex\", \"logourl\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $imgtray->addElement($image_option,false);
    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='../images/shots/" . $logourl . "' name='logourlex' id='logourlex' alt='' />" ) );
    $modlinkform->addElement($imgtray);
    //Img Upload
    $modlinkform->addElement(new XoopsFormFile(_MD_MXDIR_LOGOUP, 'logoup',$xoopsModuleConfig['logo_maxfilesize']));
    $premopts = array(
       '1' => $xoopsModuleConfig['premium_listing1'],
       '2' => $xoopsModuleConfig['premium_listing2'],
       '3' => $xoopsModuleConfig['premium_listing3'],
       '4' => $xoopsModuleConfig['premium_listing4'],
       '5' => $xoopsModuleConfig['premium_listing5'],
    );
    $premmenu = new XoopsFormSelect(_MD_MXDIR_PREMIUM, 'premium', $premium, 1, false);
    $premmenu->addOptionArray($premopts);
    $modlinkform->addElement($premmenu);
    $modlinkform->addElement(new XoopsFormHidden('openstrip', ''));
    $modlinkform->addElement(new XoopsFormHidden('closestrip', ''));
    $modlinkform->addElement(new XoopsFormHidden('lid', $lid));
    $modlinkform->addElement(new XoopsFormHidden('op', ''));
    $wraptray = new XoopsFormElementTray(_MD_MXDIR_MODIFY,'');
    
    $deltray = new XoopsFormElementTray('');
    $dbtn=new XoopsFormButton('', '', _MD_MXDIR_DELETE, 'submit');
    $dbtn->setExtra('onclick="this.form.elements.op.value=\'delLink\'"');
    $deltray->addElement($dbtn);
    
    $regtray = new XoopsFormElementTray('');
    $sbtn=new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit');
    $sbtn->setExtra('onclick="this.form.elements.op.value=\'modLinkS\'"');
    $regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));
    $regtray->addElement($sbtn);
    
    $wraptray->addElement($regtray);
    $wraptray->addElement($deltray);

    $modlinkform->addElement($wraptray);

    $modlinkform->addElement(new XoopsFormHidden('mfhrs', $mfhrs));
    $modlinkform->addElement(new XoopsFormHidden('sathrs', $sathrs));
    $modlinkform->addElement(new XoopsFormHidden('sunhrs', $sunhrs));
    $modlinkform->setExtra('enctype="multipart/form-data"');
    $modlinkform->display();
    
//START STATS
        $result5=$xoopsDB->query("SELECT count(*) FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid");
        list($totalvotes) = $xoopsDB->fetchRow($result5);
        echo "<table width=\"100%\">\n";
        echo "<tr><td colspan=\"7\"><font style=\"font-weight: bold;\">";
    printf(_MD_MXDIR_TOTALVOTES,$totalvotes);
    echo "</font><br /></td></tr>\n";
// Show Registered Users Votes
        $result5=$xoopsDB->query("SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result5);
        echo "<tr><td colspan=\"7\"><br /><font style=\"font-weight: bold;\">";
    printf(_MD_MXDIR_USERTOTALVOTES,$votes);
    echo "</font><br /><br /></td></tr>\n";
        echo "<tr><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_USER."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_IP."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_RATING."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_USERAVG."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_TOTALRATE."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_DATE."  </font></td><td align=\"center\"><font style=\"font-weight: bold;\">" ._MD_MXDIR_DELETE."</font></td></tr>\n";
        if ($votes == 0){
        echo "<tr><td align=\"center\" colspan=\"7\">" ._MD_MXDIR_NOREGVOTES."<br /></td></tr>\n";
        }
        $x=0;
        while(list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5)) {
        //	$ratingtimestamp = formatTimestamp($ratingtimestamp);
            //Individual user information
            $result2=$xoopsDB->query("SELECT rating FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE ratinguser = '$ratinguser'");
                $uservotes = $xoopsDB->getRowsNum($result2);
                $useravgrating = 0;
                while ( list($rating2) = $xoopsDB->fetchRow($result2) ) {
            $useravgrating = $useravgrating + $rating2;
        }
                $useravgrating = $useravgrating / $uservotes;
                $useravgrating = number_format($useravgrating, 1);
        $ratingusername = XoopsUser::getUnameFromId($ratinguser);
                echo "<tr><td>".$ratingusername."</td><td>".$ratinghostname."</td><td>$rating</td><td>".$useravgrating."</td><td>".$uservotes."</td><td>".$ratingtimestamp."</td><td align=\"center\"><font style=\"font-weight: bold;\">".myTextForm("main.php?op=delVote&amp;lid=$lid&amp;rid=$ratingid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."", "X")."</font></td></tr>\n";
            $x++;
        }
    // Show Unregistered Users Votes
        $result5=$xoopsDB->query("SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ".$xoopsDB->prefix("xdir_votedata")." WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");
        $votes = $xoopsDB->getRowsNum($result5);
        echo "<tr><td colspan=\"7\"><font style=\"font-weight: bold;\"><br />";
    printf(_MD_MXDIR_ANONTOTALVOTES,$votes);
    echo "</font><br /></td></tr>\n";
        echo "<tr><td colspan=2><font style=\"font-weight: bold;\">" ._MD_MXDIR_IP."  </font></td><td colspan=3><font style=\"font-weight: bold;\">" ._MD_MXDIR_RATING."  </font></td><td><font style=\"font-weight: bold;\">" ._MD_MXDIR_DATE."  </font></td><td align=\"center\"><font style=\"font-weight: bold;\">" ._MD_MXDIR_DELETE."</font></td><br /></tr>";
        if ( $votes == 0 ) {
        echo "<tr><td colspan=\"7\" align=\"center\">" ._MD_MXDIR_NOUNREGVOTES."<br /></td></tr>";
        }
        $x=0;
        while ( list($ratingid, $rating, $ratinghostname, $ratingtimestamp)=$xoopsDB->fetchRow($result5) ) {
        $formatted_date = formatTimestamp($ratingtimestamp);
                echo "<td colspan=\"2\" >$ratinghostname</td><td colspan=\"3\" >$rating</td><td>$formatted_date</td><td align=\"center\"><font style=\"font-weight: bold;\">".myTextForm("main.php?op=delVote&amp;lid=$lid&amp;rid=$ratingid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."", "X")."</font></td></tr>";
        }
            
        
/* 	include XOOPS_ROOT_PATH."/class/xoopsmailer.php";
//Set up the email class
    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
//Sets the template directory, in this example we are using the language directory. This can be useful if we need the email in more than one language.
    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/modules/wfchannel/language/'.$xoopsConfig['language'].'/mail_template');
//Sets the file to be used for this email
    $xoopsMailer->setTemplate("refer.tpl");
//Now we setup needed vars for this email
//Email address we will be sending too
    $xoopsMailer->setToEmails($remail);
//Email address and uname sending from
    $xoopsMailer->setFromEmail($semail);
    $xoopsMailer->setFromName($sname);
//This is the email subject line
    $xoopsMailer->setSubject($subject);
//Use can assign other information to be used within the body of the email if you wish
    $xoopsMailer->assign("MESSAGE", $message);
    $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
    $xoopsMailer->assign("SITEURL", XOOPS_URL."/");
    $xoopsMailer->assign("TITLE", _MD_MXDIR_MESSAGETITLE);
     */
        
        
        
        
        echo "<tr><td colspan=\"6\"> <br /></td></tr>\n";
        echo "</table>\n";
        echo"</td></tr></table>";
        xoops_cp_footer();
}

function delVote()
{
    global $xoopsDB, $_GET, $eh;
    //XoopsSecurity Check
    if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
    }

        $rid = $_GET['rid'];
        $lid = $_GET['lid'];
    $sql = sprintf("DELETE FROM %s WHERE ratingid = %u", $xoopsDB->prefix("xdir_votedata"), $rid);
        $xoopsDB->query($sql) or $eh->show("0013");
        updaterating($lid);
        redirect_header("index.php",1,_MD_MXDIR_VOTEDELETED);
        exit();
}

function listBrokenLinks()
{
        global $xoopsDB, $eh;
        $result = $xoopsDB->query("select * from ".$xoopsDB->prefix("xdir_broken")." group by lid order by reportid DESC");
        $totalbrokenlinks = $xoopsDB->getRowsNum($result);
    echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
    ."<tr class=\"head\"><td>";
    echo "<h4>"._MD_MXDIR_BROKENREPORTS." ($totalbrokenlinks)</h4>";

        if ( $totalbrokenlinks == 0 ) {
        echo _MD_MXDIR_NOBROKEN;
        } else {
        echo "<center>
		"._MD_MXDIR_IGNOREDESC."<br />
		"._MD_MXDIR_DELETEDESC."</center>";
        echo "<table align=\"center\" width=\"90%\">";
                echo "
				<tr class=\"even\">
				<td><b>Link Name</b></td>
				<td><b>" ._MD_MXDIR_REPORTER."</b></td>
				<td><b>" ._MD_MXDIR_LINKSUBMITTER."</b></td>
				<td><b>" ._MD_MXDIR_IGNORE."</b></td>
				<td><b>" ._EDIT."</b></td>
				<td><b>" ._MD_MXDIR_DELETE."</b></td>
				</tr>";
                while ( list($reportid, $lid, $sender, $ip)=$xoopsDB->fetchRow($result) ) {
            $result2 = $xoopsDB->query("select title, url, submitter from ".$xoopsDB->prefix("xdir_links")." where lid=$lid");
            if ( $sender != 0 ) {
                $result3 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid=$sender");
                list($uname, $email)=$xoopsDB->fetchRow($result3);
            }
                list($title, $url, $ownerid)=$xoopsDB->fetchRow($result2);
            //			$url=urldecode($url);
                $result4 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
                list($owner, $owneremail)=$xoopsDB->fetchRow($result4);
                echo "<tr class=\"odd\"><td><a href=$url target='_blank'>$title</a></td>";
                if ( $email=='' ) {
                echo "<td>".$sender." (".$ip.")";
            } else {
                echo "<td><a href=\"mailto:".$email."\">".$uname."</a> (".$ip.")";
            }
                echo "</td>";
                if ( $owneremail == '' ) {
                echo "<td>".$owner."";
            } else {
                echo "<td><a href=\"mailto:".$owneremail."\">".$owner."</a>";
            }

            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=ignoreBrokenLinks&amp;lid=$lid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=modLink&amp;lid=$lid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=delBrokenLinks&amp;lid=$lid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td></tr>\n";
            }
        echo "</table>";
        }

    echo"</td></tr></table>";
    xoops_cp_footer();
}

function delBrokenLinks()
{
    global $xoopsDB, $_GET, $eh;
    //XoopsSecurity Check
    if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
    }

        $lid = $_GET['lid'];
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_broken"), $lid);
        $xoopsDB->query($sql) or $eh->show("0013");
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
        $xoopsDB->query($sql) or $eh->show("0013");
        redirect_header("main.php?op=listBrokenLinks",1,_MD_MXDIR_LINKDELETED);
    exit();
}

function ignoreBrokenLinks()
{
    global $xoopsDB, $_GET, $eh;
    //XoopsSecurity Check
    if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
    }

    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_broken"), $_GET['lid']);
        $xoopsDB->query($sql) or $eh->show("0013");
        redirect_header("main.php?op=listBrokenLinks",1,_MD_MXDIR_BROKENDELETED);
    exit();
}

 function listModReq()
{
        global $xoopsDB, $eh;
        $result = $xoopsDB->query("select requestid, lid, modifysubmitter from ".$xoopsDB->prefix("xdir_mod")." group by lid order by requestid DESC");
        $totalmodrequests = $xoopsDB->getRowsNum($result);
    //	xoops_cp_header();
    echo"<table width='100%' border='0' cellspacing='1' class='outer'>"
    ."<tr class=\"head\"><td>";
        echo "<h4>"._MD_MXDIR_USERMODREQ." ($totalmodrequests)</h4><br />";

        if ( $totalmodrequests == 0 ) {
        echo _MD_MXDIR_NOSUBMITTED;
        } else {
        echo "<center><span style='font-size:xx-small;'>
		<span align='left';>"._MD_MXDIR_IGNOREDESC."<br />
		"._MD_MXDIR_DISPDESC."</span>
		</span></center>";
        echo "<table align=\"center\" width=\"90%\">";
                echo "
				<tr class=\"even\"><span style=\"font-weight: bold;\">
				<td>" ._MD_MXDIR_TITLE."</td>
				<td>" ._MD_MXDIR_SUBMITTER."</td>
				<td>" ._MD_MXDIR_OWNER."</td>
				<td>" ._MD_MXDIR_IGNORE."</td>
				<td>" ._EDIT."</td>
				<td>" ._MD_MXDIR_DISP."</td>
				</span></tr>";

                while ( list($requestid, $lid, $modifysubmitter)=$xoopsDB->fetchRow($result) ) {
            $result2 = $xoopsDB->query("select title, submitter from ".$xoopsDB->prefix("xdir_links")." where lid=$lid");
//echo $modifysubmitter;
//echo ""._MD_MXDIR_UPANDDOWN."";
//echo $requestid;
            if ( $modifysubmitter != 0 ) {
                $result3 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$modifysubmitter'");
                list($uname, $email)=$xoopsDB->fetchRow($result3);
            }
                list($title, $ownerid)=$xoopsDB->fetchRow($result2);
            //			$url=urldecode($url);
                $result4 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
                list($owner, $owneremail)=$xoopsDB->fetchRow($result4);
                echo "<tr class=\"odd\"><td>$title</td>";
                if ( $email=='' ) {
                echo "<td>".$uname."";
            } else {
                echo "<td><a href=\"mailto:".$email."\">".$uname."</a>";
            }
                echo "</td>";
                if ( $owneremail == '' ) {
                echo "<td>".$owner."";
            } else {
                echo "<td><a href=\"mailto:".$owneremail."\">".$owner."</a>";
            }

            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=ignoreModReq&amp;requestid=$requestid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=modLink&amp;lid=$lid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td><td align='center'>\n";
            echo myTextForm("main.php?op=doModReq&amp;lid=$lid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , "X");
            echo "</td></tr>\n";
            
            }
        echo "</table>";
        }

    echo"</td></tr></table>";
    xoops_cp_footer();
}

function doModReq()
{
    global $xoopsDB, $myts, $eh, $mytree, $xoopsModuleConfig, $mydirname, $_GET;
//XoopsSecurity Check
    if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
        print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

        return;
    }
        $lid= !empty($_GET['lid']) ? intval($_GET['lid']) : 0 ;
        $result = $xoopsDB->query("select * from ".$xoopsDB->prefix("xdir_mod")." where lid=$lid order by requestid");
        $totalmodrequests = $xoopsDB->getRowsNum($result);
  echo "<table>";
    echo "<tr><td colspan='2' align='left'><h4>"._MD_MXDIR_WEBLINKSCONF."</h4></td></tr>";
//	echo "<table><tr><td valign=top width=45 >";

    if ( $totalmodrequests > 0 ) {
    
        $lookup_lid = array();
            while ( list($requestid, $lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs , $sunhrs, $phone , $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $premium, $description, $modifysubmitter)=$xoopsDB->fetchRow($result) ) {
        echo "<tr><td valign='top'><table><tr><td valign='top' width=45%>";
            $lookup_lid[$requestid] = $lid;
            $result2 = $xoopsDB->query("select cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, premium, submitter from ".$xoopsDB->prefix("xdir_links")." where lid=$lid");
            list($origcid, $origtitle, $origaddress, $origaddress2, $origcity, $origstate, $origzip, $origcountry, $origmfhrs, $origsathrs, $origsunhrs, $origphone, $origfax, $origmobile, $orighome, $origtollfree, $origemail, $origurl, $origadmcontname, $origadmcontnumb, $origlogourl, $origpremium, $ownerid)=$xoopsDB->fetchRow($result2);
            $result2 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_text")." where lid=$lid");
            list($origdescription) = $xoopsDB->fetchRow($result2);
            $result3 = $xoopsDB->query("select description from ".$xoopsDB->prefix("xdir_mod")." where lid=$lid");
            list($description) = $xoopsDB->fetchRow($result3);
            $result7 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$modifysubmitter'");
            $result8 = $xoopsDB->query("select uname, email from ".$xoopsDB->prefix("users")." where uid='$ownerid'");
            $cidtitle=$mytree->getPathFromId($cid, "title");
            $origcidtitle=$mytree->getPathFromId($origcid, "title");
            list($submitter, $submitteremail)=$xoopsDB->fetchRow($result7);
            list($owner, $owneremail)=$xoopsDB->fetchRow($result8);
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
            // ?? ==thinking===> use original image file to prevent users from changing screen shots file
            $origlogourl = $myts->htmlSpecialChars($origlogourl);
            $admcontname = $myts->htmlSpecialChars($admcontname);
            $admcontnumb = $myts->htmlSpecialChars($admcontnumb);
            $logourl = $myts->htmlSpecialChars($logourl);
            $premium = $myts->htmlSpecialChars($premium);
            $origpremium = $myts->htmlSpecialChars($origpremium);
            $premopts = getlvlselects();
            $premdsp=($premopts[$premium]);
            $origpremdsp=($premopts[$origpremium]);
            $origdescription = $myts->makeTareaData4Show($origdescription);
            $description = $myts->makeTareaData4Show($description);
//			$origdescription = $myts->makeTareaData4Show($description);
                if ( $owner == "" ) {
                $owner="administration";
                }
                
                $listmodreqorigform = new XoopsThemeForm(_MD_MXDIR_ORIGINAL, 'listmodreqform', $_SERVER['PHP_SELF'], 'POST');
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_DESCRIPTIONC , "<span style='font-size:xx-small;'>".$origdescription."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_SITETITLE , "<span style='font-size:xx-small;'>".$origtitle."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADDRESS , "<span style='font-size:xx-small;'>".$origaddress."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADDRESS2 , "<span style='font-size:xx-small;'>".$origaddress2."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSCITY , "<span style='font-size:xx-small;'>".$origcity."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSTATE , "<span style='font-size:xx-small;'>".$origstate."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSCOUNTRY , "<span style='font-size:xx-small;'>".$origcountry."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSMFHRS , "<span style='font-size:xx-small;'>".displayTime($origmfhrs)."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSATHRS , "<span style='font-size:xx-small;'>".displayTime($origsathrs)."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSUNHRS , "<span style='font-size:xx-small;'>".displayTime($origsunhrs)."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSPHONE , "<span style='font-size:xx-small;'>".$origphone."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSFAX , "<span style='font-size:xx-small;'>".$origfax."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSMOBILE , "<span style='font-size:xx-small;'>".$origmobile."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSHOME , "<span style='font-size:xx-small;'>".$orighome."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSTOLLFREE , "<span style='font-size:xx-small;'>".$origtollfree."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSEMAIL , "<span style='font-size:xx-small;'>".$origemail."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_SITEURL , "<span style='font-size:xx-small;'>".$origurl."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADMCONTNAME , "<span style='font-size:xx-small;'>".$origadmcontname."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADMCONTNUMB , "<span style='font-size:xx-small;'>".$origadmcontnumb."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_CATEGORYC , "<span style='font-size:xx-small;'>".$origcidtitle."</span>"));
                $listmodreqorigform->addElement(new XoopsFormLabel(_MD_MXDIR_PREMIUM , "<span style='font-size:xx-small;'>".$origpremdsp."&nbsp;(".$origpremium.")</span>"));
                    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
                    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='../images/shots/" . $origlogourl . "' name='imagex' id='imagex' alt='' />" ) );
                $listmodreqorigform->addElement($imgtray);
                
                $listmodreqorigform->display();
        
                echo "</td>";
                echo "<td width=45%>";
                $listmodreqform = new XoopsThemeForm(_MD_MXDIR_PROPOSED, 'listmodreqform', $_SERVER['PHP_SELF'], 'POST', true);
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_DESCRIPTIONC , "<span style='font-size:xx-small;'>".$description."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_SITETITLE , "<span style='font-size:xx-small;'>".$title."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADDRESS , "<span style='font-size:xx-small;'>".$address."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADDRESS2 , "<span style='font-size:xx-small;'>".$address2."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSCITY , "<span style='font-size:xx-small;'>".$city."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSTATE , "<span style='font-size:xx-small;'>".$state."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSCOUNTRY , "<span style='font-size:xx-small;'>".$country."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSMFHRS , "<span style='font-size:xx-small;'>".displayTime($mfhrs)."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSATHRS , "<span style='font-size:xx-small;'>".displayTime($sathrs)."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSSUNHRS , "<span style='font-size:xx-small;'>".displayTime($sunhrs)."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSPHONE , "<span style='font-size:xx-small;'>".$phone."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSFAX , "<span style='font-size:xx-small;'>".$fax."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSMOBILE , "<span style='font-size:xx-small;'>".$mobile."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSHOME , "<span style='font-size:xx-small;'>".$home."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSTOLLFREE , "<span style='font-size:xx-small;'>".$tollfree."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSEMAIL , "<span style='font-size:xx-small;'>".$email."</span>"));
                
              if ( (trim($url) == '') || (trim($url) == "http://") ) {
                $newurl = "<span style='font-size:xx-small;'>".$url."</span>";
                } else {
                $newurl = "<span style='font-size:xx-small;'>".$url."&nbsp;[&nbsp;<a href=".$url." target='_blank'>".strtoupper(_MD_MXDIR_VISIT)."</a></span>&nbsp;]";
                }
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_SITEURL , $newurl));

                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADMCONTNAME , "<span style='font-size:xx-small;'>".$admcontname."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_BUSADMCONTNUMB , "<span style='font-size:xx-small;'>".$admcontnumb."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_CATEGORYC , "<span style='font-size:xx-small;'>".$cidtitle."</span>"));
                $listmodreqform->addElement(new XoopsFormLabel(_MD_MXDIR_PREMIUM , "<span style='font-size:xx-small;'>".$premdsp."&nbsp;(".$premium.")</span>"));
                    $imgtray = new XoopsFormElementTray(_MD_MXDIR_SHOTIMAGE,'');
                    $imgtray -> addElement( new XoopsFormLabel( '', "<img src='../images/shots/" . $logourl . "' name='imagex' id='imagex' alt='' />" ) );
                $listmodreqform->addElement($imgtray);
                
                $listmodreqform->display();
            echo "</td></tr></table>";
            
            echo "<table align=center width=450><tr>";
                if ( $submitteremail == "" ) {
                echo "<td align=left><small>"._MD_MXDIR_SUBMITTER."$submitter</small></td>";
            } else {
                echo "<td align=left><small>"._MD_MXDIR_SUBMITTER."<a href=mailto:".$submitteremail.">".$submitter."</a></small></td>";
            }
            if ( $owneremail == "" ) {
                echo "<td align=left><small>"._MD_MXDIR_OWNER."".$owner."</small></td>";
            } else {
                echo "<td align=left><small>"._MD_MXDIR_OWNER."<a href=mailto:".$owneremail.">".$owner."</a></small></td><td></td>";
            }

            echo "</tr>";
            echo "<tr><td><small>\n";
            echo "<table><tr><td>\n";
//ADDING XOOPSSECURITY with alternate (non-xoopsform token)
            echo myTextForm("main.php?op=changeModReq&amp;requestid=$requestid&amp;change_owner=0&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , _MD_MXDIR_APPROVE);
            echo "</td><td>\n";
            echo myTextForm("main.php?op=modLink&amp;lid=$lookup_lid[$requestid]&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."", _EDIT);
            echo "</td><td>\n";
            echo myTextForm("main.php?op=ignoreModReq&amp;requestid=$requestid&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."", _MD_MXDIR_IGNORE);
            echo "</td></tr></table>\n";
            echo "</small></td></tr>\n";
            echo "<tr><td>\n";
            echo myTextForm("main.php?op=changeModReq&amp;requestid=$requestid&amp;change_owner=1&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."" , _MD_MXDIR_APPROVECHANGE.' '.$submitter);
      echo "</tr></td></table><br /><br /></td></tr>";
            }
    } else {
        echo "<tr><td>"._MD_MXDIR_NOMODREQ."</td></tr>";
    }
    echo "</table>";
    xoops_cp_footer();
}

function changeModReq()
{
    global $xoopsDB, $_GET, $_POST, $eh, $myts;
//XoopsSecurity Check (true, $_REQUEST['t']) for button pass
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }

        $requestid = $_GET['requestid'];
        $changeowner = $_GET['change_owner'];
    $query = "select requestid, lid, cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, premium, description, modifysubmitter from ".$xoopsDB->prefix("xdir_mod")." where requestid=".$requestid."";
        $result = $xoopsDB->query($query);
        while ( list($requestid, $lid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $premium, $description, $modifysubmitter)=$xoopsDB->fetchRow($result) ) {
        if ( get_magic_quotes_runtime() ) {
            $title = stripslashes($title);
            $address = stripslashes($address);
            $address2 = stripslashes($address2);
            $city = stripslashes($city);
            $state = stripslashes($state);
            $zip = stripslashes($zip);
            $country = stripslashes($country);
            $mfhrs = stripslashes($mfhrs);
            $sathrs = stripslashes($sathrs);
            $sunhrs = stripslashes($sunhrs);
            $phone = stripslashes($phone);
            $fax = stripslashes($fax);
            $mobile = stripslashes($mobile);
            $home = stripslashes($home);
            $tollfree = stripslashes($tollfree);
            $email = stripslashes($email);
            $url = stripslashes($url);
            $admcontname = stripslashes($admcontname);
            $admcontnumb = stripslashes($admcontnumb);
            $logourl = stripslashes($logourl);
            $premium = stripslashes($premium);
            $description = stripslashes($description);
            $modifysubmitter = stripslashes($modifysubmitter);
        }
            $title = addslashes($title);
            $address = addslashes($address);
            $address2 = addslashes($address2);
            $city = addslashes($city);
            $state = addslashes($state);
            $zip = addslashes($zip);
            $country = addslashes($country);
            $mfhrs = addslashes($mfhrs);
            $sathrs = addslashes($sathrs);
            $sunhrs = addslashes($sunhrs);
            $phone = addslashes($phone);
            $fax = addslashes($fax);
            $mobile = addslashes($mobile);
            $home = addslashes($home);
            $tollfree = addslashes($tollfree);
            $email = addslashes($email);
            $url = addslashes($url);
            $admcontname = addslashes($admcontname);
            $admcontnumb = addslashes($admcontnumb);
            $logourl = addslashes($logourl);
            $premium = addslashes($premium);
            $description = addslashes($description);
            $modifysubmitter = addslashes($modifysubmitter);
        if ($changeowner == 1){
            $sql = sprintf("UPDATE %s SET cid = %u, title = '%s', address = '%s', address2 = '%s', city = '%s', state = '%s', zip = '%s', country = '%s', mfhrs = '%s', sathrs = '%s', sunhrs = '%s', phone = '%s', fax = '%s', mobile = '%s', home = '%s', tollfree = '%s', email = '%s', url = '%s', admcontname = '%s', admcontnumb = '%s', logourl = '%s', premium = '%u', submitter = '%u', status = %u, date = %u WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $premium, $modifysubmitter, 2, time(), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("UPDATE %s SET description = '%s' WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $description, $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("xdir_mod"), $requestid);
            $xoopsDB->query($sql) or $eh->show("0013");
        } else {
            $sql = sprintf("UPDATE %s SET cid = %u, title = '%s', address = '%s', address2 = '%s', city = '%s', state = '%s', zip = '%s', country = '%s', mfhrs = '%s', sathrs = '%s', sunhrs = '%s', phone = '%s', fax = '%s', mobile = '%s', home = '%s', tollfree = '%s', email = '%s', url = '%s', admcontname = '%s', admcontnumb = '%s', logourl = '%s', premium = '%u', status = %u, date = %u WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $cid, $title, $address, $address2, $city, $state, $zip, $country,$mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $premium, 2, time(), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("UPDATE %s SET description = '%s' WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $description, $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("xdir_mod"), $requestid);
            $xoopsDB->query($sql) or $eh->show("0013");
            }
    }
        redirect_header("main.php?op=listModReq",1,_MD_MXDIR_DBUPDATED);
    exit();
}

function ignoreModReq()
{
    global $xoopsDB, $_GET, $eh;
    //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
    $sql = sprintf("DELETE FROM %s WHERE requestid = %u", $xoopsDB->prefix("xdir_mod"), $_GET['requestid']);
        $xoopsDB->query($sql) or $eh->show("0013");
        redirect_header("main.php?op=listModReq",1,_MD_MXDIR_MODREQDELETED);
    exit();
}

function modLinkS()
{
        global $xoopsDB, $_POST, $myts, $eh, $xoopsModuleConfig, $_FILES, $mydirname;
//print_r ($_POST);
//exit();
//XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
        $cid = $_POST["cid"];
    if ( ($_POST["title"]) || ($_POST["title"]!="") ) {
    }
    if (!empty($_FILES["logoup"]["name"])){
        $thislogo = $_FILES["logoup"]["name"];
        $logourl = $myts->addSlashes($thislogo);
        }
        else {
                $logourl = $myts->addSlashes($_POST["logourl"]);
        }
    $title = $myts->addSlashes($_POST["title"]);
    $address = $myts->addSlashes($_POST["address"]);
    $address2 = $myts->addSlashes($_POST["address2"]);
    $city = $myts->addSlashes($_POST["city"]);
    $state = $myts->addSlashes($_POST["state"]);
    $zip = $myts->addSlashes($_POST["zip"]);
    $country = $myts->addSlashes($_POST["country"]);
    $mfhrs = $myts->addSlashes($_POST["mfhrs"]);
    $sathrs = $myts->addSlashes($_POST["sathrs"]);
    $sunhrs = $myts->addSlashes($_POST["sunhrs"]);
    $phone = $myts->addSlashes($_POST["phone"]);
    $fax = $myts->addSlashes($_POST["fax"]);
    $mobile = $myts->addSlashes($_POST["mobile"]);
    $home = $myts->addSlashes($_POST["home"]);
    $tollfree = $myts->addSlashes($_POST["tollfree"]);
    $email = $myts->addSlashes($_POST["email"]);
    $url = $myts->addSlashes($_POST["url"]);
    $admcontname = $myts->addSlashes($_POST["admcontname"]);
    $admcontnumb = $myts->addSlashes($_POST["admcontnumb"]);
    $premium = $myts->addSlashes($_POST["premium"]);
    $description = $myts->addSlashes($_POST["moddesc"]);
    $xoopsDB->query("update ".$xoopsDB->prefix("xdir_links")." set cid='$cid', title='$title', address='$address', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', mfhrs='$mfhrs', sathrs='$sathrs', sunhrs='$sunhrs', phone='$phone', fax='$fax', mobile='$mobile', home='$home', tollfree='$tollfree', email='$email', url='$url', admcontname='$admcontname', admcontnumb='$admcontnumb', logourl='$logourl', status=2, date=".time().", premium='$premium' where lid=".$_POST['lid']."")  or $eh->show("0013");
        $xoopsDB->query("update ".$xoopsDB->prefix("xdir_text")." set description='$description' where lid=".$_POST['lid']."")  or $eh->show("0013");
//Uploader
    $domain = XOOPS_URL;
    $path = '../images/shots/';   //path to targetfolder
    $path_after_domain = '/modules/' . $mydirname . '/images/shots/';   //path to targetfolder for use in url
    $max_size = $xoopsModuleConfig['logo_maxfilesize'];          //maximum filesize

    if (!isset($_FILES['logoup'])) exit;
    if (is_uploaded_file($_FILES['logoup']['tmp_name'])) {
    if ($_FILES['logoup']['size']>$max_size) {
            echo "<font style=\"font-color: #333333; font-family: Geneva, Arial, Helvetica, sans-serif;\">"._MD_MXDIR_ELOGOSIZE."</font><br />\n"; exit; }
    if (($_FILES['logoup']['type']=="image/gif") || ($_FILES['logoup']['type']=="image/png") || ($_FILES['logoup']['type']=="image/jpeg")) {
            if (file_exists($path . $_FILES['logoup']['name'])) {
                    echo "<font style=\"font-color: #333333; font-family: Geneva, Arial, Helvetica, sans-serif;\">"._MD_MXDIR_ELOGOSAMENAME."</font><br />\n"; exit; }
            $res = copy($_FILES['logoup']['tmp_name'], $path .$_FILES['logoup']['name']);
           if (!$res) { echo "<font style=\"font-color: #333333; font-family: Geneva, Arial, Helvetica, sans-serif;\">"._MD_MXDIR_ELOGOTEMP."</font><br />\n"; exit; } else {
     }
    echo "<font style=\"font-color: #333333; font-family: Geneva, Arial, Helvetica, sans-serif;\"><hr />";
    echo "Name: ".$_FILES['logoup']['name']."<br />\n";
    echo "Size: ".$_FILES['logoup']['size']." bytes<br />\n";
    echo "Type: ".$_FILES['logoup']['type']."<br />\n";
    echo "</font>";
    echo "<br /><br /><img src=\"http://".$domain."/".$path.$_FILES['logoup']['name']."\" alt=\"\" />";
    } else { echo "<font style=\"font-color: #333333; font-family: Geneva, Arial, Helvetica, sans-serif;\">"._MD_MXDIR_ELOGOTYPE."</font><br />\n"; exit; }

    }

    redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_DBUPDATED);
    exit();
}

function delLink()
{
    global $xoopsDB, $_POST, $eh, $xoopsModule;
//XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }

    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $_POST['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $_POST['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $_POST['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_coupon"), $_POST['lid']);
    $xoopsDB->query($sql) or $eh->show("0013");
    // delete comments
    xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['lid']);
    // delete notifications
    xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'link', $_POST['lid']);

        redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_LINKDELETED);
    exit();
}

function modCat()
{
    global $xoopsDB, $_POST, $myts, $eh, $mytree;
        $cid = $_POST["cid"];
        $pid = $_POST["pid"];
    $result=$xoopsDB->query("select pid, title, imgurl from ".$xoopsDB->prefix("xdir_cat")." where cid=$cid");
    list($pid,$title,$imgurl) = $xoopsDB->fetchRow($result);
    $title = $myts->htmlSpecialChars($title);
    $imgurl = $myts->htmlSpecialChars($imgurl);
    $mypid = $pid;
    
    $modcatform = new XoopsThemeForm(_MD_MXDIR_MODCAT, 'modcatform', $_SERVER['PHP_SELF'], 'POST', true);
    $modcatform->addElement(new XoopsFormText(_MD_MXDIR_TITLEC , 'title', 50, 100, $title));
    $modcatform->addElement(new XoopsFormText(_MD_MXDIR_IMGURLMAIN , 'imgurl', 50, 100, $imgurl));
    
    $select_modcats = (new XoopsFormSelect(_MD_MXDIR_PARENT , 'pid', $pid, 1, false));
    $tree = $mytree->getChildTreeArray(0,"title ASC");
    foreach ($tree as $branch ) { $branch['prefix'] = substr($branch['prefix'], 0, -1);
    $branch['prefix'] = str_replace(".","--",$branch['prefix']);
    $select_modcats -> addOption($branch['cid'],$branch['prefix'].$branch['title']);}

    $modcatform->addElement($select_modcats);
    
    $modcatform->addElement(new XoopsFormHidden('cid', $cid));
    $modcatform->addElement(new XoopsFormHidden('pid', $pid));
    
    $wraptray = new XoopsFormElementTray(_MD_MXDIR_MODIFY,'');
    
    $deltray = new XoopsFormElementTray('','del');
    $dbtn=new XoopsFormButton('', '', _MD_MXDIR_DELETE, 'button');
    $dbtn->setExtra("onClick=\"location='main.php?pid=$pid&amp;cid=$cid&amp;op=delCat&amp;t=".$GLOBALS['xoopsSecurity']->createToken()."'\"");
    $deltray->addElement($dbtn);
    
    $regtray = new XoopsFormElementTray('','');
    $sbtn=new XoopsFormButton('', '', _MD_MXDIR_MODIFY, 'submit');

    $regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));
    $regtray->addElement(new XoopsFormHidden('op', 'modCatS'));
    $regtray->addElement($sbtn);
    
    $wraptray->addElement($regtray);
    $wraptray->addElement($deltray);

    $modcatform->addElement($wraptray);
    $modcatform->display();
        xoops_cp_footer();
}

function modCatS()
{
        global $xoopsDB, $_POST, $myts, $eh;
        //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
        $cid =  $_POST['cid'];
        $pid =  $_POST['pid'];
        $title =  $myts->addSlashes($_POST['title']);
    if (empty($title)) {
        redirect_header("index.php", 2, _MD_MXDIR_ERRORTITLE);
    }
    if ( ($_POST["imgurl"]) || ($_POST["imgurl"]!="") ) {
        $imgurl = $myts->addSlashes($_POST["imgurl"]);
    }
    $xoopsDB->query("update ".$xoopsDB->prefix("xdir_cat")." set pid=$pid, title='$title', imgurl='$imgurl' where cid=$cid") or $eh->show("0013");
        redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_DBUPDATED);
}

function delCat()
{
        global $xoopsDB, $_GET, $_POST, $eh, $mytree, $xoopsModule;
        //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
        $cid =  isset($_POST['cid']) ? intval($_POST['cid']) : intval($_GET['cid']);
        $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
        if ( $ok == 1 ) {
        //get all subcategories under the specified category
        $arr=$mytree->getAllChildId($cid);
        $dcount=count($arr);
        for ( $i=0;$i<$dcount;$i++ ) {
            //get all links in each subcategory
            $result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("xdir_links")." where cid=".$arr[$i]."") or $eh->show("0013");
            //now for each link, delete the text data and vote ata associated with the link
            while ( list($lid)=$xoopsDB->fetchRow($result) ) {
                $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $lid);
                $xoopsDB->query($sql) or $eh->show("0013");
                $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $lid);
                $xoopsDB->query($sql) or $eh->show("0013");
                $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
                $xoopsDB->query($sql) or $eh->show("0013");
                $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_coupon"), $lid);
                $xoopsDB->query($sql) or $eh->show("0013");
                xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
                xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
            }
            xoops_notification_deltebyitem($xoopsModule->getVar('mid'), 'category', $arr[$i]);

            //all links for each subcategory is deleted, now delete the subcategory data
            $sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("xdir_cat"), $arr[$i]);
            $xoopsDB->query($sql) or $eh->show("0013");
        }
        //all subcategory and associated data are deleted, now delete category data and its associated data
        $result=$xoopsDB->query("select lid from ".$xoopsDB->prefix("xdir_links")." where cid=".$cid."") or $eh->show("0013");
        while ( list($lid)=$xoopsDB->fetchRow($result) ) {
            $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_votedata"), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_coupon"), $lid);
            $xoopsDB->query($sql) or $eh->show("0013");
            // delete comments
            xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
            // delete notifications
            xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
        }
        $sql = sprintf("DELETE FROM %s WHERE cid = %u", $xoopsDB->prefix("xdir_cat"), $cid);
        $xoopsDB->query($sql) or $eh->show("0013");
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $cid);
                redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_CATDELETED);
        exit();
        } else {
        //	xoops_cp_header();
        xoops_confirm(array('op' => 'delCat', 'cid' => $cid, 'ok' => 1), 'index.php', _MD_MXDIR_WARNING);
        xoops_cp_footer();
        }
}

function delNewLink()
{
    global $xoopsDB, $_POST, $eh, $xoopsModule;
    //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_links"), $_POST['lid']);
        $xoopsDB->query($sql) or $eh->show("0013");
    $sql = sprintf("DELETE FROM %s WHERE lid = %u", $xoopsDB->prefix("xdir_text"), $_POST['lid']);
        $xoopsDB->query($sql) or $eh->show("0013");
    // delete comments
    xoops_comment_delete($xoopsModule->getVar('mid'), $_POST['lid']);
    // delete notifications
    xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $_POST['lid']);
    redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_LINKDELETED);
}

function addCat()
{
    global $xoopsDB, $_POST, $myts, $eh, $mydirname;
    //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
        $pid = $_POST["cid"];
        $title = $myts->addSlashes($_POST["title"]);
    if (empty($title)) {
        redirect_header("index.php",2,_MD_MXDIR_ERRORTITLE);
        exit();
    }
        if ( ($_POST["imgurl"]) || ($_POST["imgurl"]!="") ) {
        //		$imgurl = $myts->formatURL($_POST["imgurl"]);
        //		$imgurl = urlencode($imgurl);
        $imgurl = $myts->addSlashes($_POST["imgurl"]);
    }
    $newid = $xoopsDB->genId($xoopsDB->prefix("xdir_cat")."_cid_seq");
    $sql = sprintf("INSERT INTO %s (cid, pid, title, imgurl) VALUES (%u, %u, '%s', '%s')", $xoopsDB->prefix("xdir_cat"), $newid, $pid, $title, $imgurl);
    $xoopsDB->query($sql) or $eh->show("0013");
    if ($newid == 0) {
        $newid = $xoopsDB->getInsertId();
    }
    global $xoopsModule;
    $tags = array();
    $tags['CATEGORY_NAME'] = $title;
    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/'.$mydirname.'/viewcat.php?cid=' . $newid;
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'new_category', $tags);
    redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_NEWCATADDED);
}

function addLink()
{
    global $xoopsConfig, $xoopsDB, $myts, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $eh, $_POST, $_FILES, $mydirname;
//print_r ($_POST);
//exit();
        //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }

        if (!empty($_FILES["logoup"]["name"])){
        $thislogo = $_FILES["logoup"]["name"];
        $logourl = $myts->addSlashes($thislogo);
        }
        else {
                $logourl = $myts->addSlashes($_POST["logourl"]);
        }
        $title = $myts->addSlashes($_POST["title"]);
        $address = $myts->addSlashes($_POST["address"]);
        $address2 = $myts->addSlashes($_POST["address2"]);
        $city = $myts->addSlashes($_POST["city"]);
        $state = $myts->addSlashes($_POST["state"]);
        $zip = $myts->addSlashes($_POST["zip"]);
        $country = $myts->addSlashes($_POST["country"]);
        $mfhrs = $myts->addSlashes($_POST["mfhrs"]);
        $sathrs = $myts->addSlashes($_POST["sathrs"]);
        $sunhrs = $myts->addSlashes($_POST["sunhrs"]);
        $phone = $myts->addSlashes($_POST["phone"]);
        $fax = $myts->addSlashes($_POST["fax"]);
        $mobile = $myts->addSlashes($_POST["mobile"]);
        $home = $myts->addSlashes($_POST["home"]);
        $tollfree = $myts->addSlashes($_POST["tollfree"]);
        $email = $myts->addSlashes($_POST["email"]);
        $url = $myts->addSlashes($_POST["url"]);
        $admcontname = $myts->addSlashes($_POST["admcontname"]);
        $admcontnumb = $myts->addSlashes($_POST["admcontnumb"]);
        $premium = $myts->addSlashes($_POST["premium"]);
        $description = $myts->addSlashes($_POST["adddesc"]);
        $submitter = $xoopsUser->uid();
        $result = $xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where title='$title'");
        list($numrows) = $xoopsDB->fetchRow($result);
    $errormsg = "";
    $error = 0;
        if ( $numrows > 0 ) {
        $errormsg .= "<h4 style='color: #ff0000'>";
        $errormsg .= _MD_MXDIR_ERROREXIST."</h4>";
        $error = 1;
        }
    // Check if Title exist
        if ( $title == "" ) {
        $errormsg .= "<h4 style='color: #ff0000'>";
        $errormsg .= _MD_MXDIR_ERRORTITLE."</h4>";
            $error =1;
        }

        if ( $error == 1 ) {
        echo $errormsg;
        xoops_cp_footer();
        exit();
        }
        if ( !empty($_POST['cid']) ) {
        $cid = $_POST['cid'];
    } else {
        $cid = 0;
    }
    $newid = $xoopsDB->genId($xoopsDB->prefix("xdir_links")."_lid_seq");
    $sql = sprintf("INSERT INTO %s (lid, cid, title, address, address2, city, state, zip, country, mfhrs, sathrs, sunhrs, phone, fax, mobile, home, tollfree, email, url, admcontname, admcontnumb, logourl, submitter, status, date, hits, rating, votes, comments, premium) VALUES (%u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s', %u, %u, %u, %u, %u, %u, %u, %u)", $xoopsDB->prefix("xdir_links"), $newid, $cid, $title, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $admcontname, $admcontnumb, $logourl, $submitter, 1, time(), 0, 0, 0, 0, $premium);
    $xoopsDB->query($sql) or $eh->show("0013");
    if ( $newid == 0 ) {
        $newid = $xoopsDB->getInsertId();
    }
    $sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix("xdir_text"), $newid, $description);
    $xoopsDB->query($sql) or $eh->show("0013");
    $tags = array();
        $tags['LINK_NAME'] = $title;
        $tags['LINK_URL'] = XOOPS_URL . '/modules/'.$mydirname.'/singlelink.php?cid=' . $cid . '&amp;lid=' . $newid;
        $sql = "SELECT title FROM " . $xoopsDB->prefix("xdir_cat") . " WHERE cid=" . $cid;
        $result = $xoopsDB->query($sql);
        $row = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_NAME'] = $row['title'];
        $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/'.$mydirname.'/viewcat.php?cid=' . $cid;
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
    $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);

//Uploader
    $domain = XOOPS_URL;
    $path = '../images/shots/';   //path to targetfolder
    $path_after_domain = '/modules/' . $mydirname . '/images/shots/';   //path to targetfolder for use in url
    $max_size = $xoopsModuleConfig['logo_maxfilesize'];          //maximum filesize

    if (!isset($_FILES['logoup'])) exit;
    if (is_uploaded_file($_FILES['logoup']['tmp_name'])) {
    if ($_FILES['logoup']['size']>$max_size) {
            echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOSIZE."</font><br />\n"; exit; }
    if (($_FILES['logoup']['type']=="image/gif") || ($_FILES['logoup']['type']=="image/png") || ($_FILES['logoup']['type']=="image/jpeg")) {
            if (file_exists($path . $_FILES['logoup']['name'])) {
                    echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOSAMENAME."</font><br />\n"; exit; }
            $res = copy($_FILES['logoup']['tmp_name'], $path .$_FILES['logoup']['name']);
           if (!$res) { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOTEMP."</font><br />\n"; exit; } else {
     }
    echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\"><hr />";
    echo "Name: ".$_FILES['logoup']['name']."<br />\n";
    echo "Size: ".$_FILES['logoup']['size']." bytes<br />\n";
    echo "Type: ".$_FILES['logoup']['type']."<br />\n";
    echo "</font>";
    echo "<br /><br /><img src=\"http://".$domain."/".$path.$_FILES['logoup']['name']."\" alt=\"\" />";
    } else { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOTYPE."</font><br />\n"; exit; }

    }

        redirect_header("main.php?op=linksConfigMenu",1,_MD_MXDIR_NEWLINKADDED);
}

function approve()
{
    global $xoopsConfig, $xoopsDB, $_POST, $_FILES, $myts, $eh, $xoopsModuleConfig, $mydirname;
        
        //XoopsSecurity Check
        if ((!$GLOBALS['xoopsSecurity']->check()) && (!$GLOBALS['xoopsSecurity']->check(true, $_REQUEST['t']))) {
            print _MD_MXDIR_SUBMITTER.'<br />'._MD_MXDIR_SECURITY_CODE.' '._MD_MXDIR_UPGRADEFAILED;

            return;
        }
        if (!empty($_FILES["logoup"]["name"])){
        $thislogo = $_FILES["logoup"]["name"];
        $logourl = $myts->addSlashes($thislogo);
        }
        else {
                $logourl = $myts->addSlashes($_POST["logourl"]);
        }
        $lid = $myts->addSlashes($_POST["lid"]);
        $cid = $myts->addSlashes($_POST["cid"]);
        $title = $myts->addSlashes($_POST["title"]);
        $address = $myts->addSlashes($_POST["address"]);
        $address2 = $myts->addSlashes($_POST["address2"]);
        $city = $myts->addSlashes($_POST["city"]);
        $state = $myts->addSlashes($_POST["state"]);
        $zip = $myts->addSlashes($_POST["zip"]);
        $country = $myts->addSlashes($_POST["country"]);
        $mfhrs = $myts->addSlashes($_POST["mfhrs"]);
        $sathrs = $myts->addSlashes($_POST["sathrs"]);
        $sunhrs = $myts->addSlashes($_POST["sunhrs"]);
        $phone = $myts->addSlashes($_POST["phone"]);
        $fax = $myts->addSlashes($_POST["fax"]);
        $mobile = $myts->addSlashes($_POST["mobile"]);
        $home = $myts->addSlashes($_POST["home"]);
        $tollfree = $myts->addSlashes($_POST["tollfree"]);
        $email = $myts->addSlashes($_POST["email"]);
        if (($_POST["url"]) || ($_POST["url"]!="")) {
        $url = $myts->addSlashes($_POST["url"]);
        }
        $url = $myts->addSlashes($_POST["url"]);
        $admcontname = $myts->addSlashes($_POST["admcontname"]);
        $admcontnumb = $myts->addSlashes($_POST["admcontnumb"]);
        $premium = $myts->addSlashes($_POST["premium"]);
        $description = $myts->addSlashes($_POST["description"]);

    $query = "update ".$xoopsDB->prefix("xdir_links")." set cid='$cid', title='$title', address='$address', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', mfhrs='$mfhrs', sathrs='$sathrs', sunhrs='$sunhrs', phone='$phone', fax='$fax', mobile='$mobile', home='$home', tollfree='$tollfree', email='$email', url='$url', admcontname='$admcontname', admcontnumb='$admcontnumb', logourl='$logourl', premium='$premium', status=1, date=".time()." where lid=".$lid."";
    $xoopsDB->query($query) or $eh->show("0013");
    $query = "update ".$xoopsDB->prefix("xdir_text")." set description='$description' where lid=".$lid."";
    $xoopsDB->query($query) or $eh->show("0013");
    global $xoopsModule, $mydirname;
    $tags=array();
        $tags['LINK_NAME'] = $title;
        $tags['LINK_URL'] = XOOPS_URL . '/modules/'.$mydirname.'/singlelink.php?cid=' . $cid . '&amp;lid=' . $lid;
    $sql = "SELECT title FROM " . $xoopsDB->prefix("xdir_cat") . " WHERE cid=" . $cid;
        $result = $xoopsDB->query($sql);
        $row = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_NAME'] = $row['title'];
        $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/'.$mydirname.'/viewcat.php?cid=' . $cid;
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'new_link', $tags);
    $notification_handler->triggerEvent('category', $cid, 'new_link', $tags);
    $notification_handler->triggerEvent('link', $lid, 'approve', $tags);

    if (!isset($_FILES['logoup'])) exit;
    if (is_uploaded_file($_FILES['logoup']['tmp_name'])) {
    if ($_FILES['logoup']['size']>$max_size) {
            echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOSIZE."</font><br />\n"; exit; }
    if (($_FILES['logoup']['type']=="image/gif") || ($_FILES['logoup']['type']=="image/png") || ($_FILES['logoup']['type']=="image/jpeg")) {
            if (file_exists($path . $_FILES['logoup']['name'])) {
                    echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOSAMENAME."</font><br />\n"; exit; }
            $res = copy($_FILES['logoup']['tmp_name'], $path .$_FILES['logoup']['name']);
           if (!$res) { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOTEMP."</font><br />\n"; exit; } else {
     }
    echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\"><hr />";
    echo "Name: ".$_FILES['logoup']['name']."<br />\n";
    echo "Size: ".$_FILES['logoup']['size']." bytes<br />\n";
    echo "Type: ".$_FILES['logoup']['type']."<br />\n";
    echo "</font>";
    echo "<br /><br /><img src=\"http://".$domain."/".$path.$_FILES['logoup']['name']."\" alt=\"\" />";
    } else { echo "<font color=\"#333333\" face=\"Geneva, Arial, Helvetica, sans-serif\">"._MD_MXDIR_ELOGOTYPE."</font><br />\n"; exit; }
    }
            redirect_header("main.php?op=listNewLinks",1,_MD_MXDIR_NEWLINKADDED);
}
if(!isset($_POST['op'])) {
    $op = isset($_GET['op']) ? $_GET['op'] : 'main';
} else {
    $op = $_POST['op'];
}
switch ($op) {
case "multicat":
    multicat();
    break;
case "multicatS":
    multicatS();
    break;
case "delNewLink":
    delNewLink();
    break;
case "approve":
    approve();
    break;
case "addCat":
    addCat();
    break;
case "addLink":
    addLink();
    break;
case "listBrokenLinks":
    listBrokenLinks();
    break;
case "delBrokenLinks":
    delBrokenLinks();
    break;
case "ignoreBrokenLinks":
    ignoreBrokenLinks();
    break;
case "listModReq":
    listModReq();
    break;
case "doModReq":
    doModReq();
    break;
case "changeModReq":
    changeModReq();
    break;
case "ignoreModReq":
    ignoreModReq();
    break;
case "delCat":
    delCat();
    break;
case "modCat":
    modCat();
    break;
case "modCatS":
    modCatS();
    break;
case "modLink":
    modLink();
    break;
case "modLinkS":
    modLinkS();
    break;
case "delLink":
    delLink();
    break;
case "delVote":
    delVote();
    break;
case "linksConfigMenu":
    linksConfigMenu();
    break;
case "listNewLinks":
    listNewLinks();
    break;
case 'main':
default:
    xdir();
    break;
}

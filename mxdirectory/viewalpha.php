<?php
// $Id: viewalpha.php 11970 2013-08-24 14:20:57Z beckmi $
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
include "header.php";
$mydirname = basename( dirname( __FILE__ ) ) ;
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

include_once XOOPS_ROOT_PATH."/class/pagenav.php";
global $xoopsDB, $xoopModuleConfig, $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');

include_once XOOPS_ROOT_PATH."/modules/$mydirname/class/coupon.php";
$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");

$xoopsOption['template_main'] = 'xdir_viewalpha.html';
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign('xoops_module_header', $xoops_module_header);
//Paging
$list = isset( $_GET['list'] ) ? $_GET['list'] : 0;
$start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;
$show = $xoopsModuleConfig['perpage'];
//Alpha List
$letters = letters();
$xoopsTpl->assign('letters', $letters);
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
//xoops_page
$show = $xoopsModuleConfig['perpage'];
//modify listing
$showmod = $xoopsModuleConfig['showmod'];
$xoopsTpl->assign('showmod', $showmod);

//Logo
if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['logo_maximgwidth']);
    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['logo_maximgwidth'] + 10);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('lang_noscreenshot', _MD_MXDIR_NOSHOTS);
}
//Pretty Page Titles
$pathstring = $mydirname.' - '._MD_MXDIR_MULTICAT.' - '._MD_MXDIR_SEARCHFOR.' - '.$list;
$xoopsTpl->assign('xoops_pagetitle', $pathstring);

if (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $isadmin = true;
} else {
    $isadmin = false;
}

$fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." WHERE status>0 AND (title LIKE '$list%')");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);

$navcheck = intval($numrows);
$page_nav = '';
$pcount = 0;

if($numrows>0){
    $xoopsTpl->assign('lang_description', _MD_MXDIR_DESCRIPTIONC);
    $xoopsTpl->assign('lang_lastupdate', _MD_MXDIR_LASTUPDATEC);
    $xoopsTpl->assign('lang_hits', _MD_MXDIR_HITSC);
    $xoopsTpl->assign('lang_rating', _MD_MXDIR_RATINGC);
    $xoopsTpl->assign('lang_ratethissite', _MD_MXDIR_RATETHISSITE);
    $xoopsTpl->assign('lang_reportbroken', _MD_MXDIR_REPORTBROKEN);
    $xoopsTpl->assign('lang_tellafriend', _MD_MXDIR_TELLAFRIEND);
    $xoopsTpl->assign('lang_modify', _MD_MXDIR_MODIFY);
    $xoopsTpl->assign('lang_category' , _MD_MXDIR_CATEGORYC);
    $xoopsTpl->assign('lang_visit' , _MD_MXDIR_VISIT);
    $xoopsTpl->assign('show_links', true);
	$xoopsTpl->assign('lang_comments' , _COMMENTS);
	$xoopsTpl->assign('lang_email', _MD_MXDIR_BUSEMAIL);
	$xoopsTpl->assign('lang_url', _MD_MXDIR_SITEURL);
	
  $sql = "select l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.mfhrs, l.sathrs, l.sunhrs, l.phone, l.fax, l.mobile, l.home, l.tollfree, l.email, l.url, l.logourl, l.submitter, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description FROM ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t WHERE l.lid=t.lid AND status>0 AND (title LIKE '$list%') ORDER BY title ASC";
  $result=$xoopsDB->query($sql, $show, $start);


    while(list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $submitter, $status, $time, $hits, $rating, $votes, $comments, $premium, $description) = $xoopsDB->fetchRow($result)) {

	$lvlopts = getPremiumOptions($premium);
	$pcounter = intval($lvlopts['9']);
	$pcount = ( $pcounter != 0 ) ? ($pcount + 1) : $pcount;

        if ($isadmin) {
            $adminlink = '<a href="'.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=modLink&amp;lid='.$lid.'"><img src="'. $pathIcon16 .'/edit.png"'.' border="0" alt="'._MD_MXDIR_EDITTHISLINK.'" /></a>';
        } else {
            $adminlink = '';
        }

//			include_once "include/functions.php";
			$mfhrs = displayTime($mfhrs);
			$sathrs = displayTime($sathrs);
			$sunhrs = displayTime($sunhrs);
			$bizhrs = array('1' => _MD_MXDIR_BUSMFHRSSHORT.$mfhrs,'2' => _MD_MXDIR_BUSSATHRSSHORT.$sathrs,'3' => _MD_MXDIR_BUSSUNHRSSHORT.$sunhrs,);
			$bnums = array($phone,$fax,$mobile,$home,$tollfree);
			$biznums = displaybiznums($bnums);

		 $is_owner = (!empty($xoopsUser) && ($xoopsUser->getvar('uid') == $submitter)) ? '1' : null ;
     
        if ($votes == 1) {
            $votestring = _MD_MXDIR_ONEVOTE;
        } else {
            $votestring = sprintf(_MD_MXDIR_NUMVOTES,$votes);
        }
		
        $path = $mytree->getPathFromId($cid, "title");
        $path = substr($path, 1);
        $path = str_replace("/"," <img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/arrow.gif' alt='' /> ",$path);
        $new = newlinkgraphic($time, $status);
        $pop = popgraphic($hits);
		  $ratingfl = (($rating/2) - floor(($rating/2)) < 0.5) ? intval(floor($rating/2)*10) : intval((floor($rating/2)+.5)*10) ;
	  	  $ratingfl = str_pad($ratingfl, 2, "0", STR_PAD_LEFT);
	      $rating = "<img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/ratings/rate". $ratingfl .".gif' alt='"._MD_MXDIR_RATINGC.number_format($rating, 2)."'/> ";
        
        
//Invoke & assign coupon
		$coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
        $coupons = $coupon_handler->getCountByLink($lid);
        $xoopsTpl->append('links', array('id' => $lid, 'cid' => $cid, 'url' => $url, 'rating' => $rating, 'lvlopts' => $lvlopts, 'titletrim' => $myts->htmlSpecialChars($ltitle),'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'address' => $myts->htmlSpecialChars($address), 'address2' => $myts->htmlSpecialChars($address2), 'city' => $myts->htmlSpecialChars($city), 'state' => $myts->htmlSpecialChars($state), 'zip' => $myts->htmlSpecialChars($zip), 'country' => $myts->htmlSpecialChars($country), 'bizhrs' => $bizhrs, 'biznums' => $biznums,'phone' => $myts->htmlSpecialChars($phone), 'fax' => $myts->htmlSpecialChars($fax), 'email' => $myts->htmlSpecialChars($email), 'category' => $path, 'logourl' => $myts->htmlSpecialChars($logourl), 'is_owner' => $is_owner, 'updated' => formatTimestamp($time,"m"), 'description' => $myts->displayTarea($description,0,1,1), 'coupons' => $coupons, 'adminlink' => $adminlink, 'hits' => $hits, 'comments' => $comments, 'premium' => $premium, 'votes' => $votestring, 'mail_subject' => rawurlencode(sprintf(_MD_MXDIR_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MXDIR_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid='.$cid.'&lid='.$lid)));
 //   	$xoopsTpl->append('links', array('bizhrs', $bizhrs));
 //    	$xoopsTpl->append('links', array('biznums', $biznums));
	$page_nav = new XoopsPageNav(intval($numrows) , intval($show), $start, 'start', 'list='.$list.'&amp;show='.$show);
    } 

	//Nav Invoke
	$start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;
	$page_nav = new XoopsPageNav(intval($numrows) , intval($show), $start, 'start', 'list='.$list.'&amp;show='.$show);
} 
$xoopsTpl->assign('pcount', $pcount);
//Nav assign
if ($navcheck > 0){
	$category['navbar'] = '' . $page_nav -> renderNav() . '';
	$xoopsTpl -> assign( 'category', $category);
	}
//Premium from admin
$xoopsTpl->assign('sponson', $xoopsModuleConfig['sponsor_active']);
$xoopsTpl->assign('p1color', $xoopsModuleConfig['premium_listing1col']);
$xoopsTpl->assign('p2color', $xoopsModuleConfig['premium_listing2col']);
$xoopsTpl->assign('p3color', $xoopsModuleConfig['premium_listing3col']);
$xoopsTpl->assign('p4color',$xoopsModuleConfig['premium_listing4col']);
$xoopsTpl->assign('p5color', $xoopsModuleConfig['premium_listing5col']);
//alphadetect for premiumheaders
$xoopsTpl->assign('isa', '1');
//mid autodetect
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
//search and alpha display from module preferences
$xoopsTpl->assign('usealpha', $xoopsModuleConfig['usealpha']);
$xoopsTpl->assign('usesearch', $xoopsModuleConfig['usesearch']);
//Smarty directory autodetect
$smartydir = $xoopsModule->getVar('dirname');
$xoopsTpl->assign('smartydir', $smartydir);
include XOOPS_ROOT_PATH.'/footer.php';
?>
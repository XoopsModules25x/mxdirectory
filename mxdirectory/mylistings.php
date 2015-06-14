<?php
// $Id: mylistings.php,v 1.00 2006/05/07 12:11:07
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

//include_once "include/functions.php";
include_once XOOPS_ROOT_PATH."/class/pagenav.php";
include_once "class/coupon.php";

global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');

$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object
$coupon_handler = new XdirectoryCouponHandler($xoopsDB);

$list = isset( $_GET['list'] ) ? $_GET['list'] : 0;
$start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;
$show = $xoopsModuleConfig['perpage'];

$lid = ((empty($_GET['lid'])) || (intval($_GET['lid']) == 0)) ? '' : intval($_GET['lid']) ;
if (!$xoopsUser) {
  redirect_header('./index.php',3,_NOAUTH);
} else {
  // now get user id & name
  $userid = $xoopsUser->getVar('uid');
  $user = XoopsUser::getUnameFromId($userid);
}

$xoopsOption['template_main'] = 'xdir_mylistings.html';
include XOOPS_ROOT_PATH."/header.php";

// set the page title
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($user)."&nbsp;"._MD_MXDIR_LISTINGS);

//modify listing flag
$showmod = $xoopsModuleConfig['showmod'];
$showmod = ($xoopsUser) ? $showmod : 0 ;
$xoopsTpl->assign('showmod', $showmod);

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $isadmin = true;
    $adminlink = '<a href="'.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=modLink&amp;lid='.$lid.'"><img src="'. $pathIcon16 .'/edit.png"'.' border="0" alt="'._MD_MXDIR_EDITTHISLINK.'" /></a>';
} else {
    $adminlink = '';
    $isadmin = false;
}

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);
if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['logo_maximgwidth']);
    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['logo_maximgwidth'] + 10);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('lang_noscreenshot', _MD_MXDIR_NOSHOTS);
}

$countresult1=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." WHERE submitter=$userid");
list($count1) = $xoopsDB->fetchRow($countresult1);

$fullcountresult = intval($count1);

$navcheck = intval($fullcountresult);
$numrows = $navcheck;
$page_nav = '';

$modarray = array();
$result = $xoopsDB->query("select lid FROM ".$xoopsDB->prefix("xdir_mod")." WHERE modifysubmitter=$userid ORDER BY lid ASC");
while(list($modrow) = $xoopsDB->fetchRow($result)) {
  $modarray[]=$modrow;
}

$newlink = array();

$myresult = "select l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.mfhrs, l.sathrs, l.sunhrs, l.phone, l.fax, l.mobile, l.home, l.tollfree, l.email, l.url, l.logourl, l.submitter, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description from ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t WHERE l.submitter=$userid AND l.lid=t.lid ORDER BY l.title ASC";

  $result=$xoopsDB->query($myresult, $show, $start);

while(list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $submitter, $status, $time, $hits, $rating, $votes, $comments, $premium, $descr) = $xoopsDB->fetchRow($result)) {

$is_owner = ($userid == $submitter) ? '1' : null ;
  
//Adding record related template items
$mfhrs = displayTime($mfhrs);
$sathrs = displayTime($sathrs);
$sunhrs = displayTime($sunhrs);
$bizhrs = array(_MD_MXDIR_BUSMFHRSSHORT.$mfhrs,_MD_MXDIR_BUSSATHRSSHORT.$sathrs,_MD_MXDIR_BUSSUNHRSSHORT.$sunhrs);

$bnums = array($phone,$fax,$mobile,$home,$tollfree);
$biznums = displaybiznums($bnums);
$lvlopts = getPremiumOptions($premium);

if ($status == 0) {
  $new = '';
  $pop = '';
  $rating = "";
  $votestring = "";
  $ntitle = $myts->htmlspecialchars($ltitle).$new.$pop." -"._MD_MXDIR_APPROVALPENDING;

} else {
  $new = newlinkgraphic($time, $status);
  $pop = popgraphic($hits);
  if(in_array($lid,$modarray)) {
    $status = 99;
    $ntitle = $myts->htmlspecialchars($ltitle).$new.$pop." -"._MD_MXDIR_MODREQPENDING;
  } else {
    $ntitle = $myts->htmlspecialchars($ltitle).$new.$pop;
  }
  $ratingfl = (($rating/2) - floor(($rating/2)) < 0.5) ? intval(floor($rating/2)*10) : intval((floor($rating/2)+.5)*10) ;
  $ratingfl = str_pad($ratingfl, 2, "0", STR_PAD_LEFT);
  $rating = "<img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/ratings/rate". $ratingfl .".gif' alt='"._MD_MXDIR_RATINGC.number_format($rating, 2)."' /> ";
  $votestring = ($votes == 1) ? _MD_MXDIR_ONEVOTE : sprintf(_MD_MXDIR_NUMVOTES,$votes);
}

$newlink[] = array('id' => $lid,
                                'cid' => $cid,
                                'rating' => $rating,
                                'url' => $url,
                                'lvlopts' => $lvlopts,
//                                'titletrim' => $myts->htmlspecialchars($ltitle),
                                'title' => $ntitle,
                                'address' => $myts->htmlspecialchars($address),
                                'address2' => $myts->htmlspecialchars($address2),
                                'city' => $myts->htmlspecialchars($city),
                                'state' => $myts->htmlspecialchars($state),
                                'zip' => $myts->htmlSpecialChars($zip),
                                'country' => $myts->htmlSpecialChars($country),
                                'bizhrs' => $bizhrs,
                                'biznums' => $biznums,
                                'email' => $myts->htmlSpecialChars($email),
//                                'category' => $path,
                                'logourl' => $myts->htmlSpecialChars($logourl),
                                'status' => $status,
                                'is_owner' => $is_owner,
                                'isadmin' => $isadmin,
                                'updated' => formatTimestamp($time,"m"),
                                'descr' => $myts->displayTarea($descr,0),
                                'coupons' => $coupon_handler->getCountByLink($lid),
                                'adminlink' => $adminlink,
                                'hits' => $hits,
                                'votes' => $votestring,
                                'comments' => $comments,
                                'premium' => $premium,
                                'mail_subject' => rawurlencode(sprintf(_MD_MXDIR_INTRESTLINK,$xoopsConfig['sitename'])),
                                'mail_body' => rawurlencode(sprintf(_MD_MXDIR_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?lid='.$lid)
                                );
}

$page_nav = new XoopsPageNav($numrows , intval($show), $start, 'start', 'list='.$list.'&amp;show='.$show);

$xoopsTpl->assign('links', $newlink);

$xoopsTpl->assign('p1color', $xoopsModuleConfig['premium_listing1col']);
$xoopsTpl->assign('p2color', $xoopsModuleConfig['premium_listing2col']);
$xoopsTpl->assign('p3color', $xoopsModuleConfig['premium_listing3col']);
$xoopsTpl->assign('p4color',$xoopsModuleConfig['premium_listing4col']);
$xoopsTpl->assign('p5color', $xoopsModuleConfig['premium_listing5col']);

//Adding non-record related template items
$l_mylistings = (empty($newlink)) ? _NO." "._MD_MXDIR_LISTINGS : $myts->htmlSpecialChars($user)." - "._MD_MXDIR_LISTINGS ;
$xoopsTpl->assign('lang_mylistings', $l_mylistings);
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
$xoopsTpl->assign('lang_comments' , _COMMENTS);
$xoopsTpl->assign('lang_phone', _MD_MXDIR_BUSPHONE);
$xoopsTpl->assign('lang_fax', _MD_MXDIR_BUSFAX);
$xoopsTpl->assign('lang_mobile', _MD_MXDIR_BUSMOBILE);
$xoopsTpl->assign('lang_home', _MD_MXDIR_BUSHOME);
$xoopsTpl->assign('lang_tollfree', _MD_MXDIR_BUSTOLLFREE);
$xoopsTpl->assign('lang_email', _MD_MXDIR_BUSEMAIL);
$xoopsTpl->assign('lang_mfhrs', _MD_MXDIR_BUSMFHRSSHORT);
$xoopsTpl->assign('lang_sathrs', _MD_MXDIR_BUSSATHRSSHORT);
$xoopsTpl->assign('lang_sunhrs', _MD_MXDIR_BUSSUNHRSSHORT);

//Paging
$category['navbar'] = '' . $page_nav -> renderNav() . '';
$xoopsTpl -> assign( 'category', $category);

//alphalist function
$letters = letters();
$xoopsTpl->assign('letters', $letters);
//mid autodetect
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
//Smarty directory autodetect
$xoopsTpl->assign('usealpha', $xoopsModuleConfig['usealpha']);
$xoopsTpl->assign('usesearch', $xoopsModuleConfig['usesearch']);
$smartydir = $xoopsModule->getVar('dirname');
$xoopsTpl->assign('smartydir', $smartydir);
//$xoopsTpl->assign('lang_url', _MD_MXDIR_BUSURL);

// clear the template cache for this page.  The page isn't
// viewed often - relative to other pages and we don't want
// users to get cached version of other users pages
$xoopsTpl->clear_cache('xdir_mylistings.html');

include XOOPS_ROOT_PATH.'/include/comment_view.php';
include XOOPS_ROOT_PATH.'/footer.php';

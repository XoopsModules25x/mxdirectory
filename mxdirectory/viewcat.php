<?php
// $Id: viewcat.php 11970 2013-08-24 14:20:57Z beckmi $
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
include_once XOOPS_ROOT_PATH."/modules/$mydirname/class/coupon.php";
global $xoopsDB, $xoopModuleConfig, $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');

$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
    $orderby = (isset($_GET['orderby'])) ? convertorderbyin($_GET['orderby']) : "title ASC";
    $start = isset( $_GET['start'] ) ? intval( $_GET['start'] ) : 0;
$cid = isset( $_GET['cid'] ) ? intval( $_GET['cid'] ) : 0;
//$cid = $_GET['cid'];
$pagecid = $cid;
$xoopsOption['template_main'] = 'xdir_viewcat.html';
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign('xoops_module_header', $xoops_module_header);
//alphalist
$letters = letters();
$xoopsTpl->assign('letters', $letters);
//xoopspage
$show = $xoopsModuleConfig['perpage'];
//modify listing incase you want to add to links or premiumlink
//$showmod = $xoopsModuleConfig['showmod'];
//$xoopsTpl->assign('showmod', $showmod);

//for cat, pg.title & sublinks
$pathstring = "<a href='index.php'>"._MD_MXDIR_MAIN."</a> : ";
$pathstring .= $mytree->getNicePathFromId($cid, "title", "viewcat.php?");

$xoopsTpl->assign('xoops_pagetitle', $pathstring);
//XHTML Compliance
$pathstring = preg_replace('/&c/', 'c', $pathstring);
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);
// get child category objects
$arr=array();
$arr=$mytree->getFirstChild($cid, "title");
if ( count($arr) > 0 ) {
    $scount = 1;
    foreach($arr as $ele){
            $sub_arr=array();
            $sub_arr=$mytree->getFirstChild($ele['cid'], "title");
            $space = 0;
            $chcount = 0;
            $infercategories = "";
            foreach($sub_arr as $sub_ele){
                $chtitle=$myts->htmlSpecialChars($sub_ele['title']);
                if ($chcount>5){
                    $infercategories .= "...";
                    break;
                }
                if ($space>0) {
                    $infercategories .= ", ";
                }
//				$infercategories .= "<a href=\"".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/viewcat.php?cid=".$sub_ele['perpage']."\">".$chtitle."</a>";
                $infercategories .= "<a href=\"".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/viewcat.php?cid=".$sub_ele['cid']."\">".$chtitle."</a>";
                $space++;
                $chcount++;
            }
        $xoopsTpl->append('subcategories', array('title' => $myts->htmlSpecialChars($ele['title']), 'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallinks' => getTotalItems($ele['cid'], 1), 'count' => $scount));
        $scount++;
    }
}

$title="";
if($cid>0)
{
$sql = "SELECT title FROM ".$xoopsDB->prefix("xdir_cat")." t where cid=$cid";
$result=$xoopsDB->query($sql,$show,$start);
$myrow = $xoopsDB->fetchArray($result);
$title=$myts->htmlSpecialChars($myrow['title']);
}

$xoopsTpl->assign('xoops_pagetitle', $title.' | '.$myts->htmlSpecialChars($xoopsModule->getVar('name')));

if ($xoopsModuleConfig['useshots'] == 1) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['logo_maximgwidth']);
    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['logo_maximgwidth'] + 10);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('lang_noscreenshot', _MD_MXDIR_NOSHOTS);
}

$isadmin = (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;

$fullcountresult=$xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where (cid=$cid or cidalt1=$cid or cidalt2=$cid or cidalt3=$cid or cidalt4=$cid) and status>0");
list($numrows) = $xoopsDB->fetchRow($fullcountresult);
$navcheck = $numrows;
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
    $xoopsTpl->assign('lang_phone', _MD_MXDIR_BUSPHONE);
    $xoopsTpl->assign('lang_fax', _MD_MXDIR_BUSFAX);
    $xoopsTpl->assign('lang_email', _MD_MXDIR_BUSEMAIL);
    $xoopsTpl->assign('lang_url', _MD_MXDIR_SITEURL);
    
  $sql = "select l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.mfhrs, l.sathrs, l.sunhrs, l.phone, l.fax, l.mobile, l.home, l.tollfree, l.email, l.url, l.logourl, l.submitter, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description FROM ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t where (cid=$cid or cidalt1=$cid or cidalt2=$cid or cidalt3=$cid or cidalt4=$cid) and l.lid=t.lid and status>0 order by $orderby";
  $result=$xoopsDB->query($sql,$show,$start);

  //if 2 or more items in result, show the sort menu
  if($numrows>1){
    $xoopsTpl->assign('show_nav', true);
    $orderbyTrans = convertorderbytrans($orderby);
    $xoopsTpl->assign('lang_sortby', _MD_MXDIR_SORTBY);
    $xoopsTpl->assign('lang_title', _MD_MXDIR_TITLE);
        $xoopsTpl->assign('lang_titleatoz', _MD_MXDIR_TITLEATOZ);
        $xoopsTpl->assign('lang_titleztoa', _MD_MXDIR_TITLEZTOA);
    $xoopsTpl->assign('lang_date', _MD_MXDIR_DATE);
        $xoopsTpl->assign('lang_dateold', _MD_MXDIR_DATEOLD);
        $xoopsTpl->assign('lang_datenew', _MD_MXDIR_DATENEW);
    $xoopsTpl->assign('lang_rating', _MD_MXDIR_RATING);
        $xoopsTpl->assign('lang_ratinglow', _MD_MXDIR_RATINGLTOH);
        $xoopsTpl->assign('lang_ratinghigh', _MD_MXDIR_RATINGHTOL);
    $xoopsTpl->assign('lang_popularity', _MD_MXDIR_POPULARITY);
        $xoopsTpl->assign('lang_popularityleast', _MD_MXDIR_POPULARITYLTOM);
        $xoopsTpl->assign('lang_popularitymost', _MD_MXDIR_POPULARITYMTOL);
    $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_MXDIR_CURSORTEDBY, convertorderbytrans($orderby)));
  }
  while(list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $submitter, $status, $time, $hits, $rating, $votes, $comments, $premium, $description) = $xoopsDB->fetchRow($result)) {

    $lvlopts = getPremiumOptions($premium);
//	$pcounter = intval($lvlopts['9']);
    $pcounter = isset($lvlopts['9']) ?  intval($lvlopts['9']) : 0;
    $pcount = ( $pcounter !=0 ) ? ($pcount + 1) : $pcount;

        
//	include_once "include/functions.php";
    $mfhrs = displayTime($mfhrs);
    $sathrs = displayTime($sathrs);
    $sunhrs = displayTime($sunhrs);
    $bizhrs = array('1' => _MD_MXDIR_BUSMFHRSSHORT.$mfhrs,'2' => _MD_MXDIR_BUSSATHRSSHORT.$sathrs,'3' => _MD_MXDIR_BUSSUNHRSSHORT.$sunhrs,);
    $bnums = array($phone,$fax,$mobile,$home,$tollfree);
    $biznums = displaybiznums($bnums);

    $is_owner = (!empty($xoopsUser) && ($xoopsUser->getvar('uid') == $submitter)) ? '1' : null ;

      $adminlink = ($isadmin) ? '<a href="'.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=modLink&amp;lid='.$lid.'"><img src="'. $pathIcon16 .'/edit.png"'.' border="0" alt="'._MD_MXDIR_EDITTHISLINK.'" /></a>' : '';
    
    $votestring = ($votes == 1) ? _MD_MXDIR_ONEVOTE : sprintf(_MD_MXDIR_NUMVOTES,$votes);
    $path = $mytree->getPathFromId($cid, "title");
    $path = substr($path, 1);
    $path = str_replace("/"," <img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/arrow.gif' board='0' alt=''> ",$path);
    $new = newlinkgraphic($time, $status);
    $pop = popgraphic($hits);
      $ratingfl = (($rating/2) - floor(($rating/2)) < 0.5) ? intval(floor($rating/2)*10) : intval((floor($rating/2)+.5)*10) ;
      $ratingfl = str_pad($ratingfl, 2, "0", STR_PAD_LEFT);
      $rating = "<img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/ratings/rate". $ratingfl .".gif' alt='"._MD_MXDIR_RATINGC.number_format($rating, 2)."'/> ";
 
    $coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
    $coupons = $coupon_handler->getCountByLink($lid);
//echo "-1-<P>";print_r($biznums);
    $xoopsTpl->append('links', array('id' => $lid, 'cid' => $cid, 'url' => $myts->htmlSpecialChars($url), 'rating' => $rating, 'lvlopts' => $lvlopts, 'titletrim' => $myts->htmlSpecialChars($ltitle), 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'address' => $myts->htmlSpecialChars($address), 'address2' => $myts->htmlSpecialChars($address2), 'city' => $myts->htmlSpecialChars($city), 'state' => $myts->htmlSpecialChars($state), 'zip' => $myts->htmlSpecialChars($zip), 'country' => $myts->htmlSpecialChars($country), 'bizhrs' => $bizhrs, 'biznums' => $biznums,'mfhrs' => $myts->htmlSpecialChars($mfhrs), 'sathrs' => $myts->htmlSpecialChars($sathrs), 'sunhrs' => $myts->htmlSpecialChars($sunhrs), 'phone' => $myts->htmlSpecialChars($phone), 'fax' => $myts->htmlSpecialChars($fax), 'mobile' => $myts->htmlSpecialChars($mobile), 'home' => $myts->htmlSpecialChars($home), 'tollfree' => $myts->htmlSpecialChars($tollfree), 'email' => $myts->htmlSpecialChars($email), 'category' => $myts->htmlSpecialChars($path), 'logourl' => $myts->htmlSpecialChars($logourl), 'is_owner' => $is_owner, 'updated' => formatTimestamp($time,"m"), 'description' => $myts->displayTarea($description,0), 'coupons' => $coupons, 'adminlink' => $adminlink, 'hits' => $hits, 'comments' => $comments, 'premium' => $premium, 'votes' => $votestring, 'mail_subject' => rawurlencode(sprintf(_MD_MXDIR_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MXDIR_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid='.$cid.'&amp;lid='.$lid)));

  }
     $orderby = convertorderbyout($orderby);
$xoopsTpl->assign('pcount', $pcount);
if ($navcheck > 0){
    $page_nav = new XoopsPageNav($numrows , intval($show), $start, 'start', '&amp;cid='.$pagecid.'&amp;show='.$show.'$orderby='.$orderby);
    }
}

//$xoopsTpl->assign('pcount', $pcount);

    $page_nav = new XoopsPageNav($numrows , intval($show), $start, 'start', '&amp;cid='.$pagecid.'&amp;show='.$show.'$orderby='.$orderby);

//Premium from admin
$xoopsTpl->assign('sponson', $xoopsModuleConfig['sponsor_active']);
$xoopsTpl->assign('p1color', $xoopsModuleConfig['premium_listing1col']);
$xoopsTpl->assign('p2color', $xoopsModuleConfig['premium_listing2col']);
$xoopsTpl->assign('p3color', $xoopsModuleConfig['premium_listing3col']);
$xoopsTpl->assign('p4color',$xoopsModuleConfig['premium_listing4col']);
$xoopsTpl->assign('p5color', $xoopsModuleConfig['premium_listing5col']);
//Nav assign
$category['navbarcat'] = '' . $page_nav -> renderNav() . '';
$xoopsTpl -> assign( 'category', $category);
//$xoopsTpl->assign('cid', $cid);
//mid autodetect
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
//search and alpha display from module preferences
$xoopsTpl->assign('usealpha', $xoopsModuleConfig['usealpha']);
$xoopsTpl->assign('usesearch', $xoopsModuleConfig['usesearch']);
//Smarty directory autodetect
$smartydir = $xoopsModule->getVar('dirname');
$xoopsTpl->assign('smartydir', $smartydir);
include XOOPS_ROOT_PATH.'/footer.php';

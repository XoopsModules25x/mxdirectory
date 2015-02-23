<?php
// $Id: index.php 11970 2013-08-24 14:20:57Z beckmi $
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
include_once "header.php";
include_once "class/coupon.php";
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";
//include 'include/functions.php';

global $xoopModuleConfig, $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');
/* // EVU CODE FIX - To make it work using PHP5

function add_header($tpl_source, &$xoopsTpl)
{
    return "<?php echo $xoops_module_header; ?>\n".$tpl_source;
}
*/
$xoopsOption['template_main'] = 'xdir_index.html';

$xoopsOption['xoops_module_header']= $xoops_module_header;
//cache start
include XOOPS_ROOT_PATH."/header.php";

//$xoopsTpl->register_postfilter('add_header');// EVU CODE FIX - To make it work using PHP5

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");

$result=$xoopsDB->query("SELECT cid, title, imgurl FROM ".$xoopsDB->prefix("xdir_cat")." WHERE pid = 0 ORDER BY title") or exit("Error");
//Alpha List
$letters = letters();
$xoopsTpl->assign('letters', $letters);
//mid for admin
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));

//iterate from parent ID down
$count = 1;
while($myrow = $xoopsDB->fetchArray($result)) {

	$imgurl = '';
	if ($myrow['imgurl'] && $myrow['imgurl'] != "http://"){
		$imgurl = $myts->htmlSpecialChars($myrow['imgurl']);
	}
	$totallink = getTotalItems($myrow['cid'], 1);

	// get child category objects
	$arr = array();
	$arr = $mytree->getFirstChild($myrow['cid'], "title");
	$space = 0;
	$chcount = 0;
	$subcategories = '';
	foreach($arr as $ele){
		$chtitle = $myts->htmlSpecialChars($ele['title']);
		if ($chcount > 5) {
			$subcategories .= "...";
			break;
		}
		if ($space>0) {
			$subcategories .= ", ";
		}
		$subcategories .= "<a href=\"".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/viewcat.php?cid=".$ele['cid']."\">".$chtitle."</a>";
		$space++;
		$chcount++;
	}
	$xoopsTpl->append('categories', array('image' => $imgurl, 'id' => $myrow['cid'], 'title' => $myts->htmlSpecialChars($myrow['title']), 'subcategories' => $subcategories, 'totallink' => $totallink, 'count' => $count));
	$count++;

}

//total count
list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("select count(*) from ".$xoopsDB->prefix("xdir_links")." where status>0"));
$xoopsTpl->assign('lang_thereare', sprintf(_MD_MXDIR_THEREARE,$numrows));

if ($xoopsModuleConfig['useshots'] == 1) {
	$xoopsTpl->assign('shotwidth', $xoopsModuleConfig['logo_maximgwidth']);
	$xoopsTpl->assign('tablewidth', $xoopsModuleConfig['logo_maximgwidth'] + 10);
	$xoopsTpl->assign('show_screenshot', true);
	$xoopsTpl->assign('lang_noscreenshot', _MD_MXDIR_NOSHOTS);
}

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$isadmin = true;
} else {
	$isadmin = false;
}

$xoopsTpl->assign('lang_description', _MD_MXDIR_DESCRIPTIONC);
$xoopsTpl->assign('lang_lastupdate', _MD_MXDIR_LASTUPDATEC);
$xoopsTpl->assign('lang_hits', _MD_MXDIR_HITSC);
$xoopsTpl->assign('lang_rating', _MD_MXDIR_RATINGC);
$xoopsTpl->assign('lang_ratethissite', _MD_MXDIR_RATETHISSITE);
$xoopsTpl->assign('lang_reportbroken', _MD_MXDIR_REPORTBROKEN);
$xoopsTpl->assign('lang_tellafriend', _MD_MXDIR_TELLAFRIEND);
$xoopsTpl->assign('lang_modify', _MD_MXDIR_MODIFY);
$xoopsTpl->assign('lang_latestlistings' , _MD_MXDIR_LATESTLIST);
$xoopsTpl->assign('lang_category' , _MD_MXDIR_CATEGORYC);
$xoopsTpl->assign('lang_visit' , _MD_MXDIR_VISIT);
$xoopsTpl->assign('lang_comments' , _COMMENTS);
$xoopsTpl->assign('lang_attention', _MD_MXDIR_ATTENTION);
$xoopsTpl->assign('lang_phone', _MD_MXDIR_BUSPHONE);
$xoopsTpl->assign('lang_fax', _MD_MXDIR_BUSFAX);
$xoopsTpl->assign('lang_email', _MD_MXDIR_BUSEMAIL);
$xoopsTpl->assign('lang_url', _MD_MXDIR_SITEURL);
//search and alpha display from module preferences
$xoopsTpl->assign('usealpha', $xoopsModuleConfig['usealpha']);
$xoopsTpl->assign('usesearch', $xoopsModuleConfig['usesearch']);
//mid autodetect
$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
//Smarty directory autodetect
$smartydir = $xoopsModule->getVar('dirname');
$xoopsTpl->assign('smartydir', $smartydir);

$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.mfhrs, l.sathrs, l.sunhrs, l.phone, l.fax, l.mobile, l.home, l.tollfree, l.email, l.url, l.logourl, l.submitter, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description FROM ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t WHERE l.status>0 and l.lid=t.lid ORDER BY date DESC", $xoopsModuleConfig['newlinks'], 0);
while(list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $submitter, $status, $time, $hits, $rating, $votes, $comments, $premium, $description) = $xoopsDB->fetchRow($result)) {

//Nice arrays for smarty selects
//include_once "include/functions.php";
$mfhrs = displayTime($mfhrs);
$sathrs = displayTime($sathrs);
$sunhrs = displayTime($sunhrs);
 
$bizhrs = array('1' => _MD_MXDIR_BUSMFHRSSHORT.$mfhrs,'2' => _MD_MXDIR_BUSSATHRSSHORT.$sathrs,'3' => _MD_MXDIR_BUSSUNHRSSHORT.$sunhrs,);
$bnums = array($phone,$fax,$mobile,$home,$tollfree);
$biznums = displaybiznums($bnums);

$is_owner = (!empty($xoopsUser) && ($xoopsUser->getvar('uid') == $submitter)) ? '1' : null ;

$lvlopts = getPremiumOptions($premium);

	if ($isadmin) {
		$adminlink = '<a href="'.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/admin/main.php?op=modLink&amp;lid='.$lid.'"><img src="'. $pathIcon16 .'/edit.png"'.' border="0" alt="'._MD_MXDIR_EDITTHISLINK.'" /></a>';
	} else {
		$adminlink = '';
	}
	if ($votes == 1) {
		$votestring = _MD_MXDIR_ONEVOTE;
	} else {
		$votestring = sprintf(_MD_MXDIR_NUMVOTES,$votes);
	}
	$path = $mytree->getPathFromId($cid, "title");
	$path = substr($path, 1);
	$path = str_replace("/"," - ",$path);
	$new = newlinkgraphic($time, $status);
	$pop = popgraphic($hits);
//	$coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
	$coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);
	$coupons = $coupon_handler->getCountByLink($lid);

	$ratingfl = (($rating/2) - floor(($rating/2)) < 0.5) ? intval(floor($rating/2)*10) : intval((floor($rating/2)+.5)*10) ;
	$ratingfl = str_pad($ratingfl, 2, "0", STR_PAD_LEFT);
	$rating = "<img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/ratings/rate". $ratingfl .".gif' alt='"._MD_MXDIR_RATINGC.number_format($rating, 2)."'/> ";
	
	$xoopsTpl->append('links', array('id' => $lid, 'cid' => $cid, 'url' => $url, 'rating' => $rating, 'lvlopts' => $lvlopts, 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'address' => $myts->htmlSpecialChars($address), 'address2' => $myts->htmlSpecialChars($address2), 'city' => $myts->htmlSpecialChars($city), 'state' => $myts->htmlSpecialChars($state), 'zip' => $myts->htmlSpecialChars($zip), 'country' => $myts->htmlSpecialChars($country), 'bizhrs' => $bizhrs, 'biznums' => $biznums, 'email' => $myts->htmlSpecialChars($email), 'category' => $path, 'logourl' => $myts->htmlSpecialChars($logourl), 'is_owner' => $is_owner, 'updated' => formatTimestamp($time,"m"), 'description' => $myts->displayTarea($description,0), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring, 'coupons' => $coupons, 'comments' => $comments, 'premium' => $premium, 'mail_subject' => rawurlencode(sprintf(_MD_MXDIR_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MXDIR_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid='.$cid.'&lid='.$lid)));

}

//Premium Admin Colors
$xoopsTpl->assign('p1color', $xoopsModuleConfig['premium_listing1col']);
$xoopsTpl->assign('p2color', $xoopsModuleConfig['premium_listing2col']);
$xoopsTpl->assign('p3color', $xoopsModuleConfig['premium_listing3col']);
$xoopsTpl->assign('p4color',$xoopsModuleConfig['premium_listing4col']);
$xoopsTpl->assign('p5color', $xoopsModuleConfig['premium_listing5col']);

include XOOPS_ROOT_PATH.'/footer.php';

?>

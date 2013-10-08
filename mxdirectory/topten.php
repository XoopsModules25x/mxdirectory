<?php
// $Id: topten.php 11970 2013-08-24 14:20:57Z beckmi $
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
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
$xoopsOption['template_main'] = 'xdir_topten.html';
include XOOPS_ROOT_PATH."/header.php";
//generates top 10 charts by rating and hits for each main category

if ( isset($_GET['rate']) ) {
	$sort = _MD_MXDIR_RATING;
	$sortDB = "rating";
	$rateqlfy = " and (votes > 0) ";
}else{
	$sort = _MD_MXDIR_HITS;
	$sortDB = "hits";
	$rateqlfy = "";
}
$xoopsTpl->assign('lang_sortby' ,$sort);
$xoopsTpl->assign('lang_rank' , _MD_MXDIR_RANK);
$xoopsTpl->assign('lang_title' , _MD_MXDIR_TITLE);
$xoopsTpl->assign('lang_category' , _MD_MXDIR_CATEGORY);
$xoopsTpl->assign('lang_hits' , _MD_MXDIR_HITS);
$xoopsTpl->assign('lang_rating' , _MD_MXDIR_RATING);
$xoopsTpl->assign('lang_vote' , _MD_MXDIR_VOTE);
$arr=array();
$result=$xoopsDB->query("select cid, title from ".$xoopsDB->prefix("xdir_cat")." where pid=0");
$e = 0;
$rankings = array();
while(list($cid, $ctitle)=$xoopsDB->fetchRow($result)){
//	$rankings[$e]['title'] = sprintf(_MD_MXDIR_TOP10, $myts->htmlSpecialChars($ctitle));
	$query = "select lid, cid, title, hits, rating, votes from ".$xoopsDB->prefix("xdir_links")." where status>0".$rateqlfy." and (cid=$cid";
	// get all child cat ids for a given cat id
	$arr=$mytree->getAllChildId($cid);
	$size = count($arr);
	for($i=0;$i<$size;$i++){
		$query .= " or cid=".$arr[$i]."";
	}
	$query .= ") order by ".$sortDB." DESC";
	$result2 = $xoopsDB->query($query,10,0);
	$res2_rows = $xoopsDB->getRowsNum($result2);
	if ( $res2_rows > 0 ) {
	  $rank = 1;
	  while(list($lid,$lcid,$ltitle,$hits,$rating,$votes)=$xoopsDB->fetchRow($result2)){
		  $catpath = $mytree->getPathFromId($lcid, "title");
  		$catpath= substr($catpath, 1);
	  	$catpath = str_replace("/"," <span class='fg2'>&raquo;</span> ",$catpath);
		  $ratingfl = (($rating/2) - floor(($rating/2)) < 0.5) ? intval(floor($rating/2)*10) : intval((floor($rating/2)+.5)*10) ;
  		$ratingfl = str_pad($ratingfl, 2, "0", STR_PAD_LEFT);
	  	$rating = "<img src='".XOOPS_URL."/modules/" . $xoopsModule->getVar('dirname') . "/images/ratings/rate". $ratingfl .".gif' alt='"._MD_MXDIR_RATINGC.number_format($rating, 2)."'> ";

//		  $rankings[$e]['links'][] = array('id' => $lid, 'cid' => $cid, 'rank' => $rank, 'title' => $myts->htmlSpecialChars($ltitle), 'category' => $catpath, 'hits' => $hits, 'rating' => number_format($rating, 2), 'votes' => $votes);
  		$rankings[$e]['links'][] = array('id' => $lid, 'cid' => $cid, 'rank' => $rank, 'title' => $myts->htmlSpecialChars($ltitle), 'category' => $catpath, 'hits' => $hits, 'rating' => $rating, 'votes' => $votes);
	  	$rank++;
  	}
  	$rankings[$e]['title'] = sprintf(_MD_MXDIR_TOP10, $myts->htmlSpecialChars($ctitle));
	}
  $e++;
}
$xoopsTpl->assign('rankings', $rankings);
//Smarty directory autodetect
$smartydir = $xoopsModule->getVar('dirname');
$xoopsTpl->assign('smartydir', $smartydir);
include XOOPS_ROOT_PATH.'/footer.php';
?>

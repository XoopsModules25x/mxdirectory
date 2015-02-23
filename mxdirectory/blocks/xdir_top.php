<?php
// $Id: xdir_top.php 11970 2013-08-24 14:20:57Z beckmi $
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
/******************************************************************************
 * Function: b_xdir_top_show
 * Input   : $options[0] = date for the most recent links
 *                    hits for the most popular links
 *           $block['content'] = The optional above content
 *           $options[1]   = How many reviews are displayes
 * Output  : Returns the desired most recent or most popular links
 ******************************************************************************/
function b_xdir_top_show($options) {
    //include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
  $mydirname = basename ( dirname(dirname( __FILE__ ) ) ) ;
  include_once XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";


  global $xoopsDB;
  $mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");
	$block = array();
	$myts =& MyTextSanitizer::getInstance();

 	switch (trim($options[0])) {
   	case "hits":
   	  $hitchk = " AND hits>0 ";
   	  $limit = " LIMIT " . $options[1];
   	  $orderby = "hits";
   	  $rand_limit = "";
   	  break;
   	case "rand":
   	  $hitchk = "" ;
   	  $limit = "";
   	  $orderby = "NULL";
   	  $rand_limit = $options[1];
   	  break;
   	case "rank":  // order by rank if votes>0
   	  $hitchk = " AND votes>0 " ;
   	  $limit = " LIMIT ".$options[1];
   	  $orderby = "rating";
   	  $rand_limit = "";
   	  break;
   	case "date":
   	default:
   	  $hitchk = "" ;
   	  $limit = " LIMIT ".$options[1];
   	  $orderby = "date";
   	  $rand_limit = "";
   	  break;
  }

	if ($options[5] == 'All') {
    $dispcat  = "";
  } else {
    $start = intval($options[5]);
   	$tree = $mytree->getChildTreeArray($start,"title ASC");
    $dispcat =  " AND ((cid = ".$options[5].")" ;
    $dispcat .= " OR (cidalt1 = ".$options[5].")";
    $dispcat .= " OR (cidalt2 = ".$options[5].")";
    $dispcat .= " OR (cidalt3 = ".$options[5].")";
    $dispcat .= " OR (cidalt4 = ".$options[5].")";
  	foreach ($tree as $branch ) {
  		$dispcat .= " OR (cid = ".$branch['cid'].")";
  		$dispcat .= " OR (cidalt1 = ".$branch['cid'].")";
  		$dispcat .= " OR (cidalt2 = ".$branch['cid'].")";
  		$dispcat .= " OR (cidalt3 = ".$branch['cid'].")";
  		$dispcat .= " OR (cidalt4 = ".$branch['cid'].")";
  	}
    $dispcat .= ")";
  }

//	$result = $xoopsDB->query("SELECT lid, cid, cidalt1, cidalt2, cidalt3, cidalt4, title, date, hits FROM ".$xoopsDB->prefix("xdir_links")." WHERE (status>0".$hitchk.$dispcat.") ORDER BY ".$orderby." DESC",$limit);
	$result = $xoopsDB->query("SELECT lid, cid, title, date, hits, rating, votes FROM ".$xoopsDB->prefix("xdir_links")." WHERE (status>0".$hitchk.$dispcat.") ORDER BY ".$orderby." DESC".$limit);
	$lstcount = 0;
	while($myrow = $xoopsDB->fetchArray($result)){
		$link = array();
		$title = $myts->htmlSpecialChars($myrow["title"]);
		if ( !XOOPS_USE_MULTIBYTES ) {
			if (strlen($myrow['title']) >= $options[2]) {
				$title = $myts->htmlSpecialChars(substr($myrow['title'],0,($options[2] -1)))."...";
			}
		}
		$link['id'] = $myrow['lid'];
		$link['cid'] = $myrow['cid'];
		$link['title'] = $title;

		$lstcount++;
		$link['mydirname'] = $options[3];
		switch ($options[0]) {
  		case "hits":
  		  $link['criteria'] = $myrow['hits'];
  		  break;
  		case "rank":
  		  $link['criteria'] = sprintf("%01.2f", $myrow['rating']);
  		  break;
  		case "rand":
  		case "date":
  		default:
  		  $link['criteria'] = formatTimestamp($myrow['date'],'s');
  		  break;
		}

		$block['links'][] = $link;
	}
	$retval = $block;

	if( empty($block) ) {
		$retval = ($options[4] == 0) ? "" : array('id'=>'','cid'=>'','title'=>'');
	} else {
  	// now check to see if random block
  	if ( ( $options[0] == "rand") and ($rand_limit <= $lstcount) ) {
      $rand_ret = array();
   	  $rand_key = array_rand($block['links'],$rand_limit);
   	  if ( is_array($rand_key) ) {
   	    foreach ( $rand_key as $idx ) {
          $rand_ret['links'][] = $block['links'][$idx];
        }
      } else {
        $rand_ret['links'][] = $block['links'][$rand_key];
      }
      $block = $rand_ret;
      $retval = $block;
    }
	}

	return $retval;
}

function b_xdir_top_edit($options) {
    //include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
  $mydirname = basename ( dirname(dirname( __FILE__ ) ) ) ;
  include_once XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";


  global $xoopsDB, $xoopModuleConfig;
  $mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");

	$form = ""._MB_MXDIR_DISP."&nbsp;";
	$form .= "<input type='hidden' name='options[]' value='";

	switch ($options[0]) {
  	case "date":
  	  $form .= "date'";
  	  break;
  	case "rand":
  	  $form .= "rand'";
  	  break;
  	case "rank":
  	  $form .= "rank'";
  	  break;
  	case "hits":
  	default:
  	  $form .= "hits'";
  	  break;
	}

	$form .= " />";
	$form .= "<input type='text' name='options[]' value='".$options[1]."' />&nbsp;"._MB_MXDIR_LINKS."";
	$form .= "&nbsp;<br>"._MB_MXDIR_CHARS."&nbsp;<input type='text' name='options[]' value='".$options[2]."' />&nbsp;"._MB_MXDIR_LENGTH."";
	$form .= "<input type='hidden' name='options[]' value='".$options[3]."'";
	if ($options[4] == 1) {
		$ychk = 'checked';
		$nchk = '';
	} else {
		$ychk = '';
		$nchk = 'checked';
	}
  $form .= "<br /><br />"._MB_MXDIR_BLANK."&nbsp;";
  $form .= "<input type='radio' $ychk name='options[]' value='1' />"._YES;
	$form .= "<input type='radio' $nchk name='options[]' value='0' />"._NO;

	$form .= "<br /><br />"._MB_MXDIR_SELECT_CAT."&nbsp;";
	$tree = $mytree->getChildTreeArray(0,"title ASC");
  $form .= "<select name='options[5]'>";
	$form .= "<option value='All'>"._MB_MXDIR_ALLCATS;
	foreach ($tree as $branch ) {
		$branch['prefix'] = substr($branch['prefix'], 0, -1);
		$branch['prefix'] = str_replace(".","--",$branch['prefix']);
		$form .= "<option value='".$branch['cid']."'";
		$selopt = ($options[5] == $branch['cid']) ? " selected='selected' " : "" ;
		$form .= $selopt.">".$branch['prefix'].$branch['title'];
	}
	$form .= "</select>";

	return $form;
}
?>
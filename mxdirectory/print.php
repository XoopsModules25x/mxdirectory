<?php
// $Id: print.php 11970 2013-08-24 14:20:57Z beckmi $
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
include '../../mainfile.php';
$mydirname = basename ( dirname( __FILE__ ) ) ;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;
if (!($lid > 0) ) {
    redirect_header("index.php");
}

function PrintPage($lid)
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $mydirname;
    $myts =& MyTextSanitizer::getInstance();
    include XOOPS_ROOT_PATH."/modules/" .$mydirname. "/include/functions.php";
    require_once XOOPS_ROOT_PATH.'/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $result = $xoopsDB->query("select l.lid, l.cid, l.title, l.address, l.address2, l.city, l.state, l.zip, l.country, l.mfhrs, l.sathrs, l.sunhrs, l.phone, l.fax, l.mobile, l.home, l.tollfree, l.email, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.premium, t.description from ".$xoopsDB->prefix("xdir_links")." l, ".$xoopsDB->prefix("xdir_text")." t where l.lid=$lid and l.lid=t.lid and status>0");
    list($lid, $cid, $ltitle, $address, $address2, $city, $state, $zip, $country, $mfhrs, $sathrs, $sunhrs, $phone, $fax, $mobile, $home, $tollfree, $email, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $premium, $description) = $xoopsDB->fetchRow($result);
    
    if ($votes == 1) {
        $votestring = _MD_MXDIR_ONEVOTE;
    } else {
        $votestring = sprintf(_MD_MXDIR_NUMVOTES,$votes);
    }
    
    if ($xoopsModuleConfig['useshots'] == 1) {
        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
        $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('lang_noscreenshot', _MD_MXDIR_NOSHOTS);
    }

    $mfhrs = displayTime($mfhrs);
    $sathrs = displayTime($sathrs);
    $sunhrs = displayTime($sunhrs);
    $bizhrs = array( _MD_MXDIR_BUSMFHRSSHORT.$mfhrs, _MD_MXDIR_BUSSATHRSSHORT.$sathrs, _MD_MXDIR_BUSSUNHRSSHORT.$sunhrs);
    $bnums = array(0=>$phone, 1=>$fax, 2=>$mobile, 3=>$home, 4=>$tollfree);
    $biznums = displaybiznums($bnums);
    
    $new = newlinkgraphic($time, $status);
    $pop = popgraphic($hits);
    $xoopsTpl->assign('link', array('id' => $lid, 'cid' => $cid, 'rating' => number_format($rating, 2), 'url' => $url, 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'address' => $myts->htmlSpecialChars($address), 'address2' => $myts->htmlSpecialChars($address2), 'city' => $myts->htmlSpecialChars($city), 'state' => $myts->htmlSpecialChars($state), 'zip' => $myts->htmlSpecialChars($zip), 'country' => $myts->htmlSpecialChars($country), 'bizhrs' => $bizhrs, 'biznums' => $biznums, 'email' => $myts->htmlSpecialChars($email), 'logourl' => $myts->htmlSpecialChars($logourl), 'updated' => formatTimestamp($time,"m"), 'description' => $myts->displayTarea($description,0), 'hits' => $hits, 'votes' => $votestring, 'premium' => $premium, 'mail_subject' => rawurlencode(sprintf(_MD_MXDIR_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MXDIR_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/' .$mydirname. '/singlelink.php?lid='.$lid)));
    $xoopsTpl->assign('lang_description', _MD_MXDIR_DESCRIPTIONC);
    $xoopsTpl->assign('lang_lastupdate', _MD_MXDIR_LASTUPDATEC);
    $xoopsTpl->assign('lang_hits', _MD_MXDIR_HITSC);
    $xoopsTpl->assign('lang_rating', _MD_MXDIR_RATINGC);
    $xoopsTpl->assign('lang_category' , _MD_MXDIR_CATEGORYC);
    $xoopsTpl->assign('lang_visit' , _MD_MXDIR_VISIT);
    $xoopsTpl->assign('lang_phone', _MD_MXDIR_BUSPHONE);
    $xoopsTpl->assign('lang_fax', _MD_MXDIR_BUSFAX);
    $xoopsTpl->assign('lang_email', _MD_MXDIR_BUSEMAIL);
    $xoopsTpl->assign('xoops_pagetitle', $ltitle);
    $xoopsTpl->template_dir = XOOPS_ROOT_PATH."/modules/".$mydirname;
    $xoopsTpl->assign('print_footer',_MD_MXDIR_PRINTFOOTER . XOOPS_URL);
    //Smarty directory autodetect

    $xoopsTpl->assign('smartydir', $mydirname);
    $xoopsTpl->display('db:xdir_print.html');
}
PrintPage($lid);

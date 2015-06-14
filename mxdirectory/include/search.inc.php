<?php
// $Id: search.inc.php,v 1.6 2003/03/25 11:08:22 buennagel Exp $
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
//  Author(Hacker): Adam Frick 				                                 //
//  Purpose: Phonebook-like business directory		                         //
//  email: africk69@yahoo.com                           		             //
//  All files of xDirectory are based on the MyLinks module, customized      //
//  to accomodate additional business listing information		             //
//  ------------------------------------------------------------------------ //

function xdir_search($queryarray, $andor, $limit, $offset, $userid){
    global $xoopsDB;
    $sql = "SELECT c.title, l.lid,l.cid,l.date,l.title,l.submitter,l.zip,l.city,l.country,t.description FROM ".$xoopsDB->prefix("xdir_links")." l LEFT JOIN ".$xoopsDB->prefix("xdir_cat")." c ON c.cid=l.cid LEFT JOIN ".$xoopsDB->prefix("xdir_text")." t ON t.lid=l.lid WHERE status>0";
    if ( $userid != 0 ) {
        $sql .= " AND l.submitter=".$userid." ";
    }
    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    if ( is_array($queryarray) && $count = count($queryarray) ) {
        $sql .= " AND ((c.title LIKE '%$queryarray[0]%' OR l.title LIKE '%$queryarray[0]%' OR l.city LIKE '%$queryarray[0]%' OR l.zip LIKE '%$queryarray[0]%' OR l.country LIKE '%$queryarray[0]%' OR t.description LIKE '%$queryarray[0]%')";
        for($i=1;$i<$count;$i++){
            $sql .= " $andor ";
            $sql .= "(c.title LIKE '%$queryarray[$i]%' OR l.title LIKE '%$queryarray[$i]%' OR l.city LIKE '%$queryarray[i]%' OR l.zip LIKE '%$queryarray[i]%' OR l.country LIKE '%$queryarray[i]%' OR t.description LIKE '%$queryarray[$i]%')";
        }
        $sql .= ") ";
    }
    $sql .= "ORDER BY l.date DESC";
    $result = $xoopsDB->query($sql,$limit,$offset);
    $ret = array();
    $i = 0;
    while($myrow = $xoopsDB->fetchArray($result)){
        $ret[$i]['image'] = "images/home.gif";
        $ret[$i]['link'] = "singlelink.php?cid=".$myrow['cid']."&amp;lid=".$myrow['lid']."";
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time'] = $myrow['date'];
        $ret[$i]['uid'] = $myrow['submitter'];
        $i++;
    }

    return $ret;
}

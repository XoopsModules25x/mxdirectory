<?php
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

if (!defined('XOOPS_ROOT_PATH')) { exit(); }

function mx_security_check($inval, $goodkey) {
	$retval = false;
  $code= mx_calc_security($goodkey);
  if ($code) {
  	$retval = ($code == $inval) ? $code : false ;
	}
	return $retval;
}

function mx_calc_security($rnd_num) {
	global $xoopsModule;
	$mydirname = (basename ( dirname ( dirname( __FILE__ ) ) , "a" ) ) ;

	$retval = false;
	if ($rnd_num) {
		if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != $mydirname) {	
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname($mydirname);
		} else {
			$module =& $xoopsModule;
		}
		$smid = $module->getVar('mid');
		$datekey = date("F j");
	  $rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'].$rnd_num.$smid.$datekey));
  	$retval = substr($rcode, 2, 6);
	}
	return $retval;
}
?>
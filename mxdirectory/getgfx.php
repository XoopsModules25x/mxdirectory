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

include "header.php";
include "include/securitycheck.php";

$random_num = (!isset($_GET['random_num']) || intval($_GET['random_num'] == 0)) ? die() : intval($_GET['random_num']) ;
$gd = isset($_GET['gd']) ? intval($_GET['gd']) : 0 ;

$code= mx_calc_security($random_num);

$min_font_sz			= 3;		// minimum font size allowed
$max_font_sz 			= 5;		// maximum font size allowed
$img_height_mult	= 1.5;	// height of image - multiplier * font height 
$img_extend				= 2;		// number of additional chars to extend image

$str_start_pos		= $img_extend * 0.25;	// horiz. center string in image

//
$img_width = imagefontwidth($max_font_sz) * (strlen($code) + intval($img_extend)) ;
$img_height = intval(imagefontheight($max_font_sz) * $img_height_mult) ;
$xoff = imagefontwidth(intval($max_font_sz) * $str_start_pos) ;

	switch ($gd) {
		case 2:
			$img = imagecreatetruecolor($img_width,$img_height);
			break;
		case 1:
			$img = imagecreate($img_width,$img_height);
			break;
		default:
			die();
	}


$bg = imagecolorallocate($img,255,255,255);
$black = imagecolorallocate($img,76,76,76);
$len=strlen($code);

$xpos = $xoff;
$bdr_keepout = 0.1;

for($i=0;$i<$len;$i++)
{
	$font_size = rand($min_font_sz,$max_font_sz);
	$xpos += imagefontwidth($font_size);

	$yoff_max = $img_height - intval((1+$bdr_keepout)*(imagefontheight($font_size)));
	$yoff_min = intval( $bdr_keepout * (imagefontheight($font_size) ) );
	
	$ypos = rand($yoff_min, $yoff_max);
  $vert = false;
//
// UNTESTED FEATURE - On servers using GD2
// You can uncomment the following line to also display chars vertically
//	$vert = rand(1);
	if ($vert && ($gd == 2) ) {
		imagecharup($img,$font_size,$xpos,$ypos,$code,$black);
	} else {
		imagechar($img,$font_size,$xpos,$ypos,$code,$black);
	}
	$code = substr($code,1);    
}
header("Content-Type: image/jpeg");
imagejpeg($img); 
imagedestroy($img);
die();
?>
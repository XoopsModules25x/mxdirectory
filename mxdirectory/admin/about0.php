<?php
// $Id: index.php,v 1.17 2004/07/26 17:51:25 hthouzard Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
include '../../../include/cp_header.php';
include 'functions.php';
xoops_cp_header();

//adminmenu(-1);
echo"<div style=\"position: relative; left: 1%;\"><table class=\"outer\" style=\"width: 100%; border-style: none; spacing: 1px;\">";
echo "<tr><th style=\"text-align: center;\">mx-directory Brought To You By:</th></tr>";
echo "<tr><td class=\"odd\" style=\"width: 100%; text-align: center;\">";
echo "<a href='http://dev.xoops.org' target='_blank'><h3>Dev.Xoops.Org</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.shoalsmedia.com' target='_blank'>Tripmon</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.web-udvikling.dk' target='_blank'>JKP Software Development</h3></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.zyspec.com' target='_blank'>ZySpec</h3></a><br />";
echo "<font style=\"font-style: italic;\">Check the readme for additional tweaks and options.</font>";
echo '</td></tr></table>';
echo"<table class=\"outer\" style=\"width: 100%; border-style: none; spacing: 1px;\"><tr>";
echo "<td class=\"odd\" style=\"width: 48%; text-align: center\" >";
echo "<font style=\"font-weight: bold;\"><a href='http://dev.xoops.org/modules/xfmod/project/?group_id=1223' target='_blank'>mx-directory Module Support</a></font><br />";
echo "Need help with using mx-directory? <br /><br />- Xoops Support Forums</a><br /><br />";
echo "Need additional help with PHP?<br /><br />- <a href='http://www.php.net/' target='_blank'>Official PHP Site</a><br /><br />";
echo "Note: Xoops Donors (Friend of Xoops) will given priority.<br /><br />";
echo "</td>";
echo "<td class=\"odd\" style=\"width: 48%; text-align: center;\">";
echo "<font style=\"font-weight: bold;\">Make A Donation</font><br />Thank you for using mx-directory. If you find the module useful and plan to use it on your site, please show your appreciation by making a small donation at xoops.org to ensure its ongoing development. <br /><br />";

echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" target=\"paypal\" method=\"post\">";
echo "<input type=\"hidden\" name=\"os0\" value=\"No\" />";
echo "<input type=\"hidden\" name=\"amount\" value=\"15.00\" />";
echo "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />";
echo "<input type=\"hidden\" name=\"business\" value=\"donations@xoops.org\" />";
echo "<input type=\"hidden\" name=\"item_name\" value=\"XOOPS.org Donation\" />";
echo "<input type=\"hidden\" name=\"item_number\" value=\"110\" />";
echo "<input type=\"hidden\" name=\"rm\" value=\"2\" />";
echo "<input type=\"hidden\" name=\"notify_url\" value=\"http://www.xoops.org/modules/xdonations/ipnppd.php\" />";
echo "<input type=\"hidden\" name=\"on0\" value=\"List your name? \" />";
echo "<input type=\"hidden\" name=\"on1\" value=\"Module: \" />";
echo "<input type=\"hidden\" name=\"os1\" value=\"mx-directory\" />";

echo "<input type=\"hidden\" name=\"no_shipping\" value=\"1\" />";
echo "<input type=\"hidden\" name=\"currency_code\" value=\"EUR\" />";
echo "<input type=\"hidden\" name=\"cn\" value=\"Comments\" />";
echo "<input type=\"hidden\" name=\"custom\" value=\"\" />";
echo "<input type=\"hidden\" name=\"cancel_return\" value=\"http://www.xoops.org/modules/xdonations/cancel.php\" />";
echo "<input type=\"hidden\" name=\"return\" value=\"http://www.xoops.org/modules/xdonations/success.php\" />";
echo "<input type=\"hidden\" name=\"image_url\" value=\"\" />";
echo "<input type=\"submit\" value=\"Submit Donation\" name=\"I1\" />";
echo "</form>";
echo "</td></tr></table></div>";

xoops_cp_footer();
?>
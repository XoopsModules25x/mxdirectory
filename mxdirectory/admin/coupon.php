<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:								                                 //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  	          	     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  	             	     //
// Thatware - http://thatware.org/					                         //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//	Hacks provided by: Adam Frick											 //
// 	e-mail: africk69@yahoo.com												 //
//	Purpose: Create a yellow-page like business directory for xoops using 	 //
//	the mylinks module as the foundation.									 //
// ------------------------------------------------------------------------- //

include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//global $xoopsDB, $xoopsModule;
//Inserted as $xoopsModule->getVar('dirname') replacement in functions and for module directory name in handlers
//In html templates $mydirname = Smarty variable <{$smartydir}>
//$mydirname = $xoopsModule->getVar('dirname');

include_once "admin_header.php";
xoops_cp_header();
include 'functions.php';
//adminmenu(-1);

//if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) {
//	include "../language/".$xoopsConfig['language']."/main.php";
//} else {
//	include "../language/english/main.php";
//}

$mydirname = $xoopsModule->getVar('dirname');
//	redirect_header(XOOPS_URL,10,"Made it to here - directory name (".$mydirname.")");
include "../class/coupon.php";
$coupon_handler = new XdirectoryCouponHandler($GLOBALS['xoopsDB']);

//$coupon_handler =& xoops_getmodulehandler('coupon', $mydirname);
if (!isset($_GET['op'])) {
    header('location', 'index.php');
}
$op = trim($_GET['op']);
$criteria = new CriteriaCompo();
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;

	$sql = "SELECT lid, title FROM ".$xoopsDB->prefix("xdir_links")." order by title asc";
	$result3 = $xoopsDB->query($sql);

switch ($op) {
    
	case "menu":
	
		$cadmform = new XoopsThemeForm(_MI_MXDIR_ADMENU6, 'cadmform', '../savings.php', 'GET');
		$select_link = (new XoopsFormSelect(_MD_MXDIR_LINKID , 'lid', $lid, 1, false));
	
		while (list($lid, $title) = $xoopsDB->fetchRow($result3) ) {
		$select_link->addOption($lid, $title);
			}	
			
		$cadmform->addElement($select_link);	
		$wraptray = new XoopsFormElementTray('','');

		$regtray = new XoopsFormElementTray('','');
			$sbtn=new XoopsFormButton('', '', _MD_MXDIR_COUPMODADD, 'submit');
			$sbtn->setExtra('onclick="document.cadmform.action =\'../addcoupon.php\'"');	
		$regtray->addElement($sbtn);

		$deltray = new XoopsFormElementTray('','');
			$dbtn=new XoopsFormButton('', '', _MD_MXDIR_COUPMOD, 'submit');
			$dbtn->setExtra('onclick="document.cadmform.action =\'../savings.php\'"');	
		$deltray->addElement($dbtn);
		
		$wraptray->addElement($regtray);
		$wraptray->addElement($deltray);

		$cadmform->addElement($wraptray);
		$cadmform->setExtra("onsubmit=\" this.form.elements.lid.value=this.form.elements._GET[lid].value\"");
		$cadmform->display();
	
		
		
		$cmform = new XoopsThemeForm(_MI_MXDIR_ADMENU6, 'cmform', $_SERVER['PHP_SELF'], 'GET');
		$cmform->addElement(new XoopsFormLabel(_MI_MXDIR_ADMENU11, " <a href=coupon.php?op=noexp> No Expiration Coupons</a>"));
		$cmform->addElement(new XoopsFormLabel(_MI_MXDIR_ADMENU8, "<a href=coupon.php?op=future> Future Coupons</a>"));
		$cmform->addElement(new XoopsFormLabel(_MI_MXDIR_ADMENU7, " <a href=coupon.php?op=expired> Expired Coupons</a>"));
		$cmform->display();
		break;
	
    case 'expired':
        //$operators = '( < ( != 0 ))';
		$criteria->add(new Criteria('expire', time(), '<'));
   		$criteria->add(new Criteria('expire', '0', '!='));
    break;
    
	case 'noexp':
		$criteria->add(new Criteria('expire', '0', '='));	
    break;
	
	case 'future':
        $criteria->add(new Criteria('publish', time(), '>'));
    break;
}
$coupons = $coupon_handler->getObjects($criteria, false);
$coupons = $coupon_handler->prepare2show($coupons);
$output = "<table>";
foreach ($coupons as $catid => $category) {
    $output .= '<tr>
            <th colspan="2">				
				'.$category['catTitle'].';
       </th>
        </tr>';
    foreach ($category['coupons'] as $key => $coupon) {
        if (!isset($class) || ($class != "odd")) {
            $class = "odd";
        }
        else {
            $class = "even";
        }
        $output .= "<tr class='".$class."'>
                <td>";
        $output .= '<a href="'.XOOPS_URL.'/modules/' .$mydirname. '/addcoupon.php?couponid='.$coupon['couponid'].'"><img src="'. $pathIcon16 .'/edit.png"'.' alt="'._MD_MXDIR_EDITCOUPON.'" /></a>
                        <a href="'.XOOPS_URL.'/modules/' .$mydirname. '/singlelink.php?lid='.$coupon['lid'].'">'.$coupon['linkTitle'].'</a><br />
                        <br />
                        '._MD_MXDIR_PUBLISHEDON.' '.$coupon['publish'];
        if ($coupon['expire'] > 0) {
            $output .= "<br />"._MD_MXDIR_EXPIRESON.$coupon['expire'];
        }
        $output .= "<br />"._MD_MXDIR_COUPONHITS." : ".$coupon['counter'];
        $output .= '</div>
                </td>
                <td valign="top">'.$coupon['heading'].'<br />'.$coupon['description'].'</td>
            </tr>
            <tr>
                <td colspan="2" class="foot">
                        <a href="'.XOOPS_URL.'/modules/' .$mydirname. '/addcoupon.php?couponid='.$coupon['couponid'].'">'._MD_MXDIR_EDITCOUPON.'</a>
                </tr>';
    }
}
$output .= "</table>";
if (count($coupons) < 1) {
    $output = "<br /><br />"._MD_MXDIR_NOSAVINGS;
}

echo "<div><table style=\"width: 100%; text-align: center; vertical-align: middle;\"><tr><td style=\"padding: 10;\">".$output."</td></tr></table></div>";

xoops_cp_footer();
echo "<p class=\"mytext\">&nbsp;</p>";
?>
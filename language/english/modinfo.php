<?php
// $Id: modinfo.php 11970 2013-08-24 14:20:57Z beckmi $
// Module Info
$mydirname = basename( dirname ( dirname( dirname( __FILE__ ) ) ) ) ;

// The name of this module
define("_MI_MXDIR_NAME","Yellow Pages");

// A brief description of this module
define("_MI_MXDIR_DESC","Creates a directory listing section where users can search/submit/rate various web sites.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_MXDIR_BNAME1","Recent Listings");
define("_MI_MXDIR_BNAME1DSC","Shows recently added listings");
define("_MI_MXDIR_BNAME2","Top Listings");
define("_MI_MXDIR_BNAME2DSC","Shows most visited listings");
define("_MI_MXDIR_BNAME3","Coupon Listings");
define("_MI_MXDIR_BNAME3DSC","Shows recently added coupons by name");
define("_MI_MXDIR_BNAME4","Coupon Listings - Show Images");
define("_MI_MXDIR_BNAME4DSC","Shows recently added coupons by coupon image");
define("_MI_MXDIR_BNAME5","Random Listings");
define("_MI_MXDIR_BNAME5DSC","Shows random listings");
define("_MI_MXDIR_BNAME6","Category Menu");
define("_MI_MXDIR_BNAME6DSC","Shows category links in a block");
define("_MI_MXDIR_BNAME7","Rating Listings");
define("_MI_MXDIR_BNAME7DSC","Shows top rated listings");

// Sub menu titles
define("_MI_MXDIR_SMNAME1","Submit");
define("_MI_MXDIR_SMNAME2","Popular");
define("_MI_MXDIR_SMNAME3","Top Rated");
define("_MI_MXDIR_SMNAME4","Special Offers");
define("_MI_MXDIR_SMNAME5","My Listings");

// Names of admin menu items
define("_MI_MXDIR_ADMENU1","Overview");
define("_MI_MXDIR_ADMENU2","Listings");
define("_MI_MXDIR_ADMENU3","Submitted Listings");
define("_MI_MXDIR_ADMENU4","Broken Links");
define("_MI_MXDIR_ADMENU5","Modified Listings");
define("_MI_MXDIR_ADMENU6","Coupons");
define("_MI_MXDIR_ADMENU7","Expired Coupons");
define("_MI_MXDIR_ADMENU8","Future Coupons");
define("_MI_MXDIR_ADMENU9","Categories");
define("_MI_MXDIR_ADMENU10","Blocks & Groups");
define("_MI_MXDIR_ADMENU11","No Exp. Coupons");

// Additional Admin menu items
define("_MI_MXDIR_PREFERENCES","Preferences");
define("_MI_MXDIR_GOMOD","Go to Module");
define("_MI_MXDIR_MODADMIN","Module Administration");
define("_MI_MXDIR_ABOUT","About");

// Title of config items
define('_MI_MXDIR_POPULAR', 'Select the number of hits for links to be marked as popular');
define('_MI_MXDIR_NEWLINKS', 'Select the maximum number of new listings displayed on top page');
define('_MI_MXDIR_PERPAGE', 'Select the maximum number of listings displayed in each page');
define('_MI_MXDIR_USESHOTS', 'Select yes to display Logo images in each listing (not links)');
define('_MI_MXDIR_USEFRAMES', 'Would you like to display the linked page withing a frame?');
define('_MI_MXDIR_SHOTWIDTH', 'Maximum allowed width of each screenshot image');
define('_MI_MXDIR_ANONPOST','Allow anonymous users to post business listings?');
define('_MI_MXDIR_AUTOAPPROVE','Auto approve new listings without admin intervention?');
define('_MI_MXDIR_SHOWMOD', 'Allow registered users to submit link modifications?');

define('_MI_MXDIR_PREMIUM1', 'Default Level 1 Name');
define('_MI_MXDIR_PREMIUM2', 'Sponsor Level 2 Name');
define('_MI_MXDIR_PREMIUM3', 'Sponsor Level 3 Name');
define('_MI_MXDIR_PREMIUM4', 'Sponsor Level 4 Name');
define('_MI_MXDIR_PREMIUM5', 'Sponsor Level 5 Name');

define('_MI_MXDIR_PREMIUM1_DNAME', 'Normal Listing');
define('_MI_MXDIR_PREMIUM2_DNAME', 'Copper');
define('_MI_MXDIR_PREMIUM3_DNAME', 'Silver');
define('_MI_MXDIR_PREMIUM4_DNAME', 'Gold');
define('_MI_MXDIR_PREMIUM5_DNAME', 'Platinum');
define('_MI_MXDIR_COLOR', 'Color');

//RC1
//define('_MI_MXDIR_PREMDESC','&nbsp;&nbsp;<a href=\"javascript:openWithSelfMain('.XOOPS_URL.'/modules/'.$mydirname.'/admin/colors.html, \'\', 400, 300);\">Color Wheel</a>');
define('_MI_MXDIR_PREMDESC','&nbsp;&nbsp;<a href="'.XOOPS_URL.'/modules/'.$mydirname.'/admin/colors.html" target="_blank" height="400" width="300">Color Wheel</a>');
define('_MI_MXDIR_SPONSOR_ON', '-Sponsored listings- header Active?');
define('_MI_MXDIR_PREMIUM1_OPTS', '-----Default Level 1 Options');
define('_MI_MXDIR_PREMIUM2_OPTS', '-----Level 2 Options');
define('_MI_MXDIR_PREMIUM3_OPTS', '-----Level 3 Options');
define('_MI_MXDIR_PREMIUM4_OPTS', '-----Level 4 Options');
define('_MI_MXDIR_PREMIUM5_OPTS', '-----Level 5 Options');

define('_MI_MXDIR_PREMIUM1_COPT', '-----Default Level 1 Color (#FFFFFF)');
define('_MI_MXDIR_PREMIUM2_COPT', '-----Level 2 Color (#FFFFFF)');
define('_MI_MXDIR_PREMIUM3_COPT', '-----Level 3 Color (#FFFFFF)');
define('_MI_MXDIR_PREMIUM4_COPT', '-----Level 4 Color (#FFFFFF)');
define('_MI_MXDIR_PREMIUM5_COPT', '-----Level 5 Color (#FFFFFF)');

define('_MI_MXDIR_PREMIUM_ACTV', 'User Selectable Levels');
define('_MI_MXDIR_PREMIUM_ON', 'Activate Level Options');
define('_MI_MXDIR_PREMIUM_UON', 'Activate Level in User Selects');
define('_MI_MXDIR_PREMIUM_SLLI', 'Activate SiteLink in Links');
define('_MI_MXDIR_PREMIUM_SLSLI', 'Activate SiteLink in Sponsored Links');
define('_MI_MXDIR_PREMIUM_SLLF', 'Activate SiteLink in Listings');
define('_MI_MXDIR_PREMIUM_CALI', 'Activate Coupon in Links');
define('_MI_MXDIR_PREMIUM_CALF', 'Activate Coupon in Listings');
define('_MI_MXDIR_PREMIUM_LOLI', 'Activate Logos in Links');
define('_MI_MXDIR_PREMIUM_LOLF', 'Activate Logos in Listing');
define('_MI_MXDIR_PREMIUM_LLSLI', 'List level in Sponsored Links');
define('_MI_MXDIR_PREMIUM_LLCSLI', 'Display level Color in Sponsored Links');

define('_MI_MXDIR_PREMIUM_XNAME', 'Name');
define('_MI_MXDIR_PREMIUM_XOPTS', 'Options');
define('_MI_MXDIR_PREMIUM_XCOLOR', 'Color (#FFFFFF)');

//END RC1
define('_MI_MXDIR_LOGOSIZE', 'Logo Max File Size');
define('_MI_MXDIR_LOGOWIDTH', 'Logo Max File Width');
define('_MI_MXDIR_LOGOHEIGHT', 'Logo Max File Height');
define('_MI_MXDIR_COUPSIZE', 'Coupon Image Max File Size');
define('_MI_MXDIR_COUPWIDTH', 'Coupon Image Max File Width');
define('_MI_MXDIR_COUPHEIGHT', 'Coupon Image Max File Height');
define('_MI_MXDIR_USEALPHA', 'Show Alpha Sort List?');
define('_MI_MXDIR_USESEARCH', 'Show Search Box?');

define('_MI_MXDIR_CAPTCHANON', 'Captcha Graphic for Anonymous User?');
define('_MI_MXDIR_CAPTCHANONDSC', 'Forms will display security graphic to anonymous users');
define('_MI_MXDIR_NUMLTRS', 'Add Numbers to Sort Menu?');
define('_MI_MXDIR_NUMLTRSDSC', 'Will also allow searching for Listings that start with numbers');
define('_MI_MXDIR_CHOOSETIME', 'Use 12 hour (am/pm) clock?');
define('_MI_MXDIR_CHOOSETIMEDESC', 'For Business Hour Select Box <br />No to use 24 hr. clock');

//Description of each template
define("_MI_MXDIR_LISTINGFULLDSC","");
define("_MI_MXDIR_VIEWALPHADSC","");
define("_MI_MXDIR_BROKENLINKDSC","");
define("_MI_MXDIR_LINKDSC","");
define("_MI_MXDIR_INDEXDSC","");
define("_MI_MXDIR_MODLINKDSC","");
define("_MI_MXDIR_RATELINKDSC","");
define("_MI_MXDIR_SINGLELINKDSC","");
define("_MI_MXDIR_SUBMITDSC","");
define("_MI_MXDIR_TOPTENDSC","");
define("_MI_MXDIR_VIEWCATDSC","");
define("_MI_MXDIR_PREMIUMLINKDSC","");
define("_MI_MXDIR_PRINTDSC","");
define("_MI_MXDIR_SAVINGSDSC","");
define("_MI_MXDIR_PRINT_SAVINGSDSC","");
define("_MI_MXDIR_MYLISTINGS","");

// Description of each config items
define('_MI_MXDIR_POPULARDSC', '');
define('_MI_MXDIR_NEWLINKSDSC', '');
define('_MI_MXDIR_PERPAGEDSC', '');
define('_MI_MXDIR_USEFRAMEDSC', '');
define('_MI_MXDIR_USESHOTSDSC', '');
define('_MI_MXDIR_SHOTWIDTHDSC', '');
define('_MI_MXDIR_AUTOAPPROVEDSC','');
define('_MI_MXDIR_PREMIUM1DSC', '');
define('_MI_MXDIR_PREMIUM2DSC', '');
define('_MI_MXDIR_PREMIUM3DSC', '');
define('_MI_MXDIR_PREMIUM4DSC', '');
define('_MI_MXDIR_PREMIUM5DSC', '');

define('_MI_MXDIR_SPONSOR_ONDSC', '');

define('_MI_MXDIR_PREMIUM1DSC_ON', '');
define('_MI_MXDIR_PREMIUM2DSC_ON', '');
define('_MI_MXDIR_PREMIUM3DSC_ON', '');
define('_MI_MXDIR_PREMIUM4DSC_ON', '');
define('_MI_MXDIR_PREMIUM5DSC_ON', '');
define('_MI_MXDIR_PREMIUM1DSC_CON', '');
define('_MI_MXDIR_PREMIUM2DSC_CON', '');
define('_MI_MXDIR_PREMIUM3DSC_CON', '');
define('_MI_MXDIR_PREMIUM4DSC_CON', '');
define('_MI_MXDIR_PREMIUM5DSC_CON', '');

define('_MI_MXDIR_LOGOSIZEDSC', '');
define('_MI_MXDIR_LOGOWIDTHDSC', '');
define('_MI_MXDIR_LOGOHEIGHTDSC', '');
define('_MI_MXDIR_COUPSIZEDSC', '');
define('_MI_MXDIR_COUPWIDTHDSC', '');
define('_MI_MXDIR_COUPHEIGHTDSC', '');
define('_MI_MXDIR_USEALPHADSC', '');
define('_MI_MXDIR_USESEARCHDSC', '');

define('_MI_MXDIR_COLORDSC', '');
define("_MI_MXDIR_COUPON_FOOTER", "Coupon footer text");
define("_MI_MXDIR_COUPON_FOOTERDESC", "This text will go below the coupons in the printer friendly coupon page");

// Text for notifications

define('_MI_MXDIR_GLOBAL_NOTIFY', 'Global');
define('_MI_MXDIR_GLOBAL_NOTIFYDSC', 'Global business listing notification options.');

define('_MI_MXDIR_CATEGORY_NOTIFY', 'Category');
define('_MI_MXDIR_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current business category.');

define('_MI_MXDIR_LINK_NOTIFY', 'Listing');
define('_MI_MXDIR_LINK_NOTIFYDSC', 'Notification options that aply to the current listing.');

define('_MI_MXDIR_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_MXDIR_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new listing category is created.');
define('_MI_MXDIR_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new listing category is created.');
define('_MI_MXDIR_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing category');

define('_MI_MXDIR_GLOBAL_LINKMODIFY_NOTIFY', 'Modify Listing Requested');
define('_MI_MXDIR_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notify me of any listing modification requests.');
define('_MI_MXDIR_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Receive notification when any listing modification request is submitted.');
define('_MI_MXDIR_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Listing Modification Requested');

define('_MI_MXDIR_GLOBAL_LINKBROKEN_NOTIFY', 'Broken Link Submitted');
define('_MI_MXDIR_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notify me of any broken link report.');
define('_MI_MXDIR_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Receive notification when any broken link report is submitted.');
define('_MI_MXDIR_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Link Reported');

define('_MI_MXDIR_GLOBAL_LINKSUBMIT_NOTIFY', 'New Listing Submitted');
define('_MI_MXDIR_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notify me when any new listing is submitted (awaiting approval).');
define('_MI_MXDIR_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Receive notification when any new listing is submitted (awaiting approval).');
define('_MI_MXDIR_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing submitted');

define('_MI_MXDIR_GLOBAL_NEWLINK_NOTIFY', 'New Listing');
define('_MI_MXDIR_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new listing is posted.');
define('_MI_MXDIR_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new listing is posted.');
define('_MI_MXDIR_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New listing');

define('_MI_MXDIR_CATEGORY_LINKSUBMIT_NOTIFY', 'New Listing Submitted');
define('_MI_MXDIR_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notify me when a new listing is submitted (awaiting approval) to the current category.');
define('_MI_MXDIR_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Receive notification when a new link is submitted (awaiting approval) to the current category.');
define('_MI_MXDIR_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New link submitted in category');

define('_MI_MXDIR_CATEGORY_NEWLINK_NOTIFY', 'New Listing');
define('_MI_MXDIR_CATEGORY_NEWLINK_NOTIFYCAP', 'Notify me when a new listing is posted to the current category.');
define('_MI_MXDIR_CATEGORY_NEWLINK_NOTIFYDSC', 'Receive notification when a new listing is posted to the current category.');
define('_MI_MXDIR_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New business listing in category');

define('_MI_MXDIR_LINK_APPROVE_NOTIFY', 'Listing Approved');
define('_MI_MXDIR_LINK_APPROVE_NOTIFYCAP', 'Notify me when this listing is approved.');
define('_MI_MXDIR_LINK_APPROVE_NOTIFYDSC', 'Receive notification when this listing is approved.');
define('_MI_MXDIR_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Listing approved');

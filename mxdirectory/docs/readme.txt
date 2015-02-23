README FIRST
-----------------------

See install.txt for installation instructions.

LANGUAGE FILES - If you can help translate, please do.
The only accurate language for this RC1 release is english.

To make things easier for translators, we have included a change list in :
language/_RC1_Changes
the files will show additions, deletions, and changes for RC1

**************************************************************************************
Known Issues:
**************************************************************************************
XHTML Validation - Due to the issue below, this module will not validate fully at this time. (but it functions fine)

Due to architecture of 2.0.x & allowing both 2.0 & 2.2 to run under a single module....:
When NOT cached, the pages will load the stylesheet and rss wrapper (FF Live Bookmarks) 2 times.
Unfortunately, that's necessary in order to have the stylesheet display when the module wide cache settings are enabled.

A workaround exists for 2.2 via smarty, but have not yet found a way to fix in 2.0.

**************************************************************************************
Directory Naming DOES NOT FUNCTION WITH UPGRADE from xdirectory or Beta1 only for a Beta 2 upgrade or new install.
**************************************************************************************
Variable directory name:
The module 'display' name can be edited via admin., text below is talking about the directory itself.

The code of mx-directory allows for the module directory to be custom named.
this is for the directory name that will display in the URL...

To implement a custom name for the directory:

BEFORE INSTALLING

1. Change the folder name to a lower case text name without numbers spaces or other stupid characters.
2. go to module admin (after changes above are made)
3. install the module

All of the settings should autodetect and the module should now work with the custom name you provided

**************************************************************************************
**** Preferences
**************************************************************************************
Settings here should be self explanatory.

To select multiple features for a level (in multi-select boxes) hold down ctrl.
The colors assigned to levels will override css (see below).

**************************************************************************************
***TIPS:
**************************************************************************************
To easily customize, I have added a stylesheet which is located in
images/style.css

If you have a listing assigned to a Option Level (premium) and disable the level, the listings in that level will still display, just without options, this is by design. ((baseline)data presented regardless of view)


**************************************************************************************
***Coupons:
**************************************************************************************
Use the admin section to add coupons & manage existing coupons.

Savings.php will display a categorized listing of active coupons and appears as a sub-menu item as well as a link on the directory pages.

If your coupons don't show up after adding them, make sure the time settings are correct for the server and xoops.

All links with coupons will display a coupon button.

I decided not to tie the coupons into Premium services (aside from display) to allow you more flexibility.  As admin, you can add a coupon to any listing.  There are no coupon limits as described in the premium section description page (matrix.php) you can add as many as you like.

Coupons will pull logos from the listing and can provide an additional unique coupon image if desired.

Coupon counts when the print coupon button is clicked and the format is as follows:
#of views+Couponid
so for coupon id #3 with 2 views, the top right of the coupon will display: 23
so for coupon id #3 with 10 views, the top right of the coupon will display: 103

I just thought it would be better to do it this way than a 1,2,3 counter

Savings will show *all active coupons...regardless of your view settings per Option level. This is by design, use the admin to edit coupons if necessary.
**************************************************************************************
***Premium Services:
**************************************************************************************
You can now name them from the admin., the listing 'levels' function according to select box settings...

Edit the matrix.php page to reflect your setup. (click "what's this" next to the premium dropdown in 'submit' to view)
*********************************************************************************
***Multiple Categories:
*********************************************************************************
Only the admin can add multiple categories.
Use from the Multicat Manager in the admin (categories) section.

This is an RC, please don't make feature requests, please do expose bugs.


If you experience difficulties with the process,  post to the support or bug tracker at:
    http://dev.xoops.org/modules/xfmod/tracker/?group_id=1223

Thanks,
Tripmon
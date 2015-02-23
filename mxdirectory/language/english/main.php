<?php
// $Id: main.php 11970 2013-08-24 14:20:57Z beckmi $
//%%%%%%		Module Name 'Directory'		%%%%%
//$mydirname = $xoopsModule->getVar('dirname')
//2.2.x Change for dir discovery
$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

define("_MD_MXDIR_THANKSFORINFO","Thanks for the information. We'll look into your request shortly.");
define("_MD_MXDIR_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");
define("_MD_MXDIR_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");
define('_MD_MXDIR_UPGRADECOMPLETE', 'Upgrade Complete');
define('_MD_MXDIR_UPDATEMODULE', 'Update module templates and blocks');
define('_MD_MXDIR_UPGRADEFAILED', 'Upgrade Failed');
define('_MD_MXDIR_UPGRADE', 'Upgrade');
define('_MD_MXDIR_UPGRADEFAILED1', 'Error Creating the table xdir_coupon');
define('_MD_MXDIR_UPGRADEFAILED2', "Impossible to change the topic title's length");
define('_MD_MXDIR_UPGRADEFAILED21', "Impossible to add the new fields to the topics table");
define('_MD_MXDIR_UPGRADEFAILED3', 'Impossible to create the table xdirectory_votedata');
define('_MD_MXDIR_UPGRADEFAILED4', "Impossible to create the two fields 'rating' and 'votes' for the 'story' table");
define('_MD_MXDIR_UPGRADEFAILED0', "Please note the messages and try to correct the problems with phpMyadmin and the sql definition's file available in the 'sql' folder of the mxdirectory module");
define('_MD_MXDIR_UPGR_ACCESS_ERROR',"Error... to use the upgrade script, you must be an admin on this module");
define('_MD_MXDIR_PLEASE_UPGRADE',"<a href='upgrade.php'><font style=\"font-color: #FF0000\">Please upgrade the module !</font></a>");

define("_MD_MXDIR_SEARCHFOR","Search for");
define("_MD_MXDIR_ANY","ANY");
define("_MD_MXDIR_SEARCH","Search");

define("_MD_MXDIR_MAIN","Main");
define("_MD_MXDIR_SUBMITLINK","Submit Listing");
define("_MD_MXDIR_SUBMITLINKHEAD","Submit New Business Listing");
define("_MD_MXDIR_POPULAR","Popular");
define("_MD_MXDIR_TOPRATED","Top Rated");

define("_MD_MXDIR_NEWTHISWEEK","New this week");
define("_MD_MXDIR_UPTHISWEEK","Updated this week");
define("_MD_MXDIR_ATTENTION","<font style=\"font-weight: bold;\">Attention Business Owners!</font> Getting your business listed is easy and FREE! <br />Simply <a href=\"".XOOPS_URL."/register.php\"><span style=\"text-decoration: underline;\">register with {X_SITENAME}</span></a> Once registration is completed <br />just logon and <a href=\"".XOOPS_URL."/modules/" .$mydirname. "/submit.php\"><span style=\"text-decoration: underline;\">submit your business</span></a>.");
define("_MD_MXDIR_SPONSLISTHEAD","Sponsored Listings");
define("_MD_MXDIR_ALLLISTHEAD","All Listings");


define("_MD_MXDIR_POPULARITYLTOM","Popularity (Least to Most Hits)");
define("_MD_MXDIR_POPULARITYMTOL","Popularity (Most to Least Hits)");
define("_MD_MXDIR_TITLEATOZ","Business Name (A to Z)");
define("_MD_MXDIR_TITLEZTOA","Business Name (Z to A)");
define("_MD_MXDIR_DATEOLD","Date (Old Listings First)");
define("_MD_MXDIR_DATENEW","Date (New Listings First)");
define("_MD_MXDIR_RATINGLTOH","Rating (Lowest Score to Highest Score)");
define("_MD_MXDIR_RATINGHTOL","Rating (Highest Score to Lowest Score)");

define("_MD_MXDIR_NOSHOTS","No Screenshots Available");
define("_MD_MXDIR_EDITTHISLINK","Edit This Listing");

define("_MD_MXDIR_DESCRIPTIONC","Description: ");
define("_MD_MXDIR_DESCRIPTHELP","Please use this area to enter your hours of operation along with a brief description of your business");
define("_MD_MXDIR_EMAILC","Email: ");
define("_MD_MXDIR_CATEGORYC","Category: ");
define("_MD_MXDIR_CATEGORYC1","Category -alt1: ");
define("_MD_MXDIR_CATEGORYC2","Category -alt2: ");
define("_MD_MXDIR_CATEGORYC3","Category -alt3: ");
define("_MD_MXDIR_CATEGORYC4","Category -alt4: ");
define("_MD_MXDIR_NONE","---NONE---");
define("_MD_MXDIR_LASTUPDATEC","Last Update: ");
define("_MD_MXDIR_HITSC","Hits: ");
define("_MD_MXDIR_RATINGC","Rating: ");
define("_MD_MXDIR_ONEVOTE","1 vote");
define("_MD_MXDIR_NUMVOTES","%s votes");
define("_MD_MXDIR_RATETHISSITE","Rate this Business");
define("_MD_MXDIR_MODIFY","Modify this Listing");
define("_MD_MXDIR_REPORTBROKEN","Report Incorrect Info");
define("_MD_MXDIR_TELLAFRIEND","Tell a Friend");

define("_MD_MXDIR_THEREARE","There are <font style=\"font-weight: bold;\">%s</font> businesses in our Database");
define("_MD_MXDIR_LATESTLIST","Latest Business Listings");

define("_MD_MXDIR_REQUESTMOD","Request Business Listing Modification");
define("_MD_MXDIR_LINKID","Listing ID: ");
define("_MD_MXDIR_SITETITLE","Business Name: ");
define("_MD_MXDIR_BUSADDRESS","Address: ");
define("_MD_MXDIR_BUSADDRESS1","Address1: ");
define("_MD_MXDIR_BUSADDRESS2","Address2: ");
define("_MD_MXDIR_BUSCITY","City: ");
define("_MD_MXDIR_BUSSTATE","County / State: "); // EVU CODE - state
define("_MD_MXDIR_BUSZIP","Postcode / Zip: ");  // EVU CODE
define("_MD_MXDIR_BUSCOUNTRY","Country: ");
define("_MD_MXDIR_BUSOPEN","Open: ");
define("_MD_MXDIR_ALOPEN","Open 24 hrs"); // EVU CODE
define("_MD_MXDIR_UNKNOWN","Unknown");
define("_MD_MXDIR_BUSCLOSE","Close: ");
define("_MD_MXDIR_BUSHRS","Business Hours: ");
define("_MD_MXDIR_BUSCLOSED"," Closed "); // EVU CODE
define("_MD_MXDIR_BUSMFHRS","Hours Mon-Fri: ");  // EVU CODE Hours M-F:
define("_MD_MXDIR_BUSMFHRSSHORT","Mon-Fri: ");// EVU CODE "M-F:
define("_MD_MXDIR_BUSSATHRS","Hours Sat: ");
define("_MD_MXDIR_BUSSATHRSSHORT","Sat: ");
define("_MD_MXDIR_BUSSUNHRS","Hours Sun: ");
define("_MD_MXDIR_BUSSUNHRSSHORT","Sun: ");
define("_MD_MXDIR_PHONENUMS","Phone numbers: ");
define("_MD_MXDIR_BUSMAIN","Main: ");
define("_MD_MXDIR_BUSPHONE","Phone: ");
define("_MD_MXDIR_BUSPH","Tel: "); // EVU CODE Ph#
define("_MD_MXDIR_BUSFAX","Fax: ");
define("_MD_MXDIR_BUSMOBILE","Mobile: ");
define("_MD_MXDIR_BUSMO","Mob: ");
define("_MD_MXDIR_BUSHOME","Home: ");
define("_MD_MXDIR_BUSHO"," Home: "); // EVU CODE Hm
define("_MD_MXDIR_BUSTOLLFREE","Free phone no: ");  // EVU CODE - Toll-Free
define("_MD_MXDIR_BUSTF","800: ");
define("_MD_MXDIR_BUSEMAIL","E-Mail: ");
define("_MD_MXDIR_SITEURL","Website: ");
define("_MD_MXDIR_BUSADMINFO","Contact: ");
define("_MD_MXDIR_BUSADMCONT","Contact Information:<br />(Not-Disclosed)");
define("_MD_MXDIR_BUSADMCONTNAME","Contact Name &nbsp;&nbsp;: ");
define("_MD_MXDIR_BUSADMCONTNUMB","Contact Number: ");
define("_MD_MXDIR_OPTIONS","Options: ");
define("_MD_MXDIR_WHATTHIS","What's This?");
define("_MD_MXDIR_PREMIUM","Listing Type: ");

define("_MD_MXDIR_SELCOLOR","Select Color");

define("_MD_MXDIR_NO_PREMIUM","-NONE- ");
define("_MD_MXDIR_PREMIUM1","Bronze: ");
define("_MD_MXDIR_PREMIUM2","Copper: ");
define("_MD_MXDIR_PREMIUM3","Silver: ");
define("_MD_MXDIR_PREMIUM4","Gold: ");
define("_MD_MXDIR_PREMIUM5","Platinum: ");

define("_MD_MXDIR_FAIL_SECURITY", "Submission FAILED:<br />Please complete the <font style=\"font-style: italic;\">secure code</font> entry.");
define("_MD_MXDIR_FAIL_GD_LOAD", "There is a problem with your server configuration.<br />FAIL: GD Load Test");
define("_MD_MXDIR_SECURITY_CODE", "Security Code" );
define("_MD_MXDIR_SECURITY_TYPE", "Enter Security Code" );
define("_MD_MXDIR_NO_SPAM", "No SPAM! Security Check");

define("_MD_MXDIR_NOTIFYAPPROVE", "Notify me when this Listing is approved");
define("_MD_MXDIR_SHOTIMAGE","Logo Img: ");
define("_MD_MXDIR_SENDREQUEST","Send Request");
define("_MD_MXDIR_LOGOUP","Upload Logo");
define("_MD_MXDIR_COUPUP","Upload Coupon");
define("_MD_MXDIR_LOGOSEL","Select Logo");
define("_MD_MXDIR_COUPSEL","Select Coupon");
define("_MD_MXDIR_UPTHIS","Upload Logo");

define("_MD_MXDIR_VOTEAPPRE","Your vote is appreciated.");
define("_MD_MXDIR_THANKURATE","Thank you for taking the time to rate a site here at %s.");
define("_MD_MXDIR_VOTEFROMYOU","Input from users such as yourself will help other visitors better decide which businesses to choose.");
define("_MD_MXDIR_VOTEONCE","Please do not vote for the same resource more than once.");
define("_MD_MXDIR_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_MD_MXDIR_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_MD_MXDIR_DONOTVOTE","Do not vote for your own resource.");
define("_MD_MXDIR_RATEIT","Rate It!");

define("_MD_MXDIR_INTRESTLINK","Interesting Business Listing at %s");  // %s is your site name
define("_MD_MXDIR_INTLINKFOUND","Here is an interesting Business I have found at %s");  // %s is your site name
define("_MD_MXDIR_INQ","Inquiry sent from");
define("_MD_MXDIR_MESSAGE_SENT","Your message has been sent");  // %s is your site name
define("_MD_MXDIR_FRIEND","Friend");
define("_MD_MXDIR_EMAILIT","Send E-Mail");
define("_MD_MXDIR_YOUR","Your");
define("_MD_MXDIR_NAME","Name");
define("_MD_MXDIR_SUBJECT","Subject");
define("_MD_MXDIR_MESSAGE","Message");

define("_MD_MXDIR_RECEIVED","We've received your business information. Thank you!");
define("_MD_MXDIR_WHENAPPROVED","You'll receive an e-mail when your business listing has been approved.");
define("_MD_MXDIR_SUBMITONCE","Submit your business listing only once.");
define("_MD_MXDIR_ALLPENDING","All business listing information is posted pending verification.");
define("_MD_MXDIR_DONTABUSE","Username and IP are recorded.");
define("_MD_MXDIR_TAKESHOT","It may take up to several days for your business listing to be added to our live database.");

define("_MD_MXDIR_RANK","Rank");
define("_MD_MXDIR_CATEGORY","Primary Category");
define("_MD_MXDIR_HITS","Hits");
define("_MD_MXDIR_RATING","Rating");
define("_MD_MXDIR_VOTE","Vote");
define("_MD_MXDIR_TOP10","%s Top 10"); // %s is a link category title

define("_MD_MXDIR_SEARCHRESULTS","Search results for <font style=\"font-weight: bold;\">%s</font>:"); // %s is search keywords
define("_MD_MXDIR_SORTBY","Sort by:");
define("_MD_MXDIR_TITLE","Business Name");
define("_MD_MXDIR_DATE","Date");
define("_MD_MXDIR_POPULARITY","Popularity");
define("_MD_MXDIR_CURSORTEDBY","Businesses currently sorted by: %s");
define("_MD_MXDIR_PREVIOUS","Previous");
define("_MD_MXDIR_NEXT","Next");
define("_MD_MXDIR_NOMATCH","No matches found to your query");
define("_MD_MXDIR_DIRHEADER","Business Directory");
define("_MD_MXDIR_RETURNCATVIEW","Return to category view");
define("_MD_MXDIR_ALLWORDS","All Words");
define("_MD_MXDIR_ANYWORDS","Any Words");
define("_MD_MXDIR_EXACTMATCH","Exact Match");
define("_MD_MXDIR_PRINTBTN","Print");
define("_MD_MXDIR_MAPBTN","Map");
define("_MD_MXDIR_EMAILBTN","Email");
define("_MD_MXDIR_MOREINFOBTN","More Info");
define("_MD_MXDIR_CLOSEWINBTN","Close Window");
define("_MD_MXDIR_LOGO","Logo");
define("_MD_MXDIR_LISTINGS","Listings");

define("_MD_MXDIR_SUBMIT","Submit");
define("_MD_MXDIR_CANCEL","Cancel");

define("_MD_MXDIR_ALREADYREPORTED","You have already submitted a broken report for this resource.");
define("_MD_MXDIR_MUSTREGFIRST","Sorry, you don't have the permission to perform this action.<br />Please register or login first!");
define("_MD_MXDIR_NORATING","No rating selected.");
define("_MD_MXDIR_CANTVOTEOWN","You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_MD_MXDIR_VOTEONCE2","Vote for the selected resource only once.<br />All votes are logged and reviewed.");
define("_MD_MXDIR_ELOGOSIZE","ABORTED - File size above Admin Logo Prefs !");
define("_MD_MXDIR_ELOGOWIDTH","ABORTED - File width above Admin Logo Prefs !");
define("_MD_MXDIR_ELOGOHEIGHT","ABORTED - File height above Admin Logo Prefs !");
define("_MD_MXDIR_ELOGOSAMENAME","A file with this name already exists, please rename your file and try again");
define("_MD_MXDIR_ELOGOTEMP","Error while renaming from temp file, please try again");
define("_MD_MXDIR_ELOGOTYPE","Only png, gif, or jpg !!!");
define("_MD_MXDIR_ELOGOUNK","Error: Number %s, please contact the site administrator");
define("_MD_MXDIR_FIELDEMPTY","Submitted form is missing required fields");
define("_MD_MXDIR_NOPERM","You do not have permission to modify this Listing");
define("_MD_MXDIR_NOLISTAVAIL","Error retrieving this Listing's information, please alert the administrator");
//%%%%%%	Module Name 'Directory' (Admin)	  %%%%%

define("_MD_MXDIR_WEBLINKSCONF","Directory Listing Configuration");
define("_MD_MXDIR_GENERALSET","Directory General Settings");
define("_MD_MXDIR_ADDMODDELETE","Add, Modify, and Delete Categories/Listings");
define("_MD_MXDIR_LINKSWAITING","Listings Waiting for Validation");
define("_MD_MXDIR_BROKENREPORTS","Broken Link Reports");
define("_MD_MXDIR_MODREQUESTS","Listing Modification Requests");
define("_MD_MXDIR_SUBMITTER","Submitter: ");
define("_MD_MXDIR_VISIT","Visit");
define("_MD_MXDIR_SHOTMUST","Screenshot image must be a valid image file under %s directory (ex. shot.gif). Leave it blank if no image file.");
define("_MD_MXDIR_APPROVE","Approve");
define("_MD_MXDIR_APPROVECHANGE","Approve & Change Owner to");
define("_MD_MXDIR_ANON","Anonymous");
define("_MD_MXDIR_DELETE","Delete");
define("_MD_MXDIR_DISP","Display");
define("_MD_MXDIR_NOSUBMITTED","No New Submitted Listings.");
define("_MD_MXDIR_ADDMAIN","Add a MAIN Category");
define("_MD_MXDIR_TITLEC","Category Name: ");
define("_MD_MXDIR_IMGURL","Image URL (OPTIONAL: height auto-sized to 50): ");
define("_MD_MXDIR_ADD","Add");
define("_MD_MXDIR_ADDSUB","Add a SUB-Category");
define("_MD_MXDIR_IN","in");
define("_MD_MXDIR_ADDNEWLINK","Add a New Business Listing");
define("_MD_MXDIR_MODCAT","Modify Category");
define("_MD_MXDIR_MULTICAT","Multiple Categories");
define("_MD_MXDIR_MULTICATMGR","Multiple Category Manager");
define("_MD_MXDIR_MODLINK","Modify Listing");
define("_MD_MXDIR_TOTALVOTES","Business Listing Votes (total votes: %s)");
define("_MD_MXDIR_USERTOTALVOTES","Registered User Votes (total votes: %s)");
define("_MD_MXDIR_ANONTOTALVOTES","Anonymous User Votes (total votes: %s)");
define("_MD_MXDIR_USER","User");
define("_MD_MXDIR_IP","IP Address");
define("_MD_MXDIR_USERAVG","User AVG Rating");
define("_MD_MXDIR_TOTALRATE","Total Ratings");
define("_MD_MXDIR_NOREGVOTES","No Registered User Votes");
define("_MD_MXDIR_NOUNREGVOTES","No Unregistered User Votes");
define("_MD_MXDIR_VOTEDELETED","Vote data deleted.");
define("_MD_MXDIR_NOBROKEN","No reported broken links.");
define("_MD_MXDIR_IGNOREDESC","Ignore (Ignores the report and only deletes the <font style=\"font-style: bold;\">report data</font>)");
define("_MD_MXDIR_DELETEDESC","Delete (Deletes <font style=\"font-weight: bold;\">the listing (all data)</font> and <font style=\"font-weight: bold;\">report data</font> for the link.)");
define("_MD_MXDIR_DISPDESC","Display (Displays the Original and Proposed Modifications for Approval.)");
define("_MD_MXDIR_REPORTER","Report Sender");
define("_MD_MXDIR_LINKSUBMITTER","Listing Submitter");
define("_MD_MXDIR_IGNORE","Ignore");
define("_MD_MXDIR_LINKDELETED","Listing Deleted.");
define("_MD_MXDIR_BROKENDELETED","Broken link report deleted.");
define("_MD_MXDIR_USERMODREQ","User Listing Modification Requests");
define("_MD_MXDIR_ORIGINAL","Original");
define("_MD_MXDIR_PROPOSED","Proposed");
define("_MD_MXDIR_OWNER","Owner: ");
define("_MD_MXDIR_UPANDDOWN","up and down:");
define("_MD_MXDIR_CHANGEOWNER","Grant Ownership to ");
define("_MD_MXDIR_NOMODREQ","No Listing Modification Request.");
define("_MD_MXDIR_DBUPDATED","Database Updated Successfully!");
define("_MD_MXDIR_MODREQDELETED","Modification Request Deleted.");
define("_MD_MXDIR_MODREQPENDING","Modification Request Pending");
define("_MD_MXDIR_APPROVALPENDING","Approval Pending");
define("_MD_MXDIR_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
define("_MD_MXDIR_PARENT","Parent Category:");
define("_MD_MXDIR_SAVE","Save Changes");
define("_MD_MXDIR_CATDELETED","Category Deleted.");
define("_MD_MXDIR_WARNING","WARNING: Are you sure you want to delete this Category and ALL of its Listings and Comments?");
define("_MD_MXDIR_YES","Yes");
define("_MD_MXDIR_NO","No");
define("_MD_MXDIR_NEWCATADDED","New Category Added Successfully!");
define("_MD_MXDIR_ERROREXIST","ERROR: The Business you provided is already in the database!");
define("_MD_MXDIR_ERRORTITLE","ERROR: You need to enter Business Name!");
define("_MD_MXDIR_ERRORDESC","ERROR: You need to enter DESCRIPTION!");
define("_MD_MXDIR_NEWLINKADDED","New Listing added to the Database.");
define("_MD_MXDIR_YOURLINK","Your Website Link at %s");
define("_MD_MXDIR_YOUCANBROWSE","You can browse our directory listing at %s");
define("_MD_MXDIR_HELLO","Hello %s");
define("_MD_MXDIR_WEAPPROVED","We've approved your business listing submission to our business directory database.");
define("_MD_MXDIR_THANKSSUBMIT","Thank you for your submission!");
define("_MD_MXDIR_ISAPPROVED","We approved your business listing submission");
define("_MD_MXDIR_CATSNOEXIST","Please setup categories before adding Listings");

define("_MD_MXDIR_SAVINGS", "Special Offers");
define("_MD_MXDIR_COUPONFORM", "Add/Edit Coupon");
define("_MD_MXDIR_COUPMOD", "View/Edit Coupons by Listing");
define("_MD_MXDIR_COUPMODADD", "Add New Coupons to Listing");

define("_MD_MXDIR_ADDCOUPON", "Add Coupon");
define("_MD_MXDIR_EDITCOUPON", "Edit Coupon");
define("_MD_MXDIR_COUPONADDED", "Coupon Added Successfully");
define("_MD_MXDIR_DELCOUPON", "Delete Coupon");
define("_MD_MXDIR_COUPONDELETED", "Coupon Deleted Successfully");
define("_MD_MXDIR_PRINTERFRIENDLY", "Printer Friendly Page");
define("_MD_MXDIR_PRINTFOOTER", "More Listings available at ");
define("_MD_MXDIR_NOSAVINGS", "There are no special offers for this category or listing");
define("_MD_MXDIR_COUPONEDITED", "Coupon Information Updated");
define("_MD_MXDIR_COUPONRUSURE", "Are you sure you want to DELETE this coupon?");
define("_MD_MXDIR_PUBLISHCOUPON", "Coupon Publish Time");
define("_MD_MXDIR_EXPIRECOUPON", "Coupon Expire Time");
define("_MD_MXDIR_CONVERTLBR", "Convert Linebreaks");
define("_MD_MXDIR_PUBLISHEDON", "Valid From ");
define("_MD_MXDIR_EXPIRESON", "Expires On ");
define("_MD_MXDIR_SETEXPIRATION", "Set Expiration date?");
define("_MD_MXDIR_COUPONHEADER", "Coupon Heading");
define("_MD_MXDIR_COUPONHITS", "Counter");
define("_MD_MXDIR_COUPONIMG", "Coupon Image: /uploads/)");
define("_MD_MXDIR_SAVINGS_IMGTXT", "Special Offers");
define("_MD_MXDIR_COUPONIMGMGR", "Use ImageManager (in Description Toolbar above) to upload images.");

define("_MD_MXDIR_RSSFMTTL","Generate RSS Feed URL");
define("_MD_MXDIR_RSSOPV","Date,Popular,Random,Rating");
define("_MD_MXDIR_RSSOPK","date,hits,rand,rate");
define("_MD_MXDIR_RSSQTY","5,10,25,50");
define("_MD_MXDIR_RSSQTYLBL","Number of Items:");
define("_MD_MXDIR_RSSURL","RSS URL");
define("_MD_MXDIR_TSTLNK","Test Link");
define("_MD_MXDIR_ALLCATS","* ALL *");

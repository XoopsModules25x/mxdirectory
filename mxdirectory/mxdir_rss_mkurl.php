<?php
include_once("header.php");
include_once(XOOPS_ROOT_PATH."/header.php");

global $xoopsUser, $xoopsDB, $mydirname, $xoopsModuleConfig;
//include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
$mydirname = basename ( dirname( __FILE__ ) ) ;
include XOOPS_ROOT_PATH."/modules/" . $mydirname . "/class/mxdirectorytree.php";

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include "include/securitycheck.php";
$myts =& MyTextSanitizer::getInstance();
$mytree = new MxdirectoryTree($xoopsDB->prefix("xdir_cat"),"cid","pid");

// setup the op array
$op_akey = explode(",",_MD_MXDIR_RSSOPK);
$op_aval = explode(",",_MD_MXDIR_RSSOPV);
for ($i=0;$i<count($op_akey);$i++){ $op_array[$op_akey[$i]] = $op_aval[$i]; }

// setup quantity array
$rss_qkey = explode(",",_MD_MXDIR_RSSQTY);
for ($i=0;$i<count($rss_qkey);$i++){ $q_array[$rss_qkey[$i]] = $rss_qkey[$i]; }

if (!empty($_POST['submit'])) {
  // check captcha security
  if ( empty($_POST['captcha_stat']) ) { redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY); }
    switch ($_POST['captcha_stat']) {
      case false:
      case 0:
        redirect_header(XOOPS_URL."./",5,_MD_MXDIR_FAIL_GD_LOAD);
        break;
      case 1:
      case 2:
        if (empty($_POST["security"]) || empty($_POST["sec_hidden"])) {
          redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
        } else {
          // values are set - now verify
          $sec_post = $myts->addSlashes($_POST["security"]);
          $sec_post_hidden = $myts->addSlashes($_POST["sec_hidden"]);
          $spass = mx_security_check($sec_post,$sec_post_hidden);
          if ($spass === false){ redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY); }
        }
        break;
      default:
        break;
  }
  // end of security graphic validation check

  // set RSS Feed Type (date, rand, hits, etc.)
  reset($op_akey);
  if ( !isset($_POST['rss_op']) || empty($_POST['rss_op']) ) {
    $rss_op =  current($op_akey);
  } else {
    $rss_op = addSlashes($_POST['rss_op']);
    if (!array_key_exists($rss_op,$op_array)) {
      reset($op_array);
      $rss_op = current($op_akey);
    }
  }

  // set RSS Category Info
  if (!isset($_POST['rss_catid']) || empty($_POST['rss_catid'])) {
    $rss_catid = 0;
  } else {
    $rss_catid = intval($_POST['rss_catid']);
    if ($rss_catid != 0) {
      $result = $xoopsDB->query("SELECT cid FROM ".$xoopsDB->prefix("xdir_cat")." WHERE cid=".$rss_catid." LIMIT 0,1");
      $mycat = $xoopsDB->fetchArray($result);
      $rss_catid = ($mycat === false) ? 0 : $rss_catid ;
    } else {
      $rss_catid = 0;
    }
  }

  // set RSS Quantity (number of feeds to return)
  reset($rss_qkey);
  if (!isset($_POST['rss_qty']) || empty($_POST['rss_qty'])) {
   $rss_qty = current($rss_qkey);
  } else {
    $qtyck = in_array(intval($_POST['rss_qty']),$q_array);
    reset($rss_qkey);
    $rss_qty = ($qtyck != false) ? intval($_POST['rss_qty']) : current($rss_qkey) ;
  }
} else {

  // set RSS Feed Type (date, rand, hits, etc.)
  reset($op_akey);
  $rss_op =  current($op_akey);

  // set Category to All
  $rss_catid = 0;

  // set RSS Quantity (number of feeds to return)
  reset($rss_qkey);
  $rss_qty = current($rss_qkey);
}

// -- FORM DISPLAY --

$rssform = new XoopsThemeForm(_MD_MXDIR_RSSFMTTL, 'rssform', $_SERVER['PHP_SELF'], 'POST');

// Get RSS Option Type
$sel_op = (new XoopsFormSelect(_MD_MXDIR_SORTBY , 'rss_op', $rss_op, 1,false));
foreach ($op_array as $key => $value) { $sel_op -> addOption($key,$value); }
$rssform->addElement($sel_op);

// display RSS Category
$tree = $mytree->getChildTreeArray(0,"title ASC");
$sel_cat = (new XoopsFormSelect(_MD_MXDIR_CATEGORYC , 'rss_catid', $rss_catid, 1,false));
$sel_cat-> addOption(0,_MD_MXDIR_ALLCATS);
foreach ($tree as $branch ) {
  $branch['prefix'] = substr($branch['prefix'], 0, -1);
  $branch['prefix'] = str_replace(".","--",$branch['prefix']);
  $sel_cat -> addOption($branch['cid'], $branch['prefix'].$branch['title']);
}
$rssform->addElement($sel_cat);

// Get RSS Quantity
$sel_qty = (new XoopsFormSelect(_MD_MXDIR_RSSQTYLBL , 'rss_qty', $rss_qty, 1, false));
for($i=0;$i<count($q_array);$i++) { $sel_qty -> addOption(intval($rss_qkey[$i]),intval($rss_qkey[$i])); }
$rssform->addElement($sel_qty);

// Setup captcha security
if ( empty($xoopsUser) && $xoopsModuleConfig['captcha_anon']) {
  $gd = ( extension_loaded('gd') ) ? 1 : false ;
  $gd = ( extension_loaded('gd2') ) ? 2 : $gd ;
  $captcha_stat = $gd;
  if ( $gd ){
    mt_srand((double)microtime()*10000);
    $random_num = mt_rand(0, 100000);
    $security = "<img src='getgfx.php?random_num=$random_num&amp;gd=$gd' border='1' alt='"._MD_MXDIR_SECURITY_CODE."' title='"._MD_MXDIR_SECURITY_CODE."' />&nbsp;&nbsp;"
                ."<img src='images/no-spam.jpg' alt='"._MD_MXDIR_NO_SPAM."' title='"._MD_MXDIR_NO_SPAM."' />";
    $captchatray = new XoopsFormElementTray('','<br />');
    $captchatray->addElement(new XoopsFormLabel('' , $security));
    $captchatray->addElement(new XoopsFormHidden('sec_hidden',$random_num));
    $captchatray->addElement(new XoopsFormText('','security',15,10,''));
    $rssform->addElement($captchatray);
  }
} else {
  $captcha_stat = 99;
}
$rssform->addElement(new XoopsFormHidden('captcha_stat', $captcha_stat));

// now create URL
$myurl = XOOPS_URL."/modules/".$mydirname."/mxdir_rss.php?op=".$rss_op."&amp;catid=".$rss_catid."&amp;qty=".$rss_qty;
$myurl = htmlspecialchars($myurl);
$tstlnk = " <a href='".$myurl."' target='_blank'><img src='./images/rss/rss-".$rss_op.".jpg' alt='"._MD_MXDIR_TSTLNK."' width='80' height='15'  /></a>";
//$myurl = urlencode($myurl);

$rssform->addElement(new XoopsFormTextArea(_MD_MXDIR_RSSURL.'<br /><br />'.$tstlnk, 'myurl', $myurl, 5, 50));

$regtray = new XoopsFormElementTray('');
$sbtn=new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit');
$regtray->addElement(new XoopsFormHidden('submit',true));
$regtray->addElement(new XoopsFormButton('', 'cancel', _MD_MXDIR_CANCEL, 'reset'));
$regtray->addElement($sbtn);

$rssform->addElement($regtray);
$rssform->display();

include_once (XOOPS_ROOT_PATH."/footer.php");
?>
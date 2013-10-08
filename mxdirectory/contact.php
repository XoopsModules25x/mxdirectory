<?php
//returned vars
include_once("header.php");
include_once(XOOPS_ROOT_PATH."/header.php");
if ( file_exists("language/".$xoopsConfig['language']."/main.php") ) {
	include_once "language/".$xoopsConfig['language']."/main.php";
} else {
	include_once "language/english/main.php";
}

include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include "include/securitycheck.php";
//include "include/functions.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$myts =& MyTextSanitizer::getInstance();
//Consumer to Business
function ctob(){
	global $xoopsConfig, $_GET, $xoopsDB, $myts, $meta;

	global $xoopsModuleConfig, $_REQUEST, $xoopsDB, $myts, $meta, $xoopsUser;

$id = ( isset($_REQUEST['id']) )? $_REQUEST['id']  : null ;
$lid = ( isset($_REQUEST['lid']) )? $_REQUEST['lid'] : null ;
$op = ( isset($_REQUEST['op']) )? $_REQUEST['op'] : null ;
$sender = ( isset($_REQUEST['sender']) )? $_REQUEST['sender'] : null ;
$subject = ( isset($_REQUEST['subject']) )? $_REQUEST['subject'] : null ;
$body = ( isset($_REQUEST['body']) )? $_REQUEST['body'] : null ;
$xsname = ( isset($_REQUEST['sname']) )? $_REQUEST['sname'] : null ;
$xsemail = ( isset($_REQUEST['semail']) )? $_REQUEST['semail'] : null ;
$frname = ( isset($_REQUEST['frname']) )? $_REQUEST['frname'] : null ;
$fremail = ( isset($_REQUEST['fremail']) )? $_REQUEST['fremail'] : null ;

$result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$lid'");
	list($linktitle, $email) = $xoopsDB->fetchRow($result);

//may need 2.2 changes here due to u.n. conventions

	if($xoopsUser) {
		$xsname =$xoopsUser->getVar("uname");
		$xsname = ($xsname == "") ? $xoopsUser->getVar("name") : $xsname ;

		$xsemail =$xoopsUser->getVar("email");
	} 	
	
	$ctobform = new XoopsThemeForm(_MD_MXDIR_EMAILC.' '._MD_MXDIR_SITETITLE.' '.$linktitle, 'ctobform', $_SERVER['PHP_SELF'].'?op=sendit', 'POST');
	$ctobform->addElement(new XoopsFormLabel(_MD_MXDIR_SUBJECT, $linktitle));
	$ctobform->addElement(new XoopsFormText(_MD_MXDIR_YOUR.' '._MD_MXDIR_NAME , 'sname', 50, 100, $xsname),true);
	$ctobform->addElement(new XoopsFormText(_MD_MXDIR_YOUR.' '._MD_MXDIR_EMAILC , 'semail', 50, 50, $xsemail),true);
	$ctobform->addElement(new XoopsFormTextArea(_MD_MXDIR_MESSAGE, 'body', '', 5, 50, ''));
	$regtray = new XoopsFormElementTray('','','');

 	if ( empty($xoopsUser) && $xoopsModuleConfig['captcha_anon'] ) {
		$gd = ( extension_loaded('gd') ) ? 1 : false ;
		$gd = ( extension_loaded('gd2') ) ? 2 : $gd ;
		$captcha_stat = $gd;
		if ( $gd ){
			mt_srand((double)microtime()*10000);
			$random_num = mt_rand(0, 100000);
			$security = "<img src='getgfx.php?random_num=$random_num&amp;gd=$gd' border='1' alt='"._MD_MXDIR_SECURITY_CODE."' title='"._MD_MXDIR_SECURITY_CODE."' />&nbsp;&nbsp;"
									."<img src='images/no-spam.jpg' alt='"._MD_MXDIR_NO_SPAM."' title='"._MD_MXDIR_NO_SPAM."' />";
			$captchatray = new XoopsFormElementTray('','&nbsp','cappie');
			$captchatray->addElement(new XoopsFormLabel('' , $security));
			$captchatray->addElement(new XoopsFormHidden('sec_hidden',$random_num));
			$captchatray->addElement(new XoopsFormText('','security',15,10,''));
			$regtray->addElement($captchatray);
		}
	} else {
		$captcha_stat = 99;
	}
	
	$ctobform->addElement(new XoopsFormHidden('captcha_stat', $captcha_stat));
	//	zyspec - end of security graphic code	 */
	$sbtn=new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit');
	$regtray->addElement($sbtn);
	$ctobform->addElement($regtray);	
	$ctobform->addElement(new XoopsFormHidden('tomailto',$email));
	$ctobform->addElement(new XoopsFormHidden('lid',$lid));
	$ctobform->addElement(new XoopsFormHidden('linktitle',$linktitle));
//	$ctobform->addElement(new XoopsFormHidden('title',$title));
	$ctobform->addElement(new XoopsFormHidden('subject',$linktitle.' '._MD_MXDIR_INQ.' '.$xoopsConfig['sitename']));
	$ctobform->addElement(new XoopsFormHidden('thistemplate', 'ctob_mail.tpl'));
	$ctobform->display();
}



//Tell A Friend
function tell(){
//	global $xoopsModuleConfig, $_GET, $_REQUEST, $xoopsDB, $myts, $meta, $xoopsUser, $xoopsConfig;
	global $xoopsConfig, $_GET, $xoopsDB, $myts, $meta, $xoopsUser;
	global $xoopsModuleConfig, $_REQUEST, $xoopsDB, $myts, $meta, $xoopsUser;

$id = ( isset($_REQUEST['id']) )? $_REQUEST['id']  : null ;
$lid = ( isset($_REQUEST['lid']) )? $_REQUEST['lid'] : null ;
$op = ( isset($_REQUEST['op']) )? $_REQUEST['op'] : null ;
$sender = ( isset($_REQUEST['sender']) )? $_REQUEST['sender'] : null ;
$subject = ( isset($_REQUEST['subject']) )? $_REQUEST['subject'] : null ;
$body = ( isset($_REQUEST['body']) )? $_REQUEST['body'] : null ;
$xsname = ( isset($_REQUEST['sname']) )? $_REQUEST['sname'] : null ;
$xsemail = ( isset($_REQUEST['semail']) )? $_REQUEST['semail'] : null ;
$frname = ( isset($_REQUEST['frname']) )? $_REQUEST['frname'] : null ;
$fremail = ( isset($_REQUEST['fremail']) )? $_REQUEST['fremail'] : null ;

$result = $xoopsDB->query("SELECT title, email FROM ".$xoopsDB->prefix("xdir_links")." WHERE lid = '$lid'");
	list($linktitle, $email) = $xoopsDB->fetchRow($result);

//may need 2.2 changes here due to u.n. conventions
	if($xoopsUser) {
    reset($xoopsUser);
		$xsname =$xoopsUser->getVar("uname");
		$xsname = ($xsname == "") ? $xoopsUser->getVar("name") : $xsname ;
		$xsemail =$xoopsUser->getVar("email");
	}	
//print_r ($xoopsConfig);
//echo $xoopsUser->getVar("uname");
//echo $xoopsUser->getVar("email");
//echo "<=HERE";	
//exit;
	$tfform = new XoopsThemeForm(_MD_MXDIR_TELLAFRIEND, 'tfform', $_SERVER['PHP_SELF'].'?op=sendit', 'POST');
	$tfform->addElement(new XoopsFormText(_MD_MXDIR_YOUR.' '._MD_MXDIR_NAME , 'sname', 50, 100,$xsname));
	$tfform->addElement(new XoopsFormText(_MD_MXDIR_FRIEND.' '._MD_MXDIR_NAME , 'frname', 50, 50, ''));
	$tfform->addElement(new XoopsFormText(_MD_MXDIR_FRIEND.' '._MD_MXDIR_EMAILC , 'fremail', 50, 50, ''));
	$tfform->addElement(new XoopsFormLabel(_MD_MXDIR_SUBJECT, $subject));
	$tfform->addElement(new XoopsFormLabel(_MD_MXDIR_TITLE, $linktitle));
	$regtray = new XoopsFormElementTray('','','');

 	if ( empty($xoopsUser) && $xoopsModuleConfig['captcha_anon'] ) {
		$gd = ( extension_loaded('gd') ) ? 1 : false ;
		$gd = ( extension_loaded('gd2') ) ? 2 : $gd ;
		$captcha_stat = $gd;
		if ( $gd ){
			mt_srand((double)microtime()*10000);
			$random_num = mt_rand(0, 100000);
			$security = "<img src='getgfx.php?random_num=$random_num&amp;gd=$gd' border='1' alt='"._MD_MXDIR_SECURITY_CODE."' title='"._MD_MXDIR_SECURITY_CODE."' />&nbsp;&nbsp;"
									."<img src='images/no-spam.jpg' alt='"._MD_MXDIR_NO_SPAM."' title='"._MD_MXDIR_NO_SPAM."' />";
			$captchatray = new XoopsFormElementTray('','&nbsp','cappie');
			$captchatray->addElement(new XoopsFormLabel('' , $security));
			$captchatray->addElement(new XoopsFormHidden('sec_hidden',$random_num));
			$captchatray->addElement(new XoopsFormText('','security',15,10,''));
			$regtray->addElement($captchatray);
		}
	} else {
		$captcha_stat = 99;
	}
	
	$tfform->addElement(new XoopsFormHidden('captcha_stat', $captcha_stat));
	//	zyspec - end of security graphic code	 */
	$sbtn=new XoopsFormButton('', '', _MD_MXDIR_SUBMIT, 'submit');
	$regtray->addElement($sbtn);
	$tfform->addElement($regtray);	
	$tfform->addElement(new XoopsFormHidden('id',$id));
	$tfform->addElement(new XoopsFormHidden('lid',$lid));
	$tfform->addElement(new XoopsFormHidden('subject',$subject));
//	$tfform->addElement(new XoopsFormHidden('body',$body));
	$tfform->addElement(new XoopsFormHidden('linktitle',$linktitle));
	$tfform->addElement(new XoopsFormHidden('thistemplate', 'tellafriend_mail.tpl'));
	$tfform->display();
  }

function sendit(){
	global $xoopsConfig, $_POST, $xoopsDB, $myts, $meta, $mydirname;
	include XOOPS_ROOT_PATH."/class/xoopsmailer.php";
//	print_r ($_POST);
//	exit;
	
	extract($_POST, EXTR_PREFIX_ALL, "post");
	
  $id = ( isset($post_id) )? ( $myts->htmlSpecialChars($post_id) ) : null ;
  $lid = ( isset($post_lid) )? ( $myts->htmlSpecialChars($post_lid) ) : null ;
  $op = ( isset($post_op) )? ( $myts->htmlSpecialChars($post_op) ) : null ;
  $sender = ( isset($post_sender) )? ( $myts->htmlSpecialChars($post_sender) ) : null ;
  $subject = ( isset($post_subject) )? ( $myts->htmlSpecialChars($post_subject) ) : null ;
  $body = ( isset($post_body) )? ( $myts->htmlSpecialChars($post_body) ) : null ;
  $sname = ( isset($post_sname) )? ( $myts->htmlSpecialChars($post_sname) ) : null ;
  $semail = ( isset($post_semail) )? ( $myts->htmlSpecialChars($post_semail) ) : null ;
  $frname = ( isset($post_frname) )? ( $myts->htmlSpecialChars($post_frname) ) : null ;
  $tomailto = ( isset($post_fremail) )? ( $myts->htmlSpecialChars($post_fremail) ) : null ;
  $title = ( isset($post_title) )? ( $myts->htmlSpecialChars($post_title) ) : null ;
  $linktitle = ( isset($post_linktitle) )? ( $myts->htmlSpecialChars($post_linktitle) ) : null ;
  $tomailto = ( isset($post_tomailto) )? ( $myts->htmlSpecialChars($post_tomailto) ) : $tomailto ;
  $thistemplate = ( isset($post_thistemplate) )? $post_thistemplate : null ;
  
  $xadminmail = $xoopsConfig['adminmail'];//setting from to server in case of SPF=>will admin config this as well
  $xsitename = $xoopsConfig['sitename'];//Adding site title as sender (mod config this?)
  $xsiteurl = $xoopsConfig['xoops_url'];//Adding site url
  $linkurl = ( isset($lid) )? (XOOPS_URL.'/modules/'.$mydirname.'/singlelink.php?lid='.$lid) : null ;

// check captcha security	
	if ( empty($_POST['captcha_stat']) ) {
	 	redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
  }
	switch ($_POST['captcha_stat']) {
		case false:
		case 0:
			redirect_header(XOOPS_URL."./",5,_MD_MXDIR_FAIL_GD_LOAD);
			break;
		case 1:
		case 2:
			if (empty($_POST["security"]) || empty($_POST["sec_hidden"])) {
				$eh->show("0008");
			} else {
			// values are set - now verify
				$sec_post = $myts->addSlashes($_POST["security"]);
				$sec_post_hidden = $myts->addSlashes($_POST["sec_hidden"]);
				$spass = mx_security_check($sec_post,$sec_post_hidden);
				if ($spass === false){
			  	redirect_header("./",5,_MD_MXDIR_FAIL_SECURITY);
 				}
	  	}
			break;
		default:
			break;
	}
	// end of security graphic validation check
		
	//mail to customer and admin
	$xoopsMailer =& getMailer(); //Get mailer object
	$xoopsMailer->useMail(); // Set it to use email (as opposed to PM)
	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$xoopsConfig['language'].'/mail_template/');
	$xoopsMailer->setTemplate($thistemplate);

	$xoopsMailer->assign('SNAME', $sname); //assign some vars for mail template
	$xoopsMailer->assign('SEMAIL', $semail);
	$xoopsMailer->assign('SUBJECT', $subject);
	$xoopsMailer->assign('FRNAME', $frname);
	$xoopsMailer->assign('FREMAIL', $fremail);
	$xoopsMailer->assign('X_ADMINMAIL', $xadminmail);
	$xoopsMailer->assign('X_SITENAME', $xsitename);
	$xoopsMailer->assign('X_SITEURL', $xsiteurl);	
	$xoopsMailer->assign('X_LINK', $linkurl);
	$xoopsMailer->assign('X_LINK_TITLE', $linktitle);			
	$xoopsMailer->assign('BODY', $body);


	$xoopsMailer->setToEmails($tomailto);
	$xoopsMailer->setFromEmail($xadminmail);
	$xoopsMailer->setFromName($xsitename);	
//	$xoopsMailer->setBody($body); //if not using a mail template use setBody instead
	$xoopsMailer->setSubject($subject);

//echo $mydirname;	
//print_r ($xoopsMailer);
//		exit;
	if ($xoopsMailer->send()) {
//	echo $xoopsMailer->getErrors();
//		echo "<= That's Why";
//			exit; 
		//send was successful
		redirect_header("index.php",1,_MD_MXDIR_MESSAGE_SENT);
	}else{
// 	echo $xoopsMailer->getErrors();
//		echo "<= That's Why";
//			exit; 
			redirect_header("index.php",1,_MD_MXDIR_ELOGOUNK);
	}
	include (XOOPS_ROOT_PATH."/footer.php");
}


//op switcher
if(!isset($_REQUEST['op'])) {
	$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'tell';
} else {
	$op = $_REQUEST['op'];
}



switch ($op) {
case "tell":
	tell();
	break;
case "ctob":
	ctob();
	break;
case "sendit":
	sendit();
	break;
default:
	redirect_header("contact.php?op=tell",1,""._RETURNGLO."");
	break;
} 
include_once (XOOPS_ROOT_PATH."/footer.php"); 
?>
<?php
//
// This code was tested using Waiting 0.94 & - zyspec
//
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

eval( '

function b_waiting_'.$mydirname.'(){
	return b_waiting_mxdirectory_base( "'.$mydirname.'" ) ;
}

' ) ;

if( ! function_exists( 'b_waiting_mxdirectory_base' ) ) {

function b_waiting_mxdirectory_base( $mydirname )
{
	$xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();

	$mydirname = (basename ( dirname ( dirname( __FILE__ ) ) , "a" ) ) ;

	// new event links
	$block = array();
	if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) echo ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
	$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('xdir_links')." WHERE status=0");
	if ( $result ) {
//		$block['adminlink'] = XOOPS_URL."/modules/."$mydirname."/admin/index.php";
//		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
//		$block['lang_linkname'] = _PI_WAITING_EVENTS ;
		list($cnt) = $xoopsDB->fetchRow($result);
		$block[] = array("adminlink" => XOOPS_URL."/modules/".$mydirname."/admin/main.php?op=listNewLinks",
											"pendingnum" => $cnt,
											"lang_linkname" => _PI_WAITING_SUBMITTED );
	}
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('xdir_mod'));
	if ( $result ) {
//		$block['adminlink'] = XOOPS_URL."/modules/."$mydirname."/admin/index.php";
//		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
//		$block['lang_linkname'] = _PI_WAITING_EVENTS ;
		list($cnt) = $xoopsDB->fetchRow($result);
		$block[] = array("adminlink" => XOOPS_URL."/modules/".$mydirname."/admin/main.php?op=listModReq",
											"pendingnum" => $cnt,
											"lang_linkname" => _PI_WAITING_MODREQS );
	}
	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix('xdir_broken'));
	if ( $result ) {
		list($cnt) = $xoopsDB->fetchRow($result);
		$block[] = array("adminlink" => XOOPS_URL."/modules/".$mydirname."/admin/main.php?op=listBrokenLinks",
											"pendingnum" => $cnt,
											"lang_linkname" => _PI_WAITING_BROKENS );
	}
	return $block;
}
}
?>
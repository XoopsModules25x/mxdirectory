<?php
// comment callback functions

function xdir_com_update($link_id, $total_num){
	$db =& XoopsDatabaseFactory::getDatabaseConnection();
	$sql = 'UPDATE '.$db->prefix('xdir_links').' SET comments = '.$total_num.' WHERE lid = '.$link_id;
	$db->query($sql);
}

function xdir_com_approve(&$comment){
	// notification mail here
}
?>
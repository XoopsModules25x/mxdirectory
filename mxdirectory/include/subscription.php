<?php

/*
('_MI_MXDIR_PREMIUM1_DNAME', 'Normal Listing');
('_MI_MXDIR_PREMIUM2_DNAME', 'Copper');
('_MI_MXDIR_PREMIUM3_DNAME', 'Silver');
('_MI_MXDIR_PREMIUM4_DNAME', 'Gold');
('_MI_MXDIR_PREMIUM5_DNAME', 'Platinum');
<option value='1'>Normal Listing</option> / AKA BRONZE I THINK
<option value='2'>Copper</option>

<option value='3'>Silver</option>
<option value='4'>Gold</option>
<option value='5'>Platinum</option>

*/

define('_MI_MXDIR_ACTIVATEPAYMENT', '1'); // 0 - for to deactivate

define('_MI_MXDIR_PREMIUM1_DNAME_GROUP', '8');
define('_MI_MXDIR_PREMIUM2_DNAME_GROUP', '6');
define('_MI_MXDIR_PREMIUM3_DNAME_GROUP', '4');
define('_MI_MXDIR_PREMIUM4_DNAME_GROUP', '5');
define('_MI_MXDIR_PREMIUM5_DNAME_GROUP', '7');



//$uid=$submitter
function UpdateListingType($lid, $uid)
{

$submitterGroups = $thisUser->getGroups();
echo "-1-<P>";print_r($submitterGroups);
$PremiumID =0;


if ( in_array(_MI_MXDIR_PREMIUM5_DNAME_GROUP, $submitterGroups ) )
{ 
	$PremiumID = 5; 
}elseif ( in_array(_MI_MXDIR_PREMIUM4_DNAME_GROUP, $submitterGroups ) ) {
	$PremiumID = 4; 
}
elseif ( in_array(_MI_MXDIR_PREMIUM3_DNAME_GROUP, $verGrupos) ) {
	$PremiumID = 3; 
}
elseif ( in_array(_MI_MXDIR_PREMIUM2_DNAME_GROUP, $verGrupos) ) {
	$PremiumID = 2; 
}
elseif ( in_array(_MI_MXDIR_PREMIUM1_DNAME_GROUP, $verGrupos) ) {
	$PremiumID = 1; 
}
elseif ( in_array(_MI_MXDIR_PREMIUM0_DNAME_GROUP, $verGrupos) ) {
	$PremiumID = 0; 
}


	global $xoopsDB;
            $sql = "UPDATE ".$this->db->prefix("xdir_links")." SET
                    premium= ".$PremiumID."  WHERE lid = ".$lid;
	$result=$xoopsDB->queryF($sql);
	return $result;
}

?>
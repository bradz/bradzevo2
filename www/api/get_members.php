<?php
/**
 * Interface to get the member list
 *
 * @author morrow
 * @version Alpha
 *
 */

// enable error reporting
error_reporting(E_ALL);

// preliminaries
define('IN_EVO', true);
include('common.php');

// get all members with access
$members_tools_object = new members( TOOLS_GROUP );
$members_tools = $members_tools_object -> get_members();

//echo "<html><head></head><body>";
echo "p_nick,username,phone,planet_coords\n";
foreach( $members_tools as $key => $user_id ) {
	$member = new member( $user_id );
	echo $member -> get_p_nick() . "," . $member -> get_username() . "," . $member -> get_phone() . "," . $member -> get_planet_coords() ."\n";
}
//echo "</body></html>";
?>
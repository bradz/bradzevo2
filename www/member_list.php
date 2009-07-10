<?php
/**
 * Shows the alliances members
 *
 * @author morrow
 *
 */

// enable error reporting
error_reporting(E_ALL);

// preliminaries
define('IN_EVO', true);
include('common.php');

// page specific settings
$pagetitle = ' - Member List';

// header
include('includes/header_html.inc.php');
include('includes/header.inc.php');

// open main table and include sidebar
echo "<table class=\"main\"><tr><td class=\"sidebar\">";
include('includes/sidebar.inc.php');
echo "</td><td class=\"content\">";

// main content title
echo "<div class=\"content_header\">MEMBER LIST <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

echo "<div class=\"content_subheader\">MEMBERS WITH TOOLS ACCESS <strong>&laquo;</strong></div>";

$tools_group = new group( TOOLS_GROUP );
$tools_members= $tools_group->get_members();

echo "<div class=\"content_item\">";
echo "<table>";
echo "<tr class=\"title\">";
echo "<td>Username</td>";
echo "<td>P-Nick</td>";
echo "<td>Phone Number</td>";
echo "<td>Planet</td>";
echo "</tr>";

foreach( $tools_members as $key => $user_id ) {
	$member = new member( $user_id );
	if( ( $key + 1 ) % 2  == 0 ) {
		echo "<tr class=\"even\">";
	} else {
		echo "<tr class=\"odd\">";
	}
	echo "<td><a href=\"member_fleets.php?&user_id=" . $user_id . "\">". $member -> get_username() . "</a></td>";
	echo "<td>" . $member -> get_p_nick() . "</td>";
	echo "<td>" . $member -> get_phone() . "</td>";	
	echo "<td>" . $member -> get_planet_coords() . "</td>";
	echo "</tr>";
}

echo "</table>";
echo "</div>";

/*
 * MAIN CONTENT ENDS HERE *****************************************************
 */

// close main table
echo "</td></tr></table>";

// footer
include('includes/footer.inc.php');
include('includes/footer_html.inc.php');
?>
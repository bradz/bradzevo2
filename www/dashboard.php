<?php
/**
 * This is the primary landing page to the tools.
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

// page specific settings
$pagetitle = ' - Dashboard';

// header
include('includes/header_html.inc.php');
include('includes/header.inc.php');

// open main table and include sidebar
echo "<table class=\"main\"><tr><td class=\"sidebar\">";
include('includes/sidebar.inc.php');
echo "</td><td class=\"content\">";

// main content title
echo "<div class=\"content_header\">DASHBOARD <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

//$mft = new member_fleet( 0, 54 );
//$mft->save_fleet();	

/*
echo "<div class=\"content_subheader\">YOUR PLANET <strong>&laquo;</strong></div>";
echo "<div class=\"content_item\">";
echo "</div>";

echo "<div class=\"content_subheader\">YOUR FLEET <strong>&laquo;</strong></div>";
echo "<div class=\"content_item\">";
echo "</div>";

echo "<div class=\"content_subheader\">HOSTILES WITH FLEETS OUT <strong>&laquo;</strong></div>";
echo "<div class=\"content_item\">";
echo "</div>";
*/

/*
 * MAIN CONTENT ENDS HERE *****************************************************
 */

// close main table
echo "</td></tr></table>";

// footer
include('includes/footer.inc.php');
include('includes/footer_html.inc.php');
?>
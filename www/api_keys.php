<?php
/**
 * Shows the API keys
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
$pagetitle = ' - API Keys';

// header
include('includes/header_html.inc.php');
include('includes/header.inc.php');

// open main table and include sidebar
echo "<table class=\"main\"><tr><td class=\"sidebar\">";
include('includes/sidebar.inc.php');
echo "</td><td class=\"content\">";

// main content title
echo "<div class=\"content_header\">API KEYS <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

echo "<div class=\"content_subheader\">CURRENTLY ENABLED API KEYS <strong>&laquo;</strong></div>";

$api = new api();
$api_keys = $api -> get_keys();

echo "<div class=\"content_item\">";
echo "<table>";
echo "<tr class=\"title\">";
echo "<td>Key Value</td>";
echo "<td>Key Comment</td>";
echo "</tr>";

foreach( $api_keys as $i => $key_id ) {
	$api_key = new api_key( $key_id );
	if( ( $i + 1 ) % 2  == 0 ) {
		echo "<tr class=\"even\">";
	} else {
		echo "<tr class=\"odd\">";
	}
	echo "<td>" . $api_key -> get_key() . "</td>";
	echo "<td>" . $api_key -> get_comment() . "</td>";
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
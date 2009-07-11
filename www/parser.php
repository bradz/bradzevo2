<?php
/**
 * Parser site.
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
$pagetitle = ' - Parser';

// header
include('includes/header_html.inc.php');
include('includes/header.inc.php');

// open main table and include sidebar
echo "<table class=\"main\"><tr><td class=\"sidebar\">";
include('includes/sidebar.inc.php');
echo "</td><td class=\"content\">";

// main content title
echo "<div class=\"content_header\">PARSER <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

echo "<div class=\"content_subheader\">PASTE MISSIONS OR GALAXY STATUS <strong>&laquo;</strong></div>";

if( !isset( $_POST['parse'] ) ) {
	echo "<div class=\"content_item\" style=\"text-align: center;\">";
	echo "<br />";
	echo "<form action=\"\" method=\"post\">";
	echo "<textarea name=\"parse\" rows=\"5\" cols=\"60\" />";
	echo "</textarea>";
	echo "<br /><br />";
	echo "<input type=\"submit\" />";
	echo "</form>";
	echo "</div>";	
} else {
	$parse = new parse( $_POST['parse'], $my_user_id );
	$parse->process_parse();
	echo $parse->get_feedback();
}

/*
 * MAIN CONTENT ENDS HERE *****************************************************
 */

// close main table
echo "</td></tr></table>";

// footer
include('includes/footer.inc.php');
include('includes/footer_html.inc.php');
?>
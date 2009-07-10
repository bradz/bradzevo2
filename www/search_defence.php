<?php
/**
 * Search defence fleets by ETA and Anti-WHATCLASS:
 * 
 * TODO: All of it :P
 * 			Also, find some way of storing a users research.
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
echo "<div class=\"content_header\">SEARCH DEFENCE <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

echo "<div class=\"content_subheader\">SELECT CLASS AND ETA <strong>&laquo;</strong></div>";
	
echo "<div class=\"content_item\" style=\"text-align: center;\">";
echo "<form action=\"\" method=\"get\">";
//echo "<input type=\"hidden\" name=\"do\" value=\"search_defence\">";

echo "<select name=\"anti\">";

if( isset( $_GET['anti'] ) ) {
	
	if( $_GET['anti'] == 'fi' ) {
		echo "<option value=\"fi\" selected>Anti Fi</option>";
	} else {
		echo "<option value=\"fi\">Anti Fi</option>";
	}
	
	if( $_GET['anti'] == 'co' ) {
		echo "<option value=\"co\" selected>Anti Co</option>";
	} else {
		echo "<option value=\"co\">Anti Co</option>";
	}
	
	if( $_GET['anti'] == 'fr' ) {
		echo "<option value=\"fr\" selected>Anti Fr</option>";
	} else {
		echo "<option value=\"fr\">Anti Fr</option>";
	}
	
	if( $_GET['anti'] == 'de' ) {
		echo "<option value=\"de\" selected>Anti De</option>";
	} else {
		echo "<option value=\"de\">Anti De</option>";
	}
	
	if( $_GET['anti'] == 'cr' ) {
		echo "<option value=\"cr\" selected>Anti Cr</option>";
	} else {
		echo "<option value=\"cr\">Anti Cr</option>";
	}
	
	if( $_GET['anti'] == 'bs' ) {
		echo "<option value=\"bs\" selected>Anti Bs</option>";
	} else {
		echo "<option value=\"bs\">Anti Bs</option>";
	}
		
} else {
	echo "<option value=\"fi\">Anti Fi</option>";
	echo "<option value=\"co\">Anti Co</option>";
	echo "<option value=\"fr\">Anti Fr</option>";
	echo "<option value=\"de\">Anti De</option>";
	echo "<option value=\"cr\">Anti Cr</option>";
	echo "<option value=\"bs\">Anti Bs</option>";
}

echo "</select> ";
echo "<select name=\"eta\">";

if( isset($_GET['eta'] ) ) {
	
	if( $_GET['eta'] == 7 ) {
		echo "<option value=\"7\" selected>ETA 7</option>";	
	} else {
		echo "<option value=\"7\">ETA 7</option>";
	}
	
	if( $_GET['eta'] == 8 ) {
		echo "<option value=\"8\" selected>ETA 8</option>";	
	} else {
		echo "<option value=\"8\">ETA 8</option>";
	}
	
	if( $_GET['eta'] == 9 ) {
		echo "<option value=\"9\" selected>ETA 9</option>";	
	} else {
		echo "<option value=\"9\">ETA 9</option>";
	}
	
	if( $_GET['eta'] == 10 ) {
		echo "<option value=\"10\" selected>ETA 10</option>";	
	} else {
		echo "<option value=\"10\">ETA 10</option>";
	}
	
	if( $_GET['eta'] == 11 ) {
		echo "<option value=\"11\" selected>ETA 11</option>";	
	} else {
		echo "<option value=\"11\">ETA 11</option>";
	}
	
	if( $_GET['eta'] == 12 ) {
		echo "<option value=\"12\" selected>ETA 12</option>";	
	} else {
		echo "<option value=\"12\">ETA 12</option>";
	}
	
	if( $_GET['eta'] == 13 ) {
		echo "<option value=\"13\" selected>ETA 13</option>";	
	} else {
		echo "<option value=\"13\">ETA 13</option>";
	}
	
	if( $_GET['eta'] == 14 ) {
		echo "<option value=\"14\" selected>ETA 14</option>";	
	} else {
		echo "<option value=\"14\">ETA 14</option>";
	}
	
} else {
	echo "<option value=\"7\">ETA 7</option>";
	echo "<option value=\"8\">ETA 8</option>";
	echo "<option value=\"9\">ETA 9</option>";
	echo "<option value=\"10\">ETA 10</option>";
	echo "<option value=\"11\">ETA 11</option>";
	echo "<option value=\"12\">ETA 12</option>";
	echo "<option value=\"13\">ETA 13</option>";
}

echo "</select>";
echo "<input type=\"submit\" />";
echo "</form>";
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
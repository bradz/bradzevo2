<?php
/**
 * Stuff that is done before the site content displayed:
 * - __autoload for class loading
 * - read out the configuration
 * - create the database connection
 * - authenticate the user
 * 
 * This file must be included in every site!
 * 
 * @author morrow
 * @version Alpha
 * 
 */

/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

// autoload magic for including classes
function __autoload( $class_name ) {
	require_once('classes/' . $class_name . '.class.php');
}

// open configuration
$conf = new config('../evorc');
$conf -> get_config();

// open database connection
$db = new mysqli( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
if( $db -> connect_error ) {
	echo $db -> connect_error;
	
}

// abort if the cookie we need doesn't exist
if( !isset( $_COOKIE[COOKIE_NAME . '_sid'] ) or !isset( $_COOKIE[COOKIE_NAME . '_u'] ) or !isset( $_COOKIE[COOKIE_NAME . '_k'] ) ) {
	echo "<html>";
	echo "<head>";
	echo "<title>Evolution Planetarion Tools</title>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	echo "<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/grey_orange.css\" />";
	echo "</head>";
	echo "<body>";
	echo "Authentication failed. Please log in via the forum!";
	echo "<br /><br />";
	echo "[ <a href=\"" . PHPBB_RELATIVE_PATH . "ucp.php?mode=login\">Proceed</a> ]";
	echo "</body>";
	echo "</html>";
	exit;
}

// do authentication here!
$user = new user($_COOKIE[COOKIE_NAME . '_sid'], $_COOKIE[COOKIE_NAME . '_u'], $_COOKIE[COOKIE_NAME . '_k']);

if( $user -> auth_user() == false ) {
	echo "<html>";
	echo "<head>";
	echo "<title>Evolution Planetarion Tools</title>";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	echo "<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/grey_orange.css\" />";
	echo "</head>";
	echo "<body>";
	echo "Authentication failed. Please log in via the forum!";
	echo "<br /><br />";
	echo "[ <a href=\"" . PHPBB_RELATIVE_PATH . "ucp.php?mode=login\">Proceed</a> ]";
	echo "</body>";
	echo "</html>";
	exit;
}

$my_user_id = $user->get_my_user_id(); 
$my_username = $user->get_my_username();
$my_groups = $user->get_my_groups();

$tickers = new tickers();
$current_tick_local = $tickers->get_current_tick( 'local_ticker' );

$member = new member( $my_user_id );
$my_eta_bonus = $member->get_eta_bonus();
?>
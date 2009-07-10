<?php
/**
 * Stuff that is run for all API interfaces, such as
 * - authentication (API key)
 * - ...
 */

/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

// run all includes
include('../classes/config.class.php');
include('../classes/members.class.php');
include('../classes/member.class.php');
include('../classes/api.class.php');
include('../classes/api_key.class.php');
include('../classes/tickers.class.php');

// open configuration
$conf = new config('../../evorc');
$conf -> get_config();

// open database connection
$db = new mysqli( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
if( $db -> connect_error ) {
	echo $db -> connect_error;
}

/*
echo "<pre>";
var_dump($_POST);
echo "</pre>";
*/

// 37df021e2d95fa4a8b77b8c836f3bdc5fc102bc8
$api = new api();
if( !isset($_POST['key']) or $api -> verify_key( $_POST['key'] ) == false ) {
//if( !isset($_GET['key']) or $api -> verify_key( $_GET['key'] ) == false ) {
	echo "Invalid Key.";
	exit;
}

?>
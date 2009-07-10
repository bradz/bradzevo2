<?php
/**
 * Interface to set the local ticker
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

// set tick
if( isset( $_POST['ticker'] ) and isset( $_POST['tick'] ) ) {
	$tickers = new tickers();	
	if( $tickers -> set_current_tick( $_POST['ticker'], $_POST['tick'] ) ) {
		echo $_POST['tick'];
	} else {
		echo "Error.";
	}
} else {
	echo "Missing Parameters.";
}
?>
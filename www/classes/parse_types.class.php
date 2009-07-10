<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Class containing the valid parse types and their regular expressions.
 * 
 * @author morrow
 *
 */
class parse_types {
	public static $types = array('missions', 'galstatus');
	public static $types_re = array('/Missions/', '/Galaxy status/');
	
	/**
	 * Constructor.
	 * 
	 * @param $type
	 * @return unknown_type
	 */
	public function __construct() {
	}
}

?>
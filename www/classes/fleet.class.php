<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Generalized fleet class. Also provides basic template for the member_fleet class.
 * 
 * @author morrow
 * @version Alpha
 *
 */
class fleet {
	private $ships;
	private $from_coords;
	private $to_coords;
	private $tick_land;
	private $tick_reported;
	private $mission;
	
	private $db;
	
	/**
	 * Constructor.
	 * 
	 * @return (nothing)
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}
}
?>
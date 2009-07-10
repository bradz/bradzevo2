<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}


/**
 * Ticker class to interact with the various ticker values in the database.
 * 
 * @author morrow
 * @version Alpha
 */
class tickers {
	private $tickers = array('planet_dump', 'alliance_dump', 'intel_dump', 'local_ticker' );
	private $ticks = array();
	
	private $db;
	
	/**
	 * Constructor.
	 * 
	 * This on sets the $db instance for the object.
	 *  
	 * @return unknown_type
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}
	
	/**
	 * Get the value of the requested ticker. Performs db lookup if not done previously.
	 * 
	 * Returns either the current tick for the requested ticker or -1 if an error occured.
	 * 
	 * @param $ticker
	 * @return (int) current_tick
	 */
	public function get_current_tick( $ticker ) {
		// check if the requested ticker is valid
		if( !in_array( $ticker, $this -> tickers ) ) {
			return -1;
		}
		
		// if we already looked up the tick in the db, return it
		if( isset( $this -> ticks[$ticker] ) ) {
			return $this -> ticks[$ticker];
		}
		
		$sql = " SELECT t.tick";
		$sql = $sql . " FROM " . EVO_PREFIX . "_tickers t";
		$sql = $sql . " WHERE name = '" . $ticker . "';";
		
		if( $res = $this->db->query( $sql ) ) {
			$row = $res->fetch_object();
			$this->ticks[$ticker] = $row->tick;		
		} else {
			echo $this->db->error;
			exit;
		}
		
		return $this->ticks[$ticker];	
	}
	
	/**
	 * Set tick for a given ticker. Returns false if ticker doesn't exist, true if all is well.
	 * 
	 *  Also refreshes the object instance of the tick value that is updated.
	 * 
	 * @param $ticker
	 * @param $tick
	 * @return (boolean)
	 */
	public function set_current_tick( $ticker, $tick ) {
		// check if the requested ticker is valid
		if( !in_array( $ticker, $this->tickers ) ) {
			return false;
		}
		
		// update sql
		$sql = " UPDATE " . EVO_PREFIX . "_tickers t";
		$sql = $sql . " SET t.tick = " . (int) $this->db->real_escape_string( $tick );
		$sql = $sql . " WHERE t.name = '" . $ticker . "';"; 
		
		// update in db		
		if( !($this->db->query($sql) ) )	{
			echo $this->db->error;
			exit;
		}
		
		// update value in object instance
		$this -> ticks[$ticker] = $tick;

		// return true so we know we're all done
		return true;
	}
}
?>
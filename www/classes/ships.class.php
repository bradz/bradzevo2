<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Ship class. Lumps together information about the ships.
 * 
 * @author morrow
 * @version Alpha
 *
 */
class ships {
	private $ships_id;				// array of ship ids
	private $ships_name;			// array of ship names
	private $db;
	
	/**
	 * Constructor.
	 * 
	 * @return unknown_type
	 */
	public function __construct() {
		global $db;
		$this->db = $db;
	}
	
	/**
	 * Get the IDs for all ships in the game.
	 * 
	 * @return (array) (int) ships_id
	 */
	public function get_all_ships_id() {
		// only update if not already present in instance
		if( !isset($this->ships_id) ) {
			$sql = " SELECT s.id";
			$sql = $sql . " FROM " . EVO_PREFIX . "_ships s";
			
			if( $result = $this->db->query($sql) )  {
				while( $row = $result->fetch_object() ) {
					$this->ships_id[] = $row->id;
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->ships_id;
	}
	
	public function get_all_ships_name() {
		// only update if not already present in instance
		if( !isset($this->ships_name) ) {
			$sql = " SELECT s.name";
			$sql = $sql . " FROM " . EVO_PREFIX . "_ships s";
			
			if( $result = $this->db->query($sql) )  {
				while( $row = $result->fetch_object() ) {
					$this->ships_name[] = $row->name;
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->ships_name;
	}
}
?>
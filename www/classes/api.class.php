<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

class api {
	private $keys_id;
	private $db;
	
	public function __construct() {
		global $db;
		$this->db = $db;
	}
	
	public function verify_key( $key ) {
		$sql = "SELECT a.key_value";
		$sql = $sql . " FROM " . EVO_PREFIX . "_api a";
		$sql = $sql . " WHERE a.key_value = '" . $key .  "';";
		
		if( $result = $this->db->query($sql) ) {
			if( $result->num_rows == 1 ) {
				return true;
			} else {
				return false;
			}
		} else {
			echo $this->db->error;
			exit;
		}
	}
	
	public function get_keys() {
		if( !isset( $this->keys ) ) {
			$sql = "SELECT a.id";
			$sql = $sql . " FROM " . EVO_PREFIX . "_api a;";
			
			if( $result = $this->db->query($sql) ) {
				while( $row = $result->fetch_object() ) {
					$this -> keys_id[] = $row->id; 
				}
			} else {
				echo $this->db->error;
				exit;
			}
		}
		return $this->keys_id;
	}
}
?>
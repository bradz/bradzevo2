<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

class api_key {
	private $key_id;
	private $key;
	private $comment;
	private $db;
	
	public function __construct( $key_id ) {
		global $db;
		$this -> db = $db;
		$this -> key_id = (int) $key_id;
	}

	public function get_key() {
		if( !isset( $this -> key ) ) {
			$sql = "SELECT a.key_value";
			$sql = $sql . " FROM " . EVO_PREFIX . "_api a";
			$sql = $sql . " WHERE a.id = " . $this -> key_id . ";";
			
			if( $result = $this -> db -> query($sql) ) {
				while( $row = $result -> fetch_object() ) {
					$this -> key = $row -> key_value; 
				}
			} else {
				echo $this -> db -> error;
				exit;
			}
		}
		return $this -> key;
	}
	
	public function get_comment() {
		if( !isset( $this -> comment ) ) {
			$sql = "SELECT a.comment";
			$sql = $sql . " FROM " . EVO_PREFIX . "_api a";
			$sql = $sql . " WHERE a.id = " . $this -> key_id . ";";
			
			if( $result = $this -> db -> query($sql) ) {
				while( $row = $result -> fetch_object() ) {
					$this -> comment = $row -> comment; 
				}
			} else {
				echo $this -> db -> error;
				exit;
			}
		}
		return $this -> comment;
	}
}
?>
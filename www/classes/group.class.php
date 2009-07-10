<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Group class. This class implements members <-> groups mappings.
 *  
 * @author morrow
 * @version Alpha
 *
 */
class group {
	private $group_id;			// the group id in question
	private $members;			// id's of the members in the group
	private $db;

	/**
	 * Constructor. This requires the ID of the group in question.
	 * 
	 * @param $group_id
	 * @return unknown_type
	 */
	public function __construct( $group_id ) {
		global $db;
		$this->db = $db;
		$this->group_id = $group_id;
	} 
	
	/**
	 * Get IDs for all members in the group.
	 * 
	 * @return unknown_type
	 */
	public function get_members() {
		// only look in the db if we haven't done that before
		if( !isset( $this->members_id ) ) {
			$sql = "SELECT g.user_id";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_user_group g";
			$sql = $sql . " WHERE g.group_id = " . $this->group_id . ";";
			
			if( $result = $this->db->query($sql) ) {
				while( $row = $result->fetch_object() ) {
					$this->members[]=$row->user_id; 
				}
			} else {
				echo $this->db->error;
				exit; 
			}
		}
		return $this->members;
	}
}
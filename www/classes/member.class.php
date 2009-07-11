<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Member class. This takes care of authenticating members, etc.
 * 
 * @author morrow
 * @version Alpha
 */
class member {
	// access related
	private $user_id;				// user_id in the database
	private $username;				// username
	private $groups_id;				// array of group_id's the member is part of
	private $groups_name;			// dito, but with names of the groups
	
	// information related
	private $planet_id;				// id of planet (currently unused)
	private $planet_coords;			// coordinates of planet
	private $p_nick;				// pnick on netgamers
	private $phone;					// phone number
	private $eta_bonus;				// travel time (ETA) bonus
	
	// database
	private $db;
	
	/**
	 * Constructor for a member; requires the user_id of the member as input.
	 * 
	 * @param $user_id
	 * @return nothing
	 */
	public function __construct( $user_id ) {
		global $db;
		$this->db = $db;
		$this->user_id = (int) $user_id;
	}
	
	/**
	 * Fetches the IDs of all groups a particular member is part of.
	 * 
	 * @return (array) groups_id
	 */
	public function get_groups_id() {
		// only fetch stuff from db if not previously done
		if ( !isset( $this -> groups_id ) ) {
			// build the sql
			$sql = "SELECT g.group_id
					FROM " . PHPBB_PREFIX . "_user_group g
					WHERE g.user_id = " . $this->user_id . ";";
			
			// query the database
			if( $result = $this->db->query($sql) ) {
				while( $row = $result->fetch_object() ) {
					$this->groups_id[] = $row->group_id;
				}
			} else {
				echo $this->db->error;
				exit;
			}
		}
		return $this->groups_id; 
	}
	
	/**
	 * Based on the group id's, fetches the associated group names for the members' groups
	 * 
	 * TODO: Move this to some external place (group id <-> name mapping).
	 *  
	 * @return (array) groups_name
	 */
	public function get_groups_name() {
		// only fetch stuff from db if not previously done
		if ( !isset( $this->groups_name ) ) {
			// make sure we know all the id's
			$this -> get_groups_id();
			
			// some sql fiddling depending on how long the groups_id array is
			$sql = "SELECT g.group_name";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_groups g";
			if( count( $this->groups_id) == 1 ) {
				$sql = $sql . " WHERE g.group_id = ". $this->groups_id[1] .";";
			} else {
				foreach( $this->groups_id as $i => $group_id ) {
					echo $i;
					echo "<br />";
					if( $i == 0 ) {
						$sql = $sql . " WHERE g.group_id = ". $this->groups_id[$i];
					} elseif ( $i + 1 == count ( $this->groups_id ) ) {
						$sql = $sql . " or g.group_id = " . $group_id . ";";
					} else {
						$sql = $sql . " or g.group_id = " . $group_id;
					}
				}
			}
			
			// query the database
			if( $result = $this->db->query($sql) ) {
				while( $row = $result->fetch_object() ) {
					$this->groups_name[] = $row->group_name;
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->groups_name;	
	}
	
	/**
	 * Returns the username.
	 * 
	 * @return (string) username
	 */
	public function get_username() {
		if( !isset( $this->username ) ) {
			$sql = "SELECT u.username";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_users u";
			$sql = $sql . " WHERE u.user_id = " . $this->user_id . ";";
			if( $result = $this->db->query($sql) ) {
				$row = $result->fetch_object();
				$this->username = $row->username;
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->username;
	}
	
	/**
	 * Returns the P-Nick.
	 * 
	 * @return (string) pnick
	 */
	public function get_p_nick() {
		if( !isset( $this->p_nick ) ) {
			$sql = "SELECT c.pf_p_nick";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_profile_fields_data c";
			$sql = $sql . " WHERE c.user_id = " . $this->user_id . ";";
			
			if( $result = $this->db->query($sql) ) {
				if( $result->num_rows == 1 ) {
					$row = $result->fetch_object();
					$this->p_nick = $row->pf_p_nick;					
				} else {
					$this->p_nick = "";
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->p_nick;
	}
	
	/**
	 * Returns the phone number in +1234 format.
	 * 
	 * @return (string) phone_number
	 */
	public function get_phone() {
		if( !isset( $this->phone ) ) {			
			$sql = "SELECT c.pf_phone";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_profile_fields_data c";
			$sql = $sql . " WHERE c.user_id = " . $this->user_id . ";";
			
			if( $result = $this->db->query($sql) ) {
				if( $result->num_rows == 1 ) {
					$row = $result->fetch_object();
					$this->phone = $row->pf_phone;					
				} else {
					$this->phone = "";
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->phone;
	}
	
	/**
	 * Returns the coordinates of the planet played by the member.
	 *  
	 * @return (int) planet_coordinates
	 */
	public function get_planet_coords() {
		if( !isset( $this->planet_coords ) ) {
			$sql = "SELECT c.pf_planet_coords";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_profile_fields_data c";
			$sql = $sql . " WHERE c.user_id = " . $this->user_id . ";";
			
			if( $result = $this->db->query($sql) ) {
				if( $result->num_rows == 1 ) {
					$row = $result->fetch_object();
					$this->planet_coords = $row->pf_planet_coords;					
				} else {
					$this->planet_coords = "";
				}
			} else {
				$this->db->error;
				exit;
			}
		}
		return $this->planet_coords;
	}
	
	/**
	 * Set ETA bonus.
	 * 
	 * @param $tt_bonus
	 * @return unknown_type
	 */
	public function set_eta_bonus( $eta_bonus ) {
		$sql = "UPDATE " . PHPBB_PREFIX . "_profile_fields_data c";
		$sql = $sql . " SET c.pf_eta_bonus = " . (int) $this->db->real_escape_string( $eta_bonus );
		$sql = $sql . " WHERE c.user_id = " . (int) $this->db->real_escape_string( $this->user_id ) . ";";
		
		if( !$this->db->query($sql) ) {
			echo $this->db->error;
			exit;
		}
		
		$this->eta_bonus = $eta_bonus;
	}
	
	/**
	 * Get ETA bonus.
	 * 
	 * @return unknown_type
	 */
	public function get_eta_bonus() {
		if( !isset( $this->eta_bonus ) ) {
			$sql = "SELECT c.pf_eta_bonus";
			$sql = $sql . " FROM " . PHPBB_PREFIX . "_profile_fields_data c";
			$sql = $sql . " WHERE c.user_id = " . (int) $this->db->real_escape_string( $this->user_id ) . ";";
			
			if( $res = $this->db->query($sql) ) {
				if( $res->num_rows == 1 ) {
					$row = $res->fetch_object();
					$this->eta_bonus = $row->pf_eta_bonus;
				} else {
					$this->eta_bonus = 0;
				}
			} else {
				echo $this->db->error;
				exit;
			}
		}
		return $this->eta_bonus;
	}
}
?>
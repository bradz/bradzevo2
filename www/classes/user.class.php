<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/** 
 * User class. Used for authentication of users and fetching associated information.
 * 
 * TODO: Use the session key to automaticall log in if session expired, but cookie is not (cf. phpBB code).
 * 
 * @author morrow
 * @version Alpha
 * 
 */
class user extends session {
	// public information
	private $my_username;
	private $my_groups;
	
	private $db;
	
	/**
	 * Constructor. This sets up the session information and the db object.
	 * 
	 * @param $session_id - Session ID
	 * @param $session_user - UserID stored in the Session
	 * @param $session_key - Session Key
	 * @return unknown_type
	 */
	public function __construct( $session_id, $session_user, $session_key ) {
		global $db;
		$this->db=$db;
		
		// store session variables
		$this->session['id'] = $session_id;
		$this->session['user'] = $session_user;
		$this->session['key'] = $session_key;
	}
	
	/**
	 * This method will authenticate the present user. If the query returns one
	 * row, the session is still valid. We then set the username and access
	 * level and return the $user array.
	 * 
	 * If the query does not return a row, we return "false".
	 * 
	 * @return true if session is valid
	 * @return false if session is invalid
	 */
	public function auth_user() {
		// create a new member object and figure out what groups we're in
		$user = new member( $this->session['user'] );
		$this->groups = $user->get_groups_id();
		
		// query to check if there's a session_id <-> user_id combination in line with the cookie data
		$sql = "SELECT u.user_id, u.username";
		$sql = $sql . " FROM " . PHPBB_PREFIX . "_sessions s, " . PHPBB_PREFIX . "_users u ";
		$sql = $sql . " WHERE s.session_id = '" . $this->session['id'] . "'";
		$sql = $sql . " AND u.user_id = '" . $this->session['user'] . "'";
		$sql = $sql . " AND u.user_id = s.session_user_id";
		
		/*
		$sql = "SELECT u.user_id, u.username, e.tools_access, c.pf_p_nick, c.pf_phone";
		$sql = $sql . " FROM " . PHPBB_PREFIX . "sessions s, " . PHPBB_PREFIX . "users u, " . EVO_PREFIX . "users e, " . PHPBB_PREFIX . "profile_fields_data c";
		$sql = $sql . "	WHERE s.session_id = '" . $this -> session['id'] . "'";
		$sql = $sql . "	AND u.user_id = " . (int) $this -> session['user'];
		$sql = $sql . " AND u.user_id = s.session_user_id";
		$sql = $sql . "	AND u.user_id = e.user_id";
		$sql = $sql . " AND c.user_id = u.user_id;";
		*/
		
		if( $result = $this->db->query($sql) ) {
			// we found exactly one row, so the session seems valid
			if( $result -> num_rows == 1 and in_array( TOOLS_GROUP, $this->groups ) ) {
				$row = $result->fetch_object();
				$this->my_username = $row->username;
				return true;
			// we found none or more than 1 row, so either the session is invalid or something is fishy 
			} else {
				return false;
			}
		} else {
			echo $this -> db -> error;
			exit;
		}
	}
	
	public function get_my_username() {
		return $this->my_username;
	}
	
	public function get_my_groups() {
		return $this->my_groups;
	}
	
	public function get_my_user_id() {
		return $this->session['user'];
	}
}
?>
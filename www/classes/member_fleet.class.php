<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Member fleet class. Contains methods to read and write fleet composition and missions of members. 
 * 
 * @author morrow
 * @version Alpha
 * 
 */
class member_fleet extends fleet {
	//private $fleet_id;			// fleet_id of member (0, 1, 2, 3)
	public $fleet_id;			// fleet_id of member (0, 1, 2, 3)
	private $user_id;			// user_id of member
	private $ships;				// array of ship objects 
	
	/**
	 * Constructor. Requires $fleet_id and $user_id of member to be passed.
	 * 
	 * @param $fleet_id (int) 0, 1, 2 or 3
	 * @param $user_id
	 * @return (nothing)
	 */
	public function __construct( $fleet_id, $user_id ) {
		global $db;
		$this->db = $db;
		$this->fleet_id = $fleet_id;
		$this->user_id = $user_id;
	}
	
	/**
	 * Returns array of objects where each object is a type of ship. Each of
	 * these objects has properties (accessed via set_XYZ())
	 * 
	 * - name
	 * - id
	 * - class
	 * - t1
	 * - t2
	 * - t3
	 * - type
	 * - race
	 * - cost (total)
	 * 
	 * @return unknown_type
	 */
	public function get_ships_in_fleet() {
		// check if we already fetched the data previously
		if( !isset( $this->ships ) ) {	
			// 1 - get all ship names
			$ships = $this->ship2column( $this->fleet_id );
			
			// 2 - construct sql
			$sql = " SELECT";
			foreach( $ships as $i => $ship ) {
				if( $i == count( $ships ) - 1 ) {
					$sql = $sql . " mf." . $ship;	
				} else {
					$sql = $sql . " mf." . $ship . ", ";
				}
			}
			$sql = $sql . " FROM " . EVO_PREFIX . "_member_fleets mf";
			$sql = $sql . " WHERE mf.user_id = '" . $this->user_id . "';";
			
			// 3 - get amount of these ships from db and set in object
			// 4 - append each ship object to the $this->ships array 
			if( $result = $this->db->query($sql) ) {
				// fetch an associative array instead of an object because we can index the array by
				// constructing the keyname from the list of ship types  
				$row = $result->fetch_assoc();
				foreach( $row as $i => $amount ) {
					$ship_name = substr( ucwords($i), 0, count($i) - 3 );		// e.g., "demeter_0" becomes "Demeter"
					$ship = new ship( $ship_name );								// create new ship object
					$ship->set_amount( $amount );								// set amount of this ship
					$this->ships[] = $ship;										// append to array of ships
				}
			} else {
				echo $this->db->error;
				exit;
			}
		}
		return $this->ships;
	}
	
	/**
	 * Returns a more complete list of ships in a fleet. Doesn't change the
	 * object wide variables.  
	 * 
	 * @param $truncate (true: remove all ships where $amount == 0 form array)
	 * @return unknown_type
	 */
	public function display_ships_in_fleet( $truncate = true ) {
		// get all ships
		$ships_new = $this->get_ships_in_fleet();
		
		/*** FROM HERE IS COPIED FROM member_fleet_total.class.php ***/
		/*** THIS IS QUITE DISGUSTING; FIGURE SOMETHING OUT :< ***/
		
		// compute total value
		$total_cost = 0;
		foreach( $ships_new as $ship ) {
			$total_cost += $ship->get_amount() * $ship->get_cost();
		}
		
		// cycle through ships array and set fractional value
		foreach( $ships_new as $ship ) {
			if( $total_cost == 0 ) {
				$ship->set_cost_fraction( 0 );	
			} else {
				$ship->set_cost_fraction( round( $ship->get_amount() * $ship->get_cost() / $total_cost, 2 ) );	
			}
		}
		
		// if $truncate = true; drop all ships with $amount = 0 from array
		if( $truncate ) {
			foreach( $ships_new as $i => $ship ) {
				if( $ship->get_amount() == 0 ) {
					unset($ships_new[$i]);
				}
			}
			$ships_new = array_values( $ships_new );
		}
		
		// return
		return $ships_new;
	}
	
	/**
	 * This sets the ships in the fleet. The passed array contains only ship
	 * objects for ships that have an amount > 0. This is then compared against
	 * the array of all ship objects which include zero-amount ships. 
	 * 
	 * @param $ships_in_fleet (array of objects)
	 * @return unknown_type
	 */
	public function set_ships_in_fleet( $ships_in_fleet_obj ) {
		// 1 - set up all ships
		$ships = new ships();
		$ships_name = $ships->get_all_ships_name();
	
		// 2 - convert from array of objects to associative array
		$ships_in_fleet_array = $this->obj2arr( $ships_in_fleet_obj );
		
		// 3 - cycle through all ships; set amount
		foreach( $ships_name as $ship_name ) {
			$ship = new ship( $ship_name );									// create new ship object
			if( isset( $ships_in_fleet_array[$ship_name] ) ) {
				$ship->set_amount( $ships_in_fleet_array[$ship_name] );		// set amount	
			} else {
				$ship->set_amount(0);										// set amount
			}
			$this->ships[] = $ship;											// append to array of ships
		}
	}
	
	/**
	 * Saves one member_fleet (0, 1, 2, 3) in the database.
	 * 
	 * 1 - check if a row for the user_id already exists
	 * 2 - if it does not; insert an empty dummy row first
	 * 3 - then do an update
	 * 
	 * @return unknown_type
	 */
	public function save_fleet() {
		// create sql to check if a row for a user already exists
		$sql = " SELECT mf.user_id";
		$sql = $sql . " FROM " . EVO_PREFIX . "_member_fleets mf";
		$sql = $sql . " WHERE user_id = '" . $this->user_id . "'";
		
		// fire them off to the db
		if( $res = $this->db->query($sql) ) {
			if( $res->num_rows == 1 ) {
				$fleet_row_exist = true;
			} else {
				$fleet_row_exist = false;
			}
		} else {
			echo $this->db->error;
			exit;
		}
		
		// if a particular user_id doesn't have an associated row yet, create a dummy one
		if( !$fleet_row_exist ) {
			$sql = "INSERT INTO " . EVO_PREFIX . "_member_fleets";
			$sql = $sql . " ( user_id )";
			$sql = $sql . " VALUES";
			$sql = $sql . " ( " . (int) $this->db->real_escape_string( $this->user_id ) . " );";
			if( !$this->db->query($sql) ) {
				echo $this->db->error;
				exit;
			}
		}
		
		// now do the update dance; get all ship/column first
		$ships_cols = $this->ship2column( $this->fleet_id );
		
		// convert the ships array of objects to associative array of ships & amounts 
		$ships_array = $this->obj2arr( $this->ships );
		 
		// now make the sql
		$sql = "UPDATE " . EVO_PREFIX . "_member_fleets mf";
		$sql = $sql . " SET";
		foreach( $ships_cols as $i => $ship_col ) {
			$ship_name = substr( ucwords($ship_col), 0, count($i) - 3 );	// get shipname to access amounts array
			if( $i == count( $ships_cols ) - 1 ) {
				$sql = $sql . " mf." . $ship_col . " = " . (int) $this->db->real_escape_string( $ships_array[$ship_name] );	
			} else {
				$sql = $sql . " mf." . $ship_col . " = " . (int) $this->db->real_escape_string( $ships_array[$ship_name] ) . ", ";
			}
		}
		$sql = $sql . " WHERE mf.user_id = " . (int) $this->db->real_escape_string( $this->user_id ) . ";";

		// hit it!
		if( !$this->db->query($sql) ) {
			$this->db->error;
			exit;
		}
	} 
	
	/**
	 * Grabs all ship names and reformats them to column name format. The 
	 * fleet_id parameter is passed such that the correct column can be
	 * constructed.
	 * 
	 * @param $fleet_id (0, 1, 2, 3) 
	 * @return unknown_type
	 */
	private function ship2column( $fleet_id ) {
		// 1 - get all ship names
		$ships = new ships();
		$ships_name = $ships->get_all_ships_name();
		
		// 2 - format properly and attach fleet_number 
		foreach( $ships_name as $ship_name ) {
			$ships_out[] = strtolower( $ship_name ) . "_" . $fleet_id;
		}
		
		return $ships_out;
	}
	
	/**
	 * Takes an array of ship objects and converts it to an associative array.
	 * 
	 * FROM: array[1] = ship_object
	 * TO: array['shipname'] = amount_of_ships
	 *  
	 * @param $objects
	 * @return unknown_type
	 */
	private function obj2arr( $objects ) {
		$array = array();
		foreach( $objects as $object ) {
			$array[$object->get_name()] = $object->get_amount();
		}
		return $array;
	}
}
?>
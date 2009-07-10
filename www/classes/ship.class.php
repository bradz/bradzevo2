<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

/**
 * Ship class. Contains information about a particular ship.
 * 
 * @author morrow
 * @version Alpha
 *
 */
class ship {
	private $id;
	private $name;
	private $class;
	private $type;
	private $t1;
	private $t2;
	private $t3;
	private $cost;
	private $cost_fraction;
	private $amount;
	private $race;
	
	private $db;

	/**
	 * Constructor.
	 * 
	 * TODO: get_ship_info call must not be mandatory (for example, if we
	 * 			only create a new ship objects so it can be made part of 
	 * 			fleet to be added to the database, we don't want to grab
	 * 			all info about the ship from the db 
	 * 
	 * @param $ship_id
	 * @return (nothing)
	 */
	public function __construct( $ship_name ) {
		global $db;
		$this->db = $db;
		
		// load valid shipnames
		$ships = new ships();
		$valid_ship_names = $ships->get_all_ships_name();
		
		// only proceed if the passed ship_name is an allowed shipname
		if( in_array( $ship_name, $valid_ship_names ) ) {
			$this->name = $ship_name;
			$this->get_ship_info( $this->name );
		}
	}
	
	/**
	 * Fetch all information regarding a ship from the database. The results are saved
	 * in the object instance and nothing is returned by this function.
	 *  
	 * @param $ship_name
	 * @return (nothing)
	 */
	private function get_ship_info( $ship_name ) {
		$sql = "SELECT s.id, s.race, s.class, s.t1, s.t2, s.t3, s.type, s.metal, s.crystal, s.eonium";
		$sql = $sql . " FROM " . EVO_PREFIX . "_ships s";
		$sql = $sql . " WHERE s.name = '" . $ship_name ."';";
		
		if( $result = $this->db->query($sql) ) {
			$row = $result->fetch_object();
			$this->id = $row->id;
			$this->class = $row->class;
			$this->race = $row->race;
			$this->t1 = $row->t1;
			$this->t2 = $row->t2;
			$this->t3 = $row->t3;
			$this->type = $row->type;
			$this->cost = $row->metal + $row->crystal + $row->eonium;	
		} else {
			echo $this->db->error;
			exit;
		}			
	}
	
	/**
	 * Sets the amount of ships.
	 * 
	 * @param $amount
	 * @return (nothing)
	 */
	public function set_amount( $amount ) {
		$this->amount = $amount;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_class() {
		return $this->class;
	}
	
	public function get_type() {
		return $this->type;
	}
	
	public function get_t1() {
		return $this->t1;
	}
	
	public function get_t2() {
		return $this->t2;
	}
	
	public function get_t3() {
		return $this->t3;
	}
	
	public function get_cost() {
		return $this->cost;
	}
	
	public function get_amount() {
		return $this->amount;
	}
	
	public function get_race() {
		return $this->race;
	}
	
	public function get_cost_fraction() {
		if( !isset( $this->cost_fraction ) ) {
			$this->set_cost_fraction(0);
		}
		return $this->cost_fraction;
	}
	
	public function set_cost_fraction( $cost_fraction ) {
		$this->cost_fraction = $cost_fraction;
	}
	
}
?>
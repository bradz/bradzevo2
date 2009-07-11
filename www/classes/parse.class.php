<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}


/**
 * Base class for parsing.
 *  
 * @author morrow
 *
 */
class parse {
	private $text;		// Content (big string)
	private $type;		// Type of parse (cf. parse_type class)
	private $tick;		// Tick of the parse
	private $coords;	// Coords of the parsing planet (TODO: Make this planet_id / member_id later)
	private $user_id;	// User ID of Parsing Users
	private $feedback;	// Feedback message			
	private $db;		// DB object (used by subclasses)
	
	/**
	 * Constructor. Load the text into the parse object, prepare the db
	 * connection for subclasses, and extract type, tick and coords from
	 * the pasted text.
	 * 
	 * @param $text
	 * @return unknown_type
	 */
	public function __construct( $text, $user_id ) {
		global $db;
		$this->db = $db;
		
		$this->user_id = $user_id;
		$this->text = $text; 
		$this->determine_type();
		$this->determine_tick();
		$this->determine_coords();
	}
	
	public function process_parse() {
		if( $this->type->get_type_name() == "missions" ) {
			$this->process_missions();
		}
	}
	
	public function get_feedback() {
		return $this->feedback;
	}
	
	/**
	 * Determine the type of parse. List of types and their regular expressions
	 * can be found in the parse_types class. 
	 * 
	 * @return unknown_type
	 */
	private function determine_type() {
		$parse_types = parse_types::$types;
		$parse_types_re = parse_types::$types_re;
		
		foreach( $parse_types_re as $i => $re ) {
			if( preg_match( $re, $this->text ) > 0 ) {
				$this->type = new parse_type( $parse_types[$i] );
			}
		}
		//var_dump( $this->type );
	}
	
	/**
	 * Determine the tick of the parse.
	 * 
	 * @return unknown_type
	 */
	private function determine_tick() {
		$re = '/Tick\s+(\d+)/';
		preg_match( $re, $this->text, $matches );
		//var_dump( $matches );
		$this->tick = $matches[1];
		//var_dump( $this->tick );
	}
	
	/**
	 * Determine the source coordinates of the parse.
	 * 
	 * @return unknown_type
	 */
	private function determine_coords() {
		$re = '/\((\d+):(\d+):(\d+)\)/';
		preg_match( $re, $this->text, $matches );
		//var_dump( $matches );
		$this->coords = $matches[1] . ":" . $matches[2] . ":" . $matches[3];
		//var_dump($this->coords);
	}
	
	/**
	 * Process a missions page parse.
	 * 
	 * @return unknown_type
	 */
	private function process_missions() {
		// 0 - regular expressions
		// a - ships
		$re_ships = "/(.*)";
		$re_ships = $re_ships . "\s+(Fighter|Corvette|Frigate|Destroyer|Cruiser|Battleship)"; 							// class
		$re_ships = $re_ships . "\s+(Fighter|Corvette|Frigate|Destroyer|Cruiser|Battleship|Asteroids|Structures|-)"; 	// t1
		$re_ships = $re_ships . "\s+(Fighter|Corvette|Frigate|Destroyer|Cruiser|Battleship|-)"; 						// t2
		$re_ships = $re_ships . "\s+(Fighter|Corvette|Frigate|Destroyer|Cruiser|Battleship|-)"; 						// t3
		$re_ships = $re_ships . "\s+(Normal|Emp|Cloak|Steal|Pod|Structure Killer)";										// type
		$re_ships = $re_ships . "\s+(\d+)/";
		
		// b - various tt regex's that can be applied
		$re_1 = "/Galaxy\s+ETA:\s+(\d+)\s+ticks,\s+Cluster\s+Defence\s+ETA:\s+(\d+)\s+ticks,\s+Universe\s+ETA:\s+12 ticks,\s+Alliance\s+ETA:\s+(\d+)\s+ticks/";
		$re_2 = "/Launching\s+in\s+tick\s+(\d+),\s+arrival\s+in\s+tick\s+(\d+)/";
		$re_3 = "/Return\s+ETA:\s+(\d+)/";
		$re_4 = "/ETA:\s+(\d+),\s+Return\s+ETA:\s+(\d+)/";
		
		// 1 - explode string into an array 
		$lines = explode( "\r\n", $this->text );
		
		// 2 - check each array element for "Fleet	Location	Target (eta)	Mission"
		// if this matches, remember array element where the match occured (throw into $blocks_begin array)
		$limits = array();
		foreach( $lines as $i => $line ) {
			$a = preg_match( '/Fleet\s+Location\s+Target\s+\(eta\)\s+Mission/', $line );
			if( $a ) {
				$limits[] = $i;
			}
		}
		
		// do all the following for every fleet block we find 
		for( $j = 0; $j < 4; $j++ ) {
			// 3 - cut array apart
			if( $j == 3 ) {
				$fleet_array = array_slice( $lines, $limits[$j] );	
			} else {
				$fleet_array = array_slice( $lines, $limits[$j], $limits[$j+1] - $limits[$j] );
			}
			
			// 4 - implode the blocks
			$fleet_string = implode( "\r\n", $fleet_array );
			
			// 5 - apply regular expressions for ships
			preg_match_all( $re_ships, $fleet_string, $matches );
			
			// DEBUGGING
			/****
			echo "<div style=\"text-align: left;\">";
			echo "<pre>";
			var_dump( $matches );
			echo "<hr />";
			echo "</pre>";
			echo "</div>";
			****/

			// 6 - apply regular expressions to determine return tick;
			// 		default value is the current_tick
			$return_tick = $this->tick;
			if( preg_match( $re_4, $fleet_string, $match ) ) {
				$eta = $match[1];
				$return_eta = $match[2];
				$return_tick= ( $eta + $return_eta ) + $eta + $this->tick;
			} elseif( preg_match( $re_3, $fleet_string, $match ) ) {
				$return_eta = $match[1];
				$return_tick = $return_eta + $this->tick;
			} elseif( preg_match( $re_2, $fleet_string, $match ) ) {
				$launch = $match[1];
				$arrival = $match[2];
				$return_tick = $arrival + ( $arrival - $launch );
			} elseif( preg_match( $re_1, $fleet_string, $match ) ) {
				$return_tick = $this->tick;
			}
			
			// 7 - process ships
			$ships_name = $matches[1];
			$ships_amount = $matches[7];
			
			// build an array of ( ['shipname'] => $number_of_ships )
			$ships = array();
			foreach( $ships_name as $k => $ship_name ) {
				$ship = new ship( trim ( $ship_name ) );		// new ship
				$ship->set_amount( $ships_amount[$k] );			// set amount of ships
				$ships[] = $ship;								// append to array
			}
			
			$mf = new member_fleet( $j, $this->user_id );
			$mf->set_ships_in_fleet( $ships );
			$mf->set_return_tick( $return_tick );
			$mf->save_fleet();
			
			unset($ships);
			unset($mf);
		}
		$this->feedback = "Parsed Missions for " . $this->coords . " at Tick " . $this->tick . ".";
	}
}
?>
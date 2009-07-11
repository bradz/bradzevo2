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
	 * @param $text (string)
	 * @param $user_id (int)
	 * @return (nothing)
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
	
	/**
	 * Umbrella method to determine which parsing method is called depending
	 * on the type of parsed text. 
	 * 
	 * @return (nothing)
	 */
	public function process_parse() {
		if( $this->type->get_type_name() == "missions" ) {
			$this->process_missions();
		}
	}
	
	/**
	 * Returns the feedback message. The message is set at the end of each
	 * parsing process.  
	 * 
	 * @return (string)
	 */
	public function get_feedback() {
		return $this->feedback;
	}
	
	/**
	 * Determine the type of parse. List of types and their regular expressions
	 * can be found in the parse_types class.
	 * 
	 * The type is set as object wide variable. 
	 * 
	 * @return (nothing)
	 */
	private function determine_type() {
		$parse_types = parse_types::$types;
		$parse_types_re = parse_types::$types_re;
		
		foreach( $parse_types_re as $i => $re ) {
			if( preg_match( $re, $this->text ) > 0 ) {
				$this->type = new parse_type( $parse_types[$i] );
			}
		}
	}
	
	/**
	 * Determine the tick of the parse.
	 * 
	 * It is stored as object wide variable.
	 * 
	 * @return (nothing)
	 */
	private function determine_tick() {
		$re = '/Tick\s+(\d+)/';
		preg_match( $re, $this->text, $matches );
		$this->tick = $matches[1];
	}
	
	/**
	 * Determine the source coordinates of the parse.
	 * 
	 * It is stored as object wide variable.
	 * 
	 * @return (string)
	 */
	private function determine_coords() {
		$re = '/\((\d+):(\d+):(\d+)\)/';
		preg_match( $re, $this->text, $matches );
		$this->coords = $matches[1] . ":" . $matches[2] . ":" . $matches[3];
	}
	
	/**
	 * Compute ETA Bonus.
	 * 
	 * Cycles through the ships array to find highest ETA ship. Then compares
	 * actual travel time with base travel time.
	 * 
	 * @param $ships (array of ship objects)
	 * @param $eta (int)
	 * @return $eta_bonus (int)
	 */
	private function compute_eta_bonus( $ships, $eta ) {
		$base_eta = 12;	// 12 - fico; 13 - frde; 14 - crbs
		foreach( $ships as $i => $ship ) {
			if( ( $ship->get_class() == 'FR' or $ship->get_class() == 'DE' ) and $base_eta != 14 ) {
				$base_eta = 13;
			} elseif( $ship->get_class() == 'CR' or $ship->get_class() == 'BS' ) {
				$base_eta = 14;
			}
		}
		$eta_bonus = $base_eta - $eta; 
		return $eta_bonus;
	}
	
	/**
	 * Process a missions page parse.
	 * 
	 * @return (nothing)
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
		$re_1 = "/Galaxy\s+ETA:\s+(\d+)\s+ticks,\s+Cluster\s+Defence\s+ETA:\s+(\d+)\s+ticks,\s+Universe\s+ETA:\s+(\d+)\s+ticks,\s+Alliance\s+ETA:\s+(\d+)\s+ticks/";
		$re_2 = "/Launching\s+in\s+tick\s+(\d+),\s+arrival\s+in\s+tick\s+(\d+)/";
		$re_3 = "/Return\s+ETA:\s+(\d+)/";
		$re_4 = "/ETA:\s+(\d+),\s+Return\s+ETA:\s+(\d+)/";
		
		// 1 - explode string into an array 
		$lines = explode( "\r\n", $this->text );
		
		// 2 - check each array element for "Fleet	Location	Target (eta)	Mission"
		// 		if this matches, remember array element where the match occured (throw into $blocks_begin array)
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

			// 6 - process ships
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
			
			// 7 - apply regular expressions to determine return tick;
			// 		default value is the current_tick
			$return_tick = $this->tick;
			if( preg_match( $re_4, $fleet_string, $match ) ) {
				$eta = $match[1];
				$return_eta = $match[2];
				$return_tick= ( $eta + $return_eta ) + $eta + $this->tick;
				$eta_bonus = $this->compute_eta_bonus( $ships, $eta + $return_eta );
			} elseif( preg_match( $re_3, $fleet_string, $match ) ) {
				$return_eta = $match[1];
				$return_tick = $return_eta + $this->tick;
			} elseif( preg_match( $re_2, $fleet_string, $match ) ) {
				$launch = $match[1];
				$arrival = $match[2];
				$return_tick = $arrival + ( $arrival - $launch );
				$eta_bonus = $this->compute_eta_bonus( $ships, $arrival - $launch );
			} elseif( preg_match( $re_1, $fleet_string, $match ) ) {
				$return_tick = $this->tick;
				$eta_bonus = $this->compute_eta_bonus( $ships, $match[3] );
			}
			
			// 8 - save return tick and fleets
			$mf->set_return_tick( $return_tick );
			$mf->save_fleet();
			
			// 9 - save eta bonus
			if( isset( $eta_bonus ) ) {
				$member = new member( $this->user_id );
				$member->set_eta_bonus( $eta_bonus );
			}
			
			unset($ships);
			unset($mf);
		}
		if( isset( $eta_bonus ) ) {
			$this->feedback = "Parsed Missions for " . $this->coords . " at Tick " . $this->tick . ".<br /><br />";
			$this->feedback = $this->feedback . "Detected ETA Bonus: " . $eta_bonus . ".";
		} else {
			$this->feedback = "Parsed Missions for " . $this->coords . " at Tick " . $this->tick . ".";
		}
	}
}
?>
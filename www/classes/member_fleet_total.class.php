<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}

class member_fleet_total {
	private $user_id;			// user_id of member
	private $ships;				// array of all ship objects
	
	private $db;
	
	public function __construct( $user_id ) {
		global $db;
		$this->db = $db;
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
	 * - total ship cost (amount * cost)
	 * - value fraction (total ship cost / total cost for all ships)
	 * 
	 * If $truncate == true, then ships objects of amount = 0 aren't returned.
	 * 
	 * @return unknown_type
	 */
	public function get_all_ships( $truncate = true ) {
		// instantiate fleet objects
		$f0 = new member_fleet( 0, $this->user_id );
		$f1 = new member_fleet( 1, $this->user_id );
		$f2 = new member_fleet( 2, $this->user_id );
		$f3 = new member_fleet( 3, $this->user_id );
		
		// get ships from each fleet
		$s0 = $f0->get_ships_in_fleet();
		$s1 = $f1->get_ships_in_fleet();
		$s2 = $f2->get_ships_in_fleet();
		$s3 = $f3->get_ships_in_fleet();
		
		// cycle through the entire array and add up
		foreach( $s0 as $i => $ship ) {
			// 1 - copy ship
			$ship_new = $ship;
			
			// 2 - add total amount from all fleets
			$ship->set_amount( $s0[$i]->get_amount() + $s1[$i]->get_amount() + $s2[$i]->get_amount() + $s3[$i]->get_amount() );
			
			// 3 - add to array
			$ships_new[] = $ship; 
		}
		
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
}
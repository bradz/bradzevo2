<?php
/**
 * Display member fleets.
 * 
 * Currently only shows the sum of all ships.
 * 
 * TODO: Split up by fleets; display current status / eta's for the fleets.
 *
 * @author morrow
 *
 */

// enable error reporting
error_reporting(E_ALL);

// preliminaries
define('IN_EVO', true);
include('common.php');

// page specific settings
$pagetitle = ' - Member Fleets';

// header
include('includes/header_html.inc.php');
include('includes/header.inc.php');

// open main table and include sidebar
echo "<table class=\"main\"><tr><td class=\"sidebar\">";
include('includes/sidebar.inc.php');
echo "</td><td class=\"content\">";

// main content title
echo "<div class=\"content_header\">MEMBER FLEETS <strong>&laquo;</strong></div>";

/*
 * MAIN CONTENT STARTS HERE ***************************************************
 */

echo "<div class=\"content_subheader\">SELECT MEMBER <strong>&laquo;</strong></div>";

$tools_group = new group( TOOLS_GROUP );
$tools_members = $tools_group -> get_members();

echo "<div class=\"content_item\" style=\"text-align: center;\">";
echo "<form action=\"\" method=\"get\">";
//echo "<input type=\"hidden\" name=\"do\" value=\"member_fleet\">";
echo "<select name=\"user_id\">";

foreach( $tools_members as $key => $user_id ) {
	$member = new member( $user_id );
	if( $user_id == $_GET['user_id'] ) {
		echo "<option value=\"" . $user_id . "\" selected>" . $member -> get_username() ."</option>";		
	} else {
		echo "<option value=\"" . $user_id . "\">" . $member -> get_username() ."</option>";	
	}
}

echo "</select> ";
/*
echo "<br /><br />";
echo "<input type=\"checkbox\" name=\"totals\" value=\"true\" checked disabled>Totals ";
echo "<input type=\"checkbox\" name=\"f0\" value=\"true\" checked disabled>Base Fleet ";
echo "<input type=\"checkbox\" name=\"f1\" value=\"true\" checked disabled>Fleet 1 ";
echo "<input type=\"checkbox\" name=\"f2\" value=\"true\" checked disabled>Fleet 2 ";
echo "<input type=\"checkbox\" name=\"f3\" value=\"true\" checked disabled>Fleet 3 ";
echo "<br /><br />";
*/
echo "<input type=\"submit\" />";
echo "</form>";
echo "</div>";

if( isset( $_GET['user_id'] ) ) {
	echo "<div class=\"content_subheader\">TOTAL FLEET <strong>&laquo;</strong></div>";
	
	// mft = member_fleet_total
	$mft = new member_fleet_total( $_GET['user_id'] );
	$sft = $mft->get_all_ships( true );
	
	echo "<div class=\"content_item\">";
	echo "<table>";
	echo "<tr class=\"title\">";
	echo "<td>Ship</td>";
	echo "<td>Class</td>";
	echo "<td>T1</td>";
	echo "<td>T2</td>";
	echo "<td>T3</td>";
	echo "<td>Type</td>";
	echo "<td>Amount</td>";
	echo "<td>Value</td>";
	echo "<td>Race</td>";
	echo "</tr>";
	
	// in case the user has no fleet (nothing parsed, curse the bitch)
	if( !count( $sft ) == 0 ) {
		// run through the ships object and show all fields
		foreach( $sft as $i => $ship ) {
		//for( $i = 0; $i < count( $total_fleet['name'] ); $i++ ){
			// alternating tr background and setting font color for each race 
			if( ( $i + 1 ) % 2  == 0 ) {
				echo "<tr class=\"even_" . strtolower( $ship->get_race() ) . "\">";
			} else {
				echo "<tr class=\"odd_" . strtolower( $ship->get_race() ) . "\">";
			}
			echo "<td>" . $ship->get_name() . "</td>";
			echo "<td>" . $ship->get_class() . "</td>";
			echo "<td>" . $ship->get_t1() . "</td>";
			echo "<td>" . $ship->get_t2() . "</td>";
			echo "<td>" . $ship->get_t3() . "</td>";		
			echo "<td>" . $ship->get_type() . "</td>";
			echo "<td>" . $ship->get_amount() . "</td>";
			echo "<td>" . 100 * $ship->get_cost_fraction() . "%</td>";
			echo "<td>" . $ship->get_race() . "</td>";
			echo "</tr>";
		}
	}
	echo "</table>";
	echo "</div>";
	
	for( $j = 0; $j < 4; $j++ ) {
		if( $j == 0 ) {
			echo "<div class=\"content_subheader\">BASE FLEET <strong>&laquo;</strong></div>";	
		} elseif( $j == 1 ) {
			echo "<div class=\"content_subheader\">FLEET 1 <strong>&laquo;</strong></div>";
		} elseif( $j == 2 ) {
			echo "<div class=\"content_subheader\">FLEET 2 <strong>&laquo;</strong></div>";
		} elseif( $j == 3 ) {
			echo "<div class=\"content_subheader\">FLEET 3 <strong>&laquo;</strong></div>";
		}
		
		
		// mf = member_fleet
		$mf = new member_fleet( $j, $_GET['user_id'] );
		$sf = $mf->get_ships_in_fleet( true, true );
		$return_tick = $mf->get_return_tick();
		
		echo "Return Tick: " . $return_tick . "<br />";
		
		echo "<div class=\"content_item\">";
		echo "<table>";
		echo "<tr class=\"title\">";
		echo "<td>Ship</td>";
		echo "<td>Class</td>";
		echo "<td>T1</td>";
		echo "<td>T2</td>";
		echo "<td>T3</td>";
		echo "<td>Type</td>";
		echo "<td>Amount</td>";
		echo "<td>Value</td>";
		echo "<td>Race</td>";
		echo "</tr>";
		
		// in case the user has no fleet (nothing parsed, curse the bitch)
		if( !count( $sf ) == 0 ) {
			// run through the ships object and show all fields
			foreach( $sf as $i => $ship ) {
			//for( $i = 0; $i < count( $total_fleet['name'] ); $i++ ){
				// alternating tr background and setting font color for each race 
				if( ( $i + 1 ) % 2  == 0 ) {
					echo "<tr class=\"even_" . strtolower( $ship->get_race() ) . "\">";
				} else {
					echo "<tr class=\"odd_" . strtolower( $ship->get_race() ) . "\">";
				}
				echo "<td>" . $ship->get_name() . "</td>";
				echo "<td>" . $ship->get_class() . "</td>";
				echo "<td>" . $ship->get_t1() . "</td>";
				echo "<td>" . $ship->get_t2() . "</td>";
				echo "<td>" . $ship->get_t3() . "</td>";		
				echo "<td>" . $ship->get_type() . "</td>";
				echo "<td>" . $ship->get_amount() . "</td>";
				echo "<td>" . 100 * $ship->get_cost_fraction() . "%</td>";
				echo "<td>" . $ship->get_race() . "</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		echo "</div>";
	}
}

/*
 * MAIN CONTENT ENDS HERE *****************************************************
 */

// close main table
echo "</td></tr></table>";

// footer
include('includes/footer.inc.php');
include('includes/footer_html.inc.php');
?>
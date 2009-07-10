<html>
<head>
</head>
<body>
<h1>Parsing Test</h1>
<?php
if( !isset( $_POST['parse'] ) ) {
	echo "<div class=\"box\">";
	echo "<form action=\"\" method=\"post\">";
	echo "<textarea name=\"parse\" rows=\"5\" cols=\"80\" />";
	echo "</textarea>";
	echo "<br /><br />";
	echo "<input type=\"submit\" />";
	echo "</form>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
	exit;
}

// 1 - explode string into an array 
$lines = explode( "\r\n", $_POST['parse'] );

// 2 - check each array element for "Fleet	Location	Target (eta)	Mission"
// if this matches, remember array element where the match occured (throw into $blocks_begin array)
$hits = 0;
$limits = array();
foreach( $lines as $i => $line ) {
	$a = preg_match( '/Fleet\s+Location\s+Target\s+\(eta\)\s+Mission/', $line );
	if( $a ) {
		$hits++;
		$limits[] = $i;
		echo $i . ") " . $line . " <br />";
		echo $a . " <br />";
		echo "<br />";
	}	
}
echo "Hits: " . $hits;
echo "<br /><br />";
echo "Limits: ";
var_dump($limits);	

// 3 - cut array apart
$f0_array = array_slice( $lines, $limits[0], $limits[1] - $limits[0] );
$f1_array = array_slice( $lines, $limits[1], $limits[2] - $limits[1] );
$f2_array = array_slice( $lines, $limits[2], $limits[3] - $limits[2] );
$f3_array = array_slice( $lines, $limits[3] ); 

// 4 - implode the blocks
$f0_string = implode( "\r\n", $f0_array );
$f1_string = implode( "\r\n", $f1_array );
$f2_string = implode( "\r\n", $f2_array );
$f3_string = implode( "\r\n", $f3_array );

echo "<pre>";
echo $f0_string;
echo "</pre>";
echo "<br /><br />";

echo "<pre>";
echo $f1_string;
echo "</pre>";
echo "<br /><br />";

echo "<pre>";
echo $f2_string;
echo "</pre>";
echo "<br /><br />";

echo "<pre>";
echo $f3_string;
echo "</pre>";
echo "<br /><br />";

echo "<hr /><br /><br />";

// 5 - apply coordinate, ship, mission, eta and traveltime (for unmoving fleets) detection regex'es

// process f0
// long and useless ships regex 
$re_ships = "/(.*)";
$re_ships = $re_ships . "\s+(Fighter|Covetter|Frigate|Destroyer|Cruiser|Battleship)"; // class
$re_ships = $re_ships . "\s+(Fighter|Covetter|Frigate|Destroyer|Cruiser|Battleship|Asteroids|Structures|-)"; // t1
$re_ships = $re_ships . "\s+(Fighter|Covetter|Frigate|Destroyer|Cruiser|Battleship|-)"; // t2
$re_ships = $re_ships . "\s+(Fighter|Covetter|Frigate|Destroyer|Cruiser|Battleship|-)"; // t3
$re_ships = $re_ships . "\s+(Normal|Emp|Cloak|Steal|Pod|Structure Killer)";
$re_ships = $re_ships . "\s+(\d+)/"; 

preg_match_all( $re_ships, $f0_string, $ships_f0 );

echo "<pre>";
var_dump($ships_f0);
echo "</pre>";

echo "<hr />";

/**********************/
 
echo "<hr />";
echo "<pre>";
//var_dump($lines);
echo "</pre>";


/*
$re = "/(.*)";
$re = $re . "\s+(Fighter|Corvette|Frigate|Destroyer|Cruiser|Battleship)";
$re = $re . "\s+(Fi|Co|Fr|De|Cr|Bs|St|Ro|-)";
$re = $re . "\s+(Fi|Co|Fr|De|Cr|Bs|St|Ro|-)";
$re = $re . "\s+(Fi|Co|Fr|De|Cr|Bs|St|Ro|-)";
$re = $re . "\s+(Norm|Cloak|Emp|Pod|Struc|Steal)";
$re = $re . "\s+(\d+)";			// init
$re = $re . "\s+(\d+)";			// gun
$re = $re . "\s+(\d+)";			// arm
$re = $re . "\s+(\d+|-)";		// dmg
$re = $re . "\s+(\d+)";			// er
$re = $re . "\s+(\d+)";			// m
$re = $re . "\s+(\d+)";			// c
$re = $re . "\s+(\d+)";			// e
$re = $re . "\s+(\d+)";			// ac
$re = $re . "\s+(\d+)";			// dc
$re = $re . "\s+(Ter|Cath|Xan|Zik|Etd)/";

preg_match_all( $re, $_POST['parse'], $ships );
echo "<pre>";
var_dump($ships);
echo "</pre>";
*/
?>

</body>
</html>
<html>
<head>
</head>
<body>
<h1>Stats SQL Generator - Evolution Planetarion Tools</h1>
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

// var_dump($_POST);

$re = "/(.*)";
//$re = "/";
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
//var_dump($ships);
echo "</pre>";

/*
 * SQL TO TRUNCATE; DROP OLD TABLES
 */

$sql = "TRUNCATE TABLE evo_ships;";
echo $sql;
echo "<hr />";

$sql = "DROP TABLE evo_member_fleets;";
echo $sql;
echo "<hr />";

/*
 * SQL TO ADD SHIPS TO DATABASE
 */
$sql = "INSERT INTO evo_ships";
$sql = $sql . " ( ";
$sql = $sql . " name, ";
$sql = $sql . " class, ";
$sql = $sql . " t1, ";
$sql = $sql . " t2, ";
$sql = $sql . " t3, ";
$sql = $sql . " type, ";
$sql = $sql . " metal, ";
$sql = $sql . " crystal, ";
$sql = $sql . " eonium, ";
$sql = $sql . " race ";
$sql = $sql . " ) ";
$sql = $sql . " VALUES";

for( $i = 0; $i < count( $ships[0] ); $i++ ) {
	$name = $ships[1][$i];
	
	$class = $ships[2][$i];
	if( $class == 'Fighter' ) {
		$class = 'FI';
	} elseif( $class == 'Corvette' ) {
		$class = 'CO';
	} elseif( $class == 'Frigate' ) {
		$class = 'FR';
	} elseif( $class == 'Destroyer' ) {
		$class = 'DE';
	} elseif( $class == 'Cruiser' ) {
		$class = 'CR';
	} elseif( $class == 'Battleship' ) {
		$class = 'BS';
	}
	
	$t1 = $ships[3][$i];
	if( $t1 == "-" ) {
		$t1 = "";		
	}
	
	$t2 = $ships[4][$i];
	if( $t2 == "-" ) {
		$t2 = "";		
	}
	
	$t3 = $ships[5][$i];
	if( $t3 == "-" ) {
		$t3 = "";		
	}
	
	$race = $ships[17][$i];
	if( $race == "Cath" ) {
		$race = "Cat";		
	}
	
	$sql = $sql . " ( ";
	$sql = $sql . " '" . $name . "', ";
	$sql = $sql . " '" . $class . "', ";
	$sql = $sql . " '" . strtoupper($t1) . "', ";
	$sql = $sql . " '" . strtoupper($t2) . "', ";
	$sql = $sql . " '" . strtoupper($t3) . "', ";
	$sql = $sql . " '" . $ships[6][$i] . "', ";
	$sql = $sql . " " . $ships[12][$i] . ", ";
	$sql = $sql . " " . $ships[13][$i] . ", ";
	$sql = $sql . " " . $ships[14][$i] . ", ";
	$sql = $sql . " '" . $race . "'";
	
	if( $i == count ( $ships[0] ) - 1 ) {
		$sql = $sql . " ); ";
	} else {
		$sql = $sql . " ), ";
	}
}

echo $sql;

echo "<hr />";

/*
 * SQL TO MODIFY THE MEMBER FLEETS TABLE WITH THE CORRESPONDING COLUMNS
 * 
 * Note: Don't forget to drop it first!
 */

$sql = "CREATE TABLE evo_member_fleets";
$sql = $sql . " ( ";
$sql = $sql . " user_id MEDIUMINT UNSIGNED PRIMARY KEY,";

// for each fleet
for( $i = 0; $i < 4; $i++ ) {
	// go through the entire ships list
	for( $j = 0; $j < count( $ships[0] ); $j++ ) {
		$name = $ships[1][$j];
		$sql = $sql . " " . strtolower($name) . "_" . $i . " INT UNSIGNED NOT NULL DEFAULT 0, ";
	}
	if( $i > 0 ) {
		$sql = $sql . " return_tick_" . $i . " SMALLINT UNSIGNED NOT NULL,";	
	}
}
$sql = $sql . " updated_tick SMALLINT UNSIGNED NOT NULL";
$sql = $sql . " ); ";

echo $sql;

?>
</body>
</html>
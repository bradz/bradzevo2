<?php
/**
 * @ignore
 */
if( !defined('IN_EVO') ) {
	exit;
}
?>
<div class="sidebar_header">
 	<strong>&raquo;</strong>
	GENERAL
</div>
<div class="sidebar_item">
 	<strong>&raquo;</strong> <a href="dashboard.php">Dashboard</a><br />
 	<strong>&raquo;</strong> <a href="<?php echo PHPBB_RELATIVE_PATH; ?>">Forums</a><br />
 	<strong>&raquo;</strong> <a href="parser.php">Parser</a>
</div>
<div class="sidebar_header">
 	<strong>&raquo;</strong>
	MISSIONS
</div>
<div class="sidebar_item">
 	<strong>&raquo;</strong> <a href="attack.php">Attack</a><br />
 	<strong>&raquo;</strong> <a href="defence.php">Defence</a><br />
 	<strong>&raquo;</strong> <a href="retal.php">Retaliation</a>
</div>
<div class="sidebar_header">
 	<strong>&raquo;</strong>
	ALLIANCE
</div>
<div class="sidebar_item">
	<strong>&raquo;</strong> <a href="member_fleets.php">Member Fleets</a><br /> 
 	<strong>&raquo;</strong> <a href="member_list.php">Member List</a><br />
 	<strong>&raquo;</strong> <a href="search_defence.php">Search Defence</a>
</div>
<div class="sidebar_header">
	<strong>&raquo;</strong>
	INTEL
</div>
<div class="sidebar_item">
 	<strong>&raquo;</strong> <a href="fleet_tracker.php">Fleet Tracker</a>
</div>
<div class="sidebar_header">
	<strong>&raquo;</strong>
	ADMINISTRATION
</div>
<div class="sidebar_item">
 	<strong>&raquo;</strong> <a href="api_keys.php">API Keys</a>
</div>
<div class="sidebar_header">
	<strong>&raquo;</strong>
	INFORMATION
</div>
<div class="sidebar_item">
 	<strong>&raquo;</strong> User: <?php echo $my_username;?><br />
 	<strong>&raquo;</strong> Tick: <?php echo $current_tick_local; ?><br />
 	<strong>&raquo;</strong> ETA Bonus: <?php echo $my_eta_bonus; ?>
</div>
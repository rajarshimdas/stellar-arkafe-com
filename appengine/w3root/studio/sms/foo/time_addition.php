<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
*/

function timeadd($total_h, $total_m){	
	
	// Convert minutes to hours and minutes
	$hour_x = ($total_m / 60);
	$hour_x = explode(".",$hour_x);
	$hour_x = $hour_x[0];
	
	// Add the hours to the total hours
	$total_h = $total_h + $hour_x;
	
	// Subtract hours from minutes
	$total_m = ($total_m - ($hour_x * 60));
	
	// Time in HH:MM format
	$timeX = $total_h.':'.$total_m;
	
	/* Formatting for 0:0 hours */
	if ($timeX === "0:0") $timeX = "&nbsp;";
	
	return $timeX;
	
}

?>
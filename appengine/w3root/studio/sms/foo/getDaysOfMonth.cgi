<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
*/

function getDaysOfMonth($year, $month, $lastDayOfMonth){
	
	/* 
	$year 	- Year No.
	$month 	- 0 to 12 Month No.
	*/		
	
	for ($i = 0;$i<$lastDayOfMonth;$i++){		
	
		$day 		= $lastDayOfMonth - $i;		
		$today 		= date('j-M-y', mktime(0, 0, 0, $month  , $day, $year));
		$mysql_dt	= date('Y-m-d', mktime(0, 0, 0, $month  , $day, $year));		
		$dayofweek 	= date('D', mktime(0, 0, 0, $month  , $day, $year));
		
		//echo "x: $today<br>";
		
		$days[] = array("dt" => $today, "mysql_dt" => $mysql_dt, "dayofweek" => $dayofweek);
		
	} 
   
	return($days);

}

?>
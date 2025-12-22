<?php

function getDaysOfWeek($year, $weeknr){
	
	/* 
	$year 		- Year No.
	$weeknr 	- 0 to 53 week no
	*/
 
    $offset = date('w', mktime(0,0,0,1,1,$year));
    $offset = ($offset < 5) ? 1-$offset : 8-$offset;
    
    $monday 	= mktime(0,0,0,1,1+$offset,$year);
    $tuesday 	= mktime(0,0,0,1,2+$offset,$year);
    $wednesday 	= mktime(0,0,0,1,3+$offset,$year);
    $thursday 	= mktime(0,0,0,1,4+$offset,$year);
    $friday 	= mktime(0,0,0,1,5+$offset,$year);
    $saturday 	= mktime(0,0,0,1,6+$offset,$year);
    $sunday 	= mktime(0,0,0,1,7+$offset,$year);
 
    $days['monday'] 	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $monday));
    $days['tuesday'] 	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $tuesday));
    $days['wednesday']	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $wednesday));
    $days['thursday']	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $thursday));
    $days['friday']	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $friday));
    $days['saturday']	= date('d-M-Y', strtotime('+' . ($weeknr - 1) .  'weeks', $saturday));
    $days['sunday'] 	= date('d-M-Y', strtotime('+' . ($weeknr - 1) . ' weeks', $sunday));
 
	return($days);

}

?>
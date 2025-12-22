<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-Nov-2007				|
| Updated On: 29-Feb-2008				|
+-------------------------------------------------------+
| Drawing List > View > Tabulates the GFC History	|
+-------------------------------------------------------+
*/

$blockno    = $_GET["bn"];
$dc         = $_GET["dc"];

if ($blockno === "-- Select --" || $dc === "-- Select --"){
	
	// Data Input error
	echo "Error: Select a Block and a Discipline...";
			
} else {
	
	// Data input ok - display the results
	include 'foo/t2GFCHistory.php';
	$lx = new GFCHistory($projectid);
	$lx->displayGFCHistory();
	
}

?>
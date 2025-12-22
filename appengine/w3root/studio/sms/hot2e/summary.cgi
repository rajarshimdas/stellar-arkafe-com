<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 23-Jan-07									|
| Updated On: 17-Apr-08									|
|				23-Jan-24								|
+-------------------------------------------------------+
| Drawing List Summary - Re-written from scratch		|
+-------------------------------------------------------+
| Dependencies: summary.php								|
+-------------------------------------------------------+
*/
?>
<style>
	.tabulation tr { height: 30px; }
	.tabulation tr td { text-align: center; }
	/* .tabulation tr td:nth-child(2) { text-align: left; } */
</style>
<?php

// For the Project summary default page
if (!$blocks) {
	$blocks = "All";	// Project Summary - Default behaiviour
	$blockX = "-";		// Not required, dummy value set
	$atag	= 1;		// unique condition when atag is required	
}

// class:: summary 
include 'hot2e/summary.php';
include 'hot2e/summary-a.php';
include 'foo/getStageArray.php';

// Tabulate the Summary
SummaryTabulate($blocks, $blockX, $projectid, $atag, $mysqli);

$mysqli->close();

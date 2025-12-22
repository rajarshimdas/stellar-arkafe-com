<?php /* Wizard - Step 1 action
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 14-Feb-2012     				            |
+-------------------------------------------------------+
| */ require 'hot3e/transmittal.php'; /*                |
+-------------------------------------------------------+
*/

// STEP 1 action
$tm     = new CreateTransmittal($projectid, $sessionid);
$tmid   = $tm->SaveHeaderInfo($sessionid);
// die("tmid: ".$tmid);

/*
+-------------------------------------------------------+
| Header						                        |
+-------------------------------------------------------+
*/
header("Location:project.cgi?a=t3xcreate-2&tmid=$tmid");

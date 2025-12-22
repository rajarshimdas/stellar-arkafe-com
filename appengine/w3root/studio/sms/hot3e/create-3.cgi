<?php /*  Create transmittals - Wizard Page 3
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 10-Oct-2012				                |
+-------------------------------------------------------+
| Transmittal Preview Page				                |
+-------------------------------------------------------+	
*/ include('hot3e/transmittal.php'); /*                 |
+-------------------------------------------------------+
*/

$tmid = $_GET['tmid'];
		
$tmX = new ShowTransmittal($projectid, $sessionid, $roleid);

// Display the Commit transmittal form
$tmX->CommitTransmittalForm($tmid);

// Tabulate the transmittal
$tmX->TabulateTransmittal($tmid);

/*
+-------------------------------------------------------+
| Display the Transmittal				                |
+-------------------------------------------------------+
*/
$date = date("d-M-Y");
$imagepath = 'foo/images';
include __DIR__.'/transbody.php';

?>
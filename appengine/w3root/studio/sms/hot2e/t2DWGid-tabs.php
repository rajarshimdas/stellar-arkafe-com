<?php
/*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 10-Dec-2007								|
| Updated On: 30-Jan-2008 (at Home till 4 am)			|
+-------------------------------------------------------+
| Drawing List Editing	- EditPanel (Right Panel)		|
+-------------------------------------------------------+
*/

$x 	= $_GET["x"];	// New tab selected by user
$sx = $_GET["sx"];	// User's sessionid
$id = $_GET["id"];	// Selected drawing dwglist_id
// echo "x:$x sx:$sx id:$id";

/* MySQL connection - select update */
include('../foo/arnav/config.php');
include('../foo/arnav/dblogin.cgi');
// echo "<br>MySQL: $mysqli->host_info";

/* Include class session_x */
include('../foo/session_x.php'); 

$px = new session_x($sx,$mysqli);
// echo "<br>sessionid: $px->sessionid<br>userpreference: $px->userpreference";

/* Update the active tab value in the sessi0ns table */
if ($px->UpdateTagValue('tab',$x,$mysqli)){
	
	// Registered new tab value sucessfully
	header("location:t2DWGid-edit.php?sx=$sx&id=$id");
	
} else {
	
	// Error while trying to register new tab value
	die("UpdateTagValue: Execution Error");
	
}


$mysqli->close();

?>
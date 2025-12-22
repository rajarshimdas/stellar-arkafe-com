<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-May-2009       			|
| Updated On: 						|
+-------------------------------------------------------+
| Timesheet :: Edit	action - delete                 |
+-------------------------------------------------------+
*/

$go 			= $_POST["go"];
$timesheet_id 	= $_POST["tsid"];
$date			= $_POST["dt"];

if ($go === "Yes") {

    $query = "update timesheet set active = 0 where id = $timesheet_id and approved = 0";
    include('foo/arnav/dblogin.cgi');
    if (!$mysqli->query($query)) printf("Error: %s\n", $mysqli->error);
    $mysqli->close();

}

header("Location:rajarshi.cgi?a=t7xedit-01&dt=$date");



?>
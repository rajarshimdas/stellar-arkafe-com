<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 11-Feb-11                                 |
| Updated On: 						|
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

/*
+-------------------------------------------------------+
| Update the sessioncache				|
+-------------------------------------------------------+
*/
$did        = $_GET["did"];    // department id
$dnm        = $_GET["dnm"];    // department name
$mysqli     = cn2();

$query = "update sessioncache set `value` = '$did:$dnm' where sessionid = '$sessionid' and `key` = 'timesheetDeptID'";

if (!$mysqli->query($query)) {
    printf("Error: %s\n", $mysqli->error);
    die;
}

$mysqli->close();
/*
+-------------------------------------------------------+
| Redirect to timesheet     				|
+-------------------------------------------------------+
*/
header("Location:rajarshi.cgi");

?>
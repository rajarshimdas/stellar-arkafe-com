<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 03-Jul-2012       			|
| Updated On:                                           |
+-------------------------------------------------------+
*/
require 'foo/sms/projectSchedule.php';

$pid        = $project_id;
$stageId    = $_GET["stageId"];

if (deleteTargetDate($pid, $stageId, $mysqli) !== TRUE) {
    echo 1;
} else {
    echo 0;
}

?>
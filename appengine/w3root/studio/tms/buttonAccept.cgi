<?php /* Accept Button
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 06-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

$tsid = $_POST["tsid"];
// die("tsid:".$tsid);
/*
+-------------------------------------------------------+
| Get Timesheet Data        				            |
+-------------------------------------------------------+
*/
$mysqli = cn1();
require 'tm/functions.cgi';
$tX = id2data($tsid, $mysqli);

/*
+-------------------------------------------------------+
| Check if this member is the pm of the project for     |
| this timesheet entry.        				            |
+-------------------------------------------------------+
*/
$pid    = $tX["projectid"];
/*
if (checkRoleInProject($pid, $pm_roles_id, $user_id, $mysqli) !== true) {
    // This member is not the project manager or project coordinator
    rdReturnJsonHttpResponse('200',
    [
        'rx' => 'F',
        'm' => 'You are not authorized to approve timesheets'
    ]);
}
*/

/*
+-------------------------------------------------------+
| Update Database        				                |
+-------------------------------------------------------+
*/
$mysqli = cn2();
$query = "update 
            timesheet 
        set 
            approved = 1, 
            quality = 0,
            pm_review_flag = 1
        where 
            id = $tsid";

$rx = $mysqli->query($query) ? "T" : "F";

$mysqli->close();
/*
+-------------------------------------------------------+
| Send JSON Response                                    |
+-------------------------------------------------------+
*/
rdReturnJsonHttpResponse('200', ["rx" => $rx]);

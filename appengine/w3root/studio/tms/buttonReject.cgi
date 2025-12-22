<?php /* Reject Button
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 06-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once BD . '/Controller/Common.php';

$tsid = $_POST["tsid"];
$rx = "F";
$mx = "X";
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
$pid        = $tX["projectid"];
$this_uid   = $tX['user_id'];
$this_dt    = $tx['dt'];
/*
if (checkRoleInProject($pid, $pm_roles_id, $user_id, $mysqli) !== true) {
    // This member is not the project manager or project coordinator
    rdReturnJsonHttpResponse('200',['rx' => "F", 'mx' => "Access Denied"]);
    die;
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
            quality = 1,
            pm_review_flag = 1
        where 
            id = $tsid";

$rx = $mysqli->query($query) ? "T" : "F";

$query = "select
            dt,
            sum(no_of_hours) as h,
            sum(no_of_min) as m
        from
            timesheet
        where
            user_id = '$this_uid' and
            active > 0 and
            quality < 1 and
            dt = '$this_dt'
        group by
            dt";

if ($result = $mysqli->query($query)) {

    if ($row = $result->fetch_assoc()) {
        $mh = $row;
    }
    $result->close();
}
//$dayMH = bdAddHourMin($mh['h'], $mh['m']);
$dayMH = $mh['h'].'|'. $mh['m'];


$mysqli->close();
/*
+-------------------------------------------------------+
| Send JSON Response                                    |
+-------------------------------------------------------+
*/
rdReturnJsonHttpResponse('200', ["rx" => $rx, "mh" => $dayMH]);

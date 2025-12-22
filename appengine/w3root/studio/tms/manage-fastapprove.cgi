<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

$fastApproveflag = in_array(strtolower($loginname), $fastApproveRights) ? 1 : 0;
if ($fastApproveflag < 1) {
    die('You do not have fastApprove enabled.');
}

// var_dump($fastApproveRights);
$thisUId = empty($_GET['uid']) ? 0 : $_GET['uid'];

$query = "select * from view_users where reports_to_user_id = $userid and active > 0";
// echo $query;

$result = $mysqli->query($query);

/* fetch associative array */
while ($row = $result->fetch_assoc()) {
    $teamX[] = $row['user_id'];
}
//var_dump($teamX);

$flag = in_array($thisUId, $teamX) ? 1 : 0;
// echo "Flag: $flag";
if ($flag < 1) {
    die("The person does not report to you.");
}

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
            user_id = $thisUId and
            active > 0 and
            quality < 1";

$rx = $mysqli->query($query) ? "T" : "F";

$mysqli->close();
/*
+-------------------------------------------------------+
| Send JSON Response                                    |
+-------------------------------------------------------+
*/
header("Location:" . BASE_URL . "studio/tms/timesheets.cgi?a=team");

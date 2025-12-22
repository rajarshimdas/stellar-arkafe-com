<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 09-Feb-12					                |
| Updated On: 06-Jan-24                                 |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

$mysqli = cn1();

/*
+-------------------------------------------------------+
| Data Collection					                    |
+-------------------------------------------------------+
*/
$tsid       = $_POST["tsid"];
//$stageid  = $_POST["stageid"];
//$taskid   = $_POST["taskid"];
//$hour     = $_POST["hour"];
//$min      = $_POST["min"];
$remark     = addslashes($_POST["remark"]);


require 'tm/functions.cgi';

// Get the current data for the timesheet
$tX = id2data($tsid, $mysqli);
/*
+-------------------------------------------------------+
| Check if this member is the pm of the project for     |
| this timesheet entry.        				            |
+-------------------------------------------------------+

$pid    = $tX["projectid"];

if (checkRoleInProject($pid, $pm_roles_id, $user_id, $mysqli) !== true) {
    // This member is not the project manager or project coordinator
    return 1;
    die;
}
*/


// Required variables
$pm_uid             = $userid; // Logging who has edited this timesheet entry
$orig_departmentid  = $tX["workgroupid"];
$orig_stageid       = $tX["stageid"];
$orig_stage         = $tX["stage"];
$orig_taskid        = $tX["taskid"];
$orig_task          = $tX["task"];
$orig_no_of_hours   = $tX["hour"];
$orig_no_of_min     = $tX["min"];
$orig_work          = $tX["work"];

// Timestamp
$now = date('Y-m-d H:i:s');

$mysqli->close();

/*
+-------------------------------------------------------+
| Log                                                   |
+-------------------------------------------------------+
*/
$mysqli = cn2();
//$mysqli->autocommit(FALSE);
$errorFlag = 0;

/*
$query = "insert into
            timesheetlogs
                (timesheet_id, pm_uid, department_id, stage_id, task_id, no_of_hours, no_of_min, work, dtime)
            values
                ($tsid, $pm_uid, $orig_departmentid, $orig_stageid, $orig_taskid, $orig_no_of_hours, $orig_no_of_min, '$orig_work', '$now')";

// echo "<br>Q: ".$query;

if ($mysqli->query($query) !== TRUE) {
    $errorFlag = 1;
}
*/

/*
+-------------------------------------------------------+
| Update        					                    |
+-------------------------------------------------------+
*/
// Format Work Description
if (!$orig_work || $orig_work === "") {
    $orig_work = 'Work Description was empty...';
}

// Concat Remark and Original Timesheet Data
//$work = '*** Remark ***\n'.$remark.'\n\n*** Original Work Description ***\nStage : '.$orig_stage.'\nTask: '.$orig_task.'\nManhours: '.$orig_no_of_hours.':'.$orig_no_of_min.'\nWork: \n'.$orig_work.'\n*** End ***';
$work = '*** Remark ***\n' . $remark . '\n\n*** Original Work Description ***\nWork: \n' . $orig_work . '\n';

$query = "update
            timesheet
        set
            work = '$work',
            approved = 0,
            quality = 1,
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

<?php

/*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 18-Aug-2009       			            |
| Updated On: 27-Jan-2011                               |
+-------------------------------------------------------+
| Timesheet :: Timesheet Edit Action                  	|
+-------------------------------------------------------+
*/

/* Update Log
+-------------------------------------------------------+
| 27-Jan-11 Added the Leave Full/Half day switch.       |
+-------------------------------------------------------+
| 3-Nov-09  Work field can be left empty.		        |
+-------------------------------------------------------+
| 8-Sep-10  Used addslashes function to remove restric- |
|           tions on use of special characters          |
+-------------------------------------------------------+
*/

// Get variables
$tsid = $_POST["tsid"];
$user_id = $_POST["uid"];
$this_project_id = $_POST["pj"];
$date = $_POST["dt"];
$hours = $_POST["hr"];
$leaveFH = 1;                           // $_POST["lx"];                // Updated on 27-Jan-2011. Added Leave Half/Full day switch
$minutes = $_POST["mn"];
$task_id = 1;                           // $_POST["tk"];
$work = $_POST["wk"];
$go = $_POST["go"];
$display_no_of_days = $_POST["no"];     // Updated on 25-Aug-09.
$worked_from = $_POST["lk"];            // Updated on 10-Sep-09.
// $_POST["st"];                        // Updated on 18-Jun-2010. Removed.
// $projectstage_id = $_POST["ps"];
$work = addslashes($work);              // Work can have any character

// Percent completed
if (isset($_POST["percent"])) {
    $percent = $_POST["percent"];
} else {
    $percent = 0;
}

// Project Stage
if (isset($_POST['ps'])){
    $projectstage_id = $_POST["ps"];
} else {
    $projectstage_id = 1;
}

// Project Scope
if (isset($_POST['scope'])){
    $scope_id = $_POST["scope"];
} else {
    $scope_id = 1;
}

/*
+-------------------------------------------------------+
| Validate data                                         |
+-------------------------------------------------------+
*/
require_once "timesheet-validate.php";
validateTimesheetFormData(
    $uid,
    $holidayListFile,
    $date,
    $this_project_id,
    $hours,
    $minutes,
    $work,
    $percent
);

/*
+-------------------------------------------------------+
| Update Database                                       |
+-------------------------------------------------------+
*/
$mysqli = cn2();

$query = "update
            timesheet
        set
            worked_from = $worked_from,
            project_id = $this_project_id,
            dt = STR_TO_DATE('$date','%d-%b-%y'),
            no_of_hours = $hours,
            no_of_min = $minutes,
            projectstage_id = $projectstage_id,
            task_id = $task_id,
            work = '$work',
            pm_review_flag = 0,
            percent = $percent,
            projectscope_id = $scope_id
        where
            id = $tsid and
            user_id = $user_id and
            approved = 0";

// die("Q: $query<br>");

if (!$mysqli->query($query)) {
    printf("Error5: %s\n", $mysqli->error);
}

$mysqli->close();

/*
+-------------------------------------------------------+
| Redirection	                                     	|
+-------------------------------------------------------+
*/
if ($display_no_of_days)
    $params = "a=timesheet&no=$display_no_of_days";
else
    $params = "a=timesheet";

header("Location:timesheets.cgi?$params");


<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 21-May-2009       			            |
| Updated On: 23-Mar-2012				                |
|             23-Jan-24                                 |
+-------------------------------------------------------+
| Timesheet :: Add a new time data entry                |
+-------------------------------------------------------+
*/

/* Update Log
+-----------------------------------------------------+
| 08-May-13   Timesheet entry, edit, delete for 3     |
|             Working Days only                       |
+-----------------------------------------------------+
| 23-Mar-12 User Data Cache                           |
+-----------------------------------------------------+
| 27-Jan-11 Added Leave Half | Full day switch        |
+-----------------------------------------------------+
| 8-Sep-10  Used addslashes function to remove restric-|
|           tions on use of special characters        |
+-----------------------------------------------------+
*/
require_once $w3etc.'/foo/timesheets/timesheetCache.php';


/*
+-------------------------------------------------------+
| Form Data Collection                                  |
+-------------------------------------------------------+
*/
$this_project_id = $_POST["pj"];
$this_user_id = $_POST["uid"];
$date = trim($_POST["dt"]);
$leaveFH = 1;                           // $_POST["lx"]; // Leave Full | Half day
$hours = $_POST["hr"];
$minutes = $_POST["mn"];
$worked_from = $_POST["lk"];
// $task_id = $_POST["tk"];
$task_id = 1; // Static
$work = addslashes($_POST["wk"]);
$display_no_of_days = $_POST["no"];
$subtask = '-';                         // removed
// $dept_id = $_POST["dept_id"];
$dept_id = 3; // Static


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

$mysqli = cn2();
/*
+-------------------------------------------------------+
| Save Timesheet Form data in cache                  	|
+-------------------------------------------------------+
*/
if (saveTMFormData2Cache($user_id, $sessionid, $this_project_id, $date, $projectstage_id, '-', $mysqli) !== TRUE) {
    die('Error[Q1]: Failed to Save to Cache...');
}


/*
+-------------------------------------------------------+
| Store in Database                                     |
+-------------------------------------------------------+
*/ 
$query = "insert into `timesheet` 
            (`dt`,`user_id`,`project_id`,`task_id`,`no_of_hours`,`no_of_min`,`work`,`worked_from`,`subtask`,`projectstage_id`,`department_id`,`percent`,`projectscope_id`)
        values
            (STR_TO_DATE('$date','%d-%b-%y'),$this_user_id,$this_project_id,$task_id,$hours,$minutes,'$work',$worked_from,'$subtask',$projectstage_id,$dept_id,$percent,$scope_id)";

/* Testing */ 
// echo "Q1: $query";

if (!$mysqli->query($query)) {
    printf("Error[Q2]: %s\n", $mysqli->error);
    die();
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

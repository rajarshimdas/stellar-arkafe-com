<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 08-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
checkApiAccess($user_id, $mysqli);
// die('pid: '.$pid);

$pid            = $_POST["pid"] + 0;
$scope          = $_POST["scope"] + 0;
$stage          = $_POST["ps"] + 0;
$work           = trim($_POST["wk"]);
// $md          = $_POST["md"] + 0; Mandays
$hr             = trim($_POST['hr']) + 0;
$mn             = trim($_POST["mn"]) + 0;
$tp             = trim($_POST["tp"]) + 0;

// Validation flag

/* Validation */
if (is_int($pid) && $pid > 0) {
    $activePID = $pid;
} else {
    $_SESSION['bdMessageTxt'] = "Select Project and try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e1");
};

if (is_int($scope) && $scope > 1) {
    $_SESSION["addTaskScopeId"] = $scope;
} else {
    $_SESSION['bdMessageTxt'] = "Select Scope and try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e2");
}

if (is_int($stage) && $stage > 1) {
    $_SESSION["addTaskStageId"] = $stage;
} else {
    $_SESSION['bdMessageTxt'] = "Select Milestone and try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e3");
}

// Work 
if (strlen($work) < 1) {
    $_SESSION['bdMessageTxt'] = "Work description must have at least 1 character. Please try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e5");
} else {
    if (alpha_numeric_text($work) !== true) {
        $_SESSION['bdMessageTxt'] = "Restricted special character in Work. Please try again.";
        $_SESSION['bdMessageFlag'] = 2;
        bdGo2uri("concert/portal/tasks/add/e6");
    }
}

// Manhours
if (!is_int($hr)) {
    $_SESSION['bdMessageTxt'] = "Manhours should be an integer, try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e4");
}

// Manminutes
if (!is_int($mn) || $mn < 0 || $mn > 59) {
    $_SESSION['bdMessageTxt'] = "Manminutes should be an integer between 0 to 59, try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e5");
}

// Target %
if (!is_int($tp) || $tp < 0 || $tp > 100) {
    $_SESSION['bdMessageTxt'] = "Target % should be an integer between 0 to 59, try again.";
    $_SESSION['bdMessageFlag'] = 2;
    bdGo2uri("concert/portal/tasks/add/e6");
}

/* task
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| Field                    | Type                  | Null | Key | Default             | Extra          |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
| id                       | int(10) unsigned      | NO   | PRI | NULL                | auto_increment |
| project_id               | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| work                     | text                  | NO   |     | NULL                |                |
| remark                   | varchar(50)           | NO   |     | -                   |                |
| projectscope_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| projectstage_id          | tinyint(3) unsigned   | NO   |     | NULL                |                |
| displayorder             | tinyint(3) unsigned   | NO   |     | 10                  |                |
| status_last_month        | tinyint(4)            | NO   |     | 0                   |                |
| status_this_month_target | tinyint(4)            | NO   |     | 0                   |                |
| allocation_flag          | tinyint(4)            | NO   |     | 0                   |                |
| mandays                  | smallint(6)           | NO   |     | 0                   |                |
| manhours                 | smallint(5) unsigned  | NO   |     | 0                   |                |
| manminutes               | tinyint(3) unsigned   | NO   |     | 0                   |                |
| dt                       | datetime              | NO   |     | current_timestamp() |                |
| active                   | tinyint(4)            | NO   |     | 1                   |                |
| mcode                    | varchar(45)           | NO   |     | x                   |                |
| onhold                   | tinyint(4)            | NO   |     | 0                   |                |
| cm_date_flag             | date                  | NO   |     | 2000-01-01          |                |
| cm_allotted_mh           | int(10) unsigned      | NO   |     | 0                   |                |
| lm_allotted_mh           | int(10) unsigned      | NO   |     | 0                   |                |
+--------------------------+-----------------------+------+-----+---------------------+----------------+
*/

/* taskallotmhlog
+-------------+------------------+------+-----+---------+-------+
| Field       | Type             | Null | Key | Default | Extra |
+-------------+------------------+------+-----+---------+-------+
| task_id     | int(10) unsigned | NO   |     | NULL    |       |
| month       | date             | NO   |     | NULL    |       |
| allottedmin | int(11)          | NO   |     | NULL    |       |
+-------------+------------------+------+-----+---------+-------+
*/


# Save in db
$mysqli = cn2();
$rx = bdAddTask($pid, $work, $scope, $stage, $hr, $mn, $tp, $mysqli);

if ($rx[0] == 'T') {
    $_SESSION['bdMessageTxt']   = "Task added.";
    $_SESSION['bdMessageFlag']  = 1;
    bdGo2uri("concert/portal/tasks/add/s");
} else {
    $_SESSION['bdMessageTxt']   = $rx[1];
    $_SESSION['bdMessageFlag']  = 2;
    bdGo2uri("concert/portal/tasks/add/e6");
}

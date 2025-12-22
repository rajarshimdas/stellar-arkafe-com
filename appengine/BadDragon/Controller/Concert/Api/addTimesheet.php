<?php

$task_id    = (int)$_POST["task_id"];
$date       = $_POST["date"];
$h          = ($_POST["h"] != NULL) ? (int)$_POST["h"] : 0;
$m          = ($_POST["m"] != NULL) ? (int)$_POST["m"] : 0;
$pid        = (int)$_POST['project_id'];
$scope_id   = (int)$_POST['scope_id'];
$stage_id   = (int)$_POST["stage_id"];
$work       = addslashes($_POST["work"]);
$percent    = ($_POST["percent"] != NULL) ? (int)$_POST["percent"] : 0;

// For Leaves
if ($pid < 10) {
  $user_id = (int)$_POST['activeUID'];
  $uid = $user_id;
}
// rdReturnJsonHttpResponse('200',['F', "P: ".$percent]);

// Validation

# Integers
#
if (!(is_int($task_id) && is_int($scope_id) && is_int($stage_id) && is_int($percent) && is_int($h) && is_int($m) && is_int($pid))) {
  rdReturnJsonHttpResponse(
    '200',
    ['F', "Data type error"]
  );
}

if ($scope_id < 1 || $stage_id < 1 || $task_id < 1) {
  rdReturnJsonHttpResponse(
    '200',
    ['F', "Error. Please try again."]
  );
}

if ($pid > 10) {
  # Date
  #

  // Load class timesheetLockDt (Old module. Works)
  require_once $w3etc . '/foo/timesheets/timesheetLockDt.php';

  // Instantiate
  $ts = new timesheetLockDt($uid, $holidayListFile, cn2());

  // GetTimesheetLockDt
  $lockdt = $ts->getTimesheetLockDt();

  if ($date < $lockdt) {
    rdReturnJsonHttpResponse(
      '200',
      ["F", "Date is before Lockdate"],
    );
  }

  if ($date > date("Y-m-d")) {
    rdReturnJsonHttpResponse(
      '200',
      ["F", "Date entered is a future date"],
    );
  }


  # Range
  #
  if ($h < 0 || $h > 24) {
    rdReturnJsonHttpResponse(
      '200',
      ['F', 'Hour is out of range']
    );
  }

  if ($m < 0 || $m > 59) {
    rdReturnJsonHttpResponse(
      '200',
      ["F", 'Minutes is out of range']
    );
  }


  if ($h < 1 && $m < 1) {
    rdReturnJsonHttpResponse(
      '200',
      ["F", 'Both Hours and Minutes cannot be zero. Please try again.']
    );
  }

  if ($pid > 500 && ($percent < 1 || $percent > 100)) {
    rdReturnJsonHttpResponse(
      '200',
      ['F', 'Percent is out of range. Please enter a value between 1 to 100.']
    );
  }
}

// Total Manhour limit per day 
require_once BD . 'Controller/User/Api/getUserDayTimesheet.php';
$x = getUserDayTimesheetSum($uid, $date, $mysqli);
$total_min = ($x['total_min'] + (($h * 60) + $m));

if ($tsMaxMinutesPerDay < $total_min) {

  // $balance_min = $tsMaxMinutesPerDay - $x['total_min'];

  rdReturnJsonHttpResponse(
    '200',
    ['F', 'Total Manhours exceeds maximum limit of 10 hours.']
  );
}

// Check Holidays
require_once $w3root . '/studio/tms/hot7e/getHolidayList.php';
$isHoliday = 0;
$dow = date("D", strtotime($date));

if ($dow == 'Sun') {
  $isHoliday = 1;
  $m = "Selected date is Sunday";
} else {
  $hx = getHolidayList(0, $mysqli);
  if (isset($hx[$date])) {
    $isHoliday = 1;
    $m = 'Selected date is a Holiday - ' . $hx[$date][0];
  }
};

if ($isHoliday > 0) {
  rdReturnJsonHttpResponse(
    '200',
    ['F', $m]
  );
}

/*
timesheet;
+-----------------+-----------------------+------+-----+---------------------+----------------+
| Field           | Type                  | Null | Key | Default             | Extra          |
+-----------------+-----------------------+------+-----+---------------------+----------------+
| id              | bigint(20) unsigned   | NO   | PRI | NULL                | auto_increment |
| dt              | date                  | NO   | MUL | NULL                |                |
| user_id         | mediumint(8) unsigned | NO   |     | NULL                |                |
| project_id      | mediumint(8) unsigned | NO   |     | NULL                |                |
| projectstage_id | tinyint(3) unsigned   | NO   |     | 1                   |                |
| department_id   | tinyint(3) unsigned   | NO   |     | NULL                |                |
| task_id         | int(10) unsigned      | NO   |     | NULL                |                |
| subtask         | varchar(250)          | NO   |     | NULL                |                |
| no_of_hours     | tinyint(4)            | NO   |     | NULL                |                |
| no_of_min       | tinyint(4)            | NO   |     | NULL                |                |
| work            | text                  | NO   |     | NULL                |                |
| worked_from     | tinyint(3) unsigned   | NO   |     | 10                  |                |
| approved        | tinyint(1)            | NO   |     | 0                   |                |
| quality         | tinyint(1)            | NO   |     | 0                   |                |
| tmstamp         | timestamp             | NO   |     | current_timestamp() |                |
| percent         | tinyint(4)            | NO   |     | 0                   |                |
| pm_review_flag  | tinyint(4)            | NO   |     | 0                   |                |
| active          | tinyint(1)            | NO   |     | 1                   |                |
| projectscope_id | smallint(6)           | NO   |     | 1                   |                |
+-----------------+-----------------------+------+-----+---------------------+----------------+
*/
$query = "INSERT INTO `timesheet` 
            (`dt`, `user_id`, `project_id`, `projectscope_id`, `projectstage_id`, `task_id`, `no_of_hours`, `no_of_min`, `work`, `percent`) 
        VALUES 
            ('$date', '$user_id', '$pid', '$scope_id', '$stage_id', '$task_id', '$h', '$m', '$work','$percent')";

// Troubleshooting
// rdReturnJsonHttpResponse('200', ['F', $query]);

$mysqli = cn2();

if ($mysqli->query($query)) {

  $mysqli = cn1();

  $tx = bdTaskStats($task_id, 1, $user_id, $mysqli);
  $dayManhours = bdGetDayManhoursWithoutLeave($date, $user_id, $mysqli);
  $dayTimesheet = bdGetDayTimesheet($date, $user_id, $mysqli);

  $mysqli->close();

  rdReturnJsonHttpResponse('200', ['T', $tx["percent"] . '%', $tx["manhours"], $dayManhours, $dayTimesheet]);
} else {
  rdReturnJsonHttpResponse('200', ["F", "Server Error"]);
}

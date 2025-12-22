<?php

$date = $_POST['date'];

// Save date to session variable
$_SESSION["timesheetDate"] = $date;

$timesheetDateCal = date("d-M-y", strtotime($date));


// Data
$dayManhours = bdGetDayManhoursWithoutLeave($date, $user_id, $mysqli);
$dayTimesheet = bdGetDayTimesheet($date, $user_id, $mysqli);

rdReturnJsonHttpResponse(
    '200',
    ['T', $dayManhours, $dayTimesheet, $timesheetDateCal]
);
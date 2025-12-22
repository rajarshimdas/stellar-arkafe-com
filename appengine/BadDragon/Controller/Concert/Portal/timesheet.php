<?php
// Getter
$timesheetDate = isset($_SESSION["timesheetDate"]) ? $_SESSION["timesheetDate"] : date("Y-m-d");
// Setter
$_SESSION["timesheetDate"] = $timesheetDate;

// Date Human
$timesheetDateCal = date("d-M-y", strtotime($timesheetDate));

// Load class timesheetLockDt (Old module. Works)
require_once $w3etc . '/foo/timesheets/timesheetLockDt.php';
// Instantiate
$ts = new timesheetLockDt($uid, $holidayListFile, cn2());
// GetTimesheetLockDt
$lockdt = $ts->getTimesheetLockDt();

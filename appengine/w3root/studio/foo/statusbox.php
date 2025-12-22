<?php
require_once W3PATH . 'appengine/w3root/studio/foo/status45d.php';

# Get User data
$s = getUserData($uid, $dt_from, $mysqli);
$uX = $s[0];
$dt_from = $s[1];

# Get Timesheet data (includes leaves)
$tsX = getUserDayRangeTimesheetSum($uid, $dt_from, $dt_today, $mysqli);
//$co = isset($tsX) ? count($tsX) : 0;

// Empty array if no result
if (!isset($tsX)) $tsX = [];

// Boxes
$dayX = statusbox($tsX, $lockdt, $dt_today, $d, $m, $Y, $dayname, $dayTotalMin, $holiday, $dt_from);
// Circle
$x45d = status45d($uid, $tsX, $dt_from, $dt_upto, $dayTotalMin, $ts_lockdt, $holiday);

// Generate Widget
tsWidget($uid, $dayX, $x45d);
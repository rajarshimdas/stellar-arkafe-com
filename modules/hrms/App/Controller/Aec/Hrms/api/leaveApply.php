<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Leave type
// $leave_type_id = empty($_POST['ltype']) ? 0 : (int)$_POST['ltype'];
$leave_type_id = 2;

// Start and End date
$sdt = empty($_POST['sdt']) ? '2004-03-23' : $_POST['sdt'];
$edt = empty($_POST['edt']) ? $sdt : $_POST['edt'];

// Start and End half day
$sx = empty($_POST['sx']) ? 'X' : $_POST['sx'];
$ex = empty($_POST['ex']) ? 'X' : $_POST['ex'];

// Number of days
$nod = empty($_POST['nod']) ? 0 : $_POST['nod'];
$nod = $nod * 100; # Shift decimal to store as integer

// Reason
// $rx = $mysqli->real_escape_string(trim($_POST['rx']));
$rx = trim($_POST['rx']);

$today = date('Y-m-d');

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/
if ($leave_type_id < 1) {
    die(bdReturnJSON(['F', 'Select Leave Type', 'ltype']));
}

$thisYear = date('Y') . '-01-01';
/*
if ($sdt < $thisYear) {
    die(bdReturnJSON(['F', 'Start Date out of range. Select a date from current Calendar year.', 'sdt']));
}

if ($edt < $thisYear) {
    die(bdReturnJSON(['F', 'End Date out of range. Select a date from current Calendar year.', 'edt']));
}
*/
if ($sdt > $edt) {
    die(bdReturnJSON(['F', 'End Date is before Start Date. Please try again.', 'edt']));
}

if ($nod == 0) {
    die(bdReturnJSON(['F', 'Enter Number of Days', 'nod']));
}

if (empty($rx)) {
    die(bdReturnJSON(['F', 'Enter reason for leave', 'rx']));
}

/*
+-------------------------------------------------------+
| Holidays                                              |
+-------------------------------------------------------+
*/
# date range defined in Controller Hrms.php
// $dateRangeStart = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 60, date('Y')));
// $dateRangeEnd = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 90, date('Y')));


$holidays = getSundaysInRange($dateRangeStart, $dateRangeEnd);
$hX = bdGetHolidayListInRange($dateRangeStart, $dateRangeEnd, $mysqli);

foreach ($hX as $h) {
    $holidays[] = $h['dt'];
}


/*
+-------------------------------------------------------+
| Leave Attribute                                       |
+-------------------------------------------------------+
*/

/* rd_leave_attr;
+----+----------------------+--------------------------------+--------+
| id | attribute            | description                    | active |
+----+----------------------+--------------------------------+--------+
|  1 | NA                   | TBD                            |      1 |
|  9 | Short Leave          | slr                            |      0 |
| 10 | Sanctioned           | ok                             |      1 |
| 20 | Un-sanctioned        | does not meet criteria         |      1 |
| 24 | LWP - Reversible     | lwp                            |      0 |
| 25 | LWP - Non Reversible | lwp                            |      0 |
| 30 | Informed             | on day of leave before 10:30am |      1 |
| 40 | Un-informed          | un informed                    |      0 | Same as Un-sanctioned
+----+----------------------+--------------------------------+--------+
*/


## Validate Sanctioned or Un-sanctioned
##
$leave_attr_id = 1;  // Instantiate as NA

## Leave applied in advance
// die(bdReturnJSON(['F', "Today: $today | sdt: $sdt", 'rx']));

if ($today < $sdt) {

    ## Check advance application rules
    if ($nod < /* nod X 100 */ 51) {
        # Half Day
        // die(bdReturnJSON(['F', 'check1d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfWorkDays($sdt, 0, $holidays);
    } elseif ($nod < 151) {
        # 1 day (and 1.5 days)
        // die(bdReturnJSON(['F', 'check2d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfWorkDays($sdt, 1, $holidays);
    } elseif ($nod < 251) {
        # 2 days (and 2.5 days)
        // die(bdReturnJSON(['F', 'check5d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfWorkDays($sdt, 4, $holidays);
    } elseif ($nod < 451) {
        # 3 to 4 days (and 4.5 days)
        // die(bdReturnJSON(['F', 'check15d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfDays($sdt, 15);
    } elseif ($nod < 701) {
        # 5 to 7 days
        // die(bdReturnJSON(['F', 'check30d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfDays($sdt, 30);
    } else {
        # More than 7 days
        // die(bdReturnJSON(['F', 'check60d: ' . $nod, 'nod']));
        $leave_attr_id = checkNoOfDays($sdt, 60);
    }

    # Troubleshooting
    #
    // die(bdReturnJSON(['F', "Future Date :: leave_attr_id: $leave_attr_id", 'rx']));
}


/*
+-------------------------------------------------------+
| Leave Attribute  :: Informed                          |
+-------------------------------------------------------+
*/
if ($sdt <= $today) {

    # Leave Applied after leave taken
    #
    $leave_attr_id = 20;    // Un-sanctioned

    # Previous Workday | Updated: 13-Aug-25
    #
    $prevFlag = 0;
    $prevDt = getPreviousISODate($today); // Start with yesterday

    while ($prevFlag < 1) {
        if (!in_array($prevDt, $holidays)) {
            $prevWorkDt = $prevDt;
            $prevFlag++;
        }
        $prevDt = getPreviousISODate($prevDt);
    }

    // Previous workday | Informed Leave
    if ($edt >= $prevWorkDt /* && $edt <= $today */) {
        $leave_attr_id = 30;
    }

    # Troubleshooting
    #
    // die(bdReturnJSON(['F', "Past Date :: leave_attr_id: $leave_attr_id", 'rx']));

}

# Trap NA Bug
#
// $leave_attr_id = 1;
if ($leave_attr_id < 2) {
    die(bdReturnJSON(['F', 'System Error [NA] :: Please re-try.', 'rx']));
}
/*
+-------------------------------------------------------+
| Execute query                                         |
+-------------------------------------------------------+
*/
# Addslashes
#
$rx = mysqli_real_escape_string($mysqli, $rx);
// $log = mysqli_real_escape_string($mysqli, json_encode($_POST));

$mysqli = cn2();
$mysqli->begin_transaction();

# Leave Application Record
#
$y = (int)date('Y', strtotime($sdt));
$m = (int)date('m', strtotime($sdt));

$query = "INSERT INTO `rd_leave_records` 
            (`user_id`, `leave_type_id`, `leave_attr_id`, `from_dt`, `from_dt_units`, `end_dt`, `end_dt_units`, `nod_units`, `reason`, `status_id`, `year_generated`, `month_generated`, `emoji`) 
        VALUES 
            ('$uid', '$leave_type_id', '$leave_attr_id', '$sdt', '$sx', '$edt', '$ex', '$nod', '$rx', '5', '$y', '$m', '1')";
// die(bdReturnJSON(['F', $query, 'x']));

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[1] in saving leave.", 'x']));
}

$leaveId = $mysqli->insert_id;

# Log
#
/*
$log = json_encode([
    'actionByUId' => $uid,
    // 'query' => $query,
]);
*/
$log = 'ok';

$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveApply', '$log')";
// die(bdReturnJSON(['F', $query, 'x']));

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[2] in saving leave.", 'x']));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave applied."]);
} else {
    bdReturnJSON(["F", "System error[3] in saving leave.", 'x']);
}

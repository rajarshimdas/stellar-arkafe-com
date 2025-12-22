<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$thisUserId = $_POST['thisUserId'];
$leaveId = $_POST['leaveId'];

// Leave type
$leave_type_id = empty($_POST['dxleaveTypeId']) ? 0 : $_POST['dxleaveTypeId'];

// Start and End date
$sdt = empty($_POST['sdt']) ? '2004-03-23' : $_POST['sdt'];
$edt = empty($_POST['edt']) ? '2004-03-23' : $_POST['edt'];

// Start and End half day
$sx = empty($_POST['sx']) ? 'X' : $_POST['sx'];
$ex = empty($_POST['ex']) ? 'X' : $_POST['ex'];

// Number of days
$nod = empty($_POST['nod']) ? 0 : $_POST['nod'];
$nod = $nod * 100; # Shift decimal to store as integer

// Reason
// $rx = $mysqli->real_escape_string(trim($_POST['rx']));
$rx = "Leave record by HR";


/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/
if ($leave_type_id < 1) {
    die(bdReturnJSON(['F', 'Select Leave Type', 'dxleaveTypeId']));
}

$thisYear = date('Y') . '-01-01';
if ($sdt < $thisYear) {
    die(bdReturnJSON(['F', 'Start Date out of range. Select a date from current Calendar year.', 'sdt']));
}

if ($edt < $thisYear) {
    die(bdReturnJSON(['F', 'End Date out of range. Select a date from current Calendar year.', 'edt']));
}

if ($sdt > $edt) {
    die(bdReturnJSON(['F', 'End Date is before Start Date. Please try again.', 'edt']));
}

if ($nod == 0) {
    die(bdReturnJSON(['F', 'Enter Number of Days', 'dxNod']));
}

if (empty($rx)) {
    die(bdReturnJSON(['F', 'Enter reason for leave', 'rx']));
}

/*
+-------------------------------------------------------+
| Execute query                                         |
+-------------------------------------------------------+
*/
$leaveRec = bdGetUserLeaveRecById($leaveId, $mysqli);
$dt_last_updated = $leaveRec['dt_last_updated'];

$mysqli = cn2();
$mysqli->begin_transaction();


# Leave Application Record
#
$y = (int)date('Y', strtotime($sdt));
$m = (int)date('m', strtotime($sdt));

# Note: For LWP leave_type_id and leave_attr_id are same
$query = "INSERT INTO `rd_leave_records` 
            (`dt_last_updated`, `user_id`, `leave_type_id`, `leave_attr_id`, `from_dt`, `from_dt_units`, `end_dt`, `end_dt_units`, `nod_units`, `reason`, `status_id`, `year_generated`, `month_generated`) 
        VALUES 
            ('$dt_last_updated', '$thisUserId', '$leave_type_id', '$leave_type_id', '$sdt', '$sx', '$edt', '$ex', '$nod', '$rx', '6', '$y', '$m')";
// die(bdReturnJSON(['F', $query, 'x']));

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[1] in saving special leave.", 'x']));
}

$leaveId = $mysqli->insert_id;

/*
# Update Leave Entitelments - None
#
*/


# Log
#
$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$thisUserId', 'leaveSpecialAdd | HR', 'ok')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[4] in saving special leave.", 'x']));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave applied."]);
} else {
    bdReturnJSON(["F", "System error[5] in saving special leave.", 'x']);
}

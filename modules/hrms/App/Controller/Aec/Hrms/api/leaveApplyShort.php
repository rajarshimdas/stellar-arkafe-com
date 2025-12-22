<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Leave type
$leave_type_id = 9;
$leave_attr_id = 9;

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
$rx = $mysqli->real_escape_string(trim($_POST['rx']));


/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/
if (empty($rx)) {
    die(bdReturnJSON(['F', 'Enter reason for leave', 'rx']));
}


/*
+-------------------------------------------------------+
| Execute query                                         |
+-------------------------------------------------------+
*/

$mysqli = cn2();
$mysqli->begin_transaction();


# Leave Application Record
#
$y = (int)date('Y', strtotime($sdt));
$m = (int)date('m', strtotime($sdt));

$query = "INSERT INTO `rd_leave_records` 
            (`user_id`, `leave_type_id`, `leave_attr_id`, `from_dt`, `from_dt_units`, `end_dt`, `end_dt_units`, `nod_units`, `reason`, `status_id`, `year_generated`, `month_generated`) 
        VALUES 
            ('$uid', '$leave_type_id', '$leave_attr_id', '$sdt', '$sx', '$edt', '$ex', '$nod', '$rx', '5', '$y', '$m')";
// die(bdReturnJSON(['F', $query, 'x']));

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[1] in saving leave.", 'x']));
}

$leaveId = $mysqli->insert_id;


# Log
#
$log = json_encode([
    'actionByUId' => $uid,
]);

$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveApply', '$log')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[4] in saving leave.", 'x']));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave applied."]);
} else {
    bdReturnJSON(["F", "System error[5] in saving leave.", 'x']);
}

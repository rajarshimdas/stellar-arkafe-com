<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   26-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/


$leaveId = $_POST['leaveId'];

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/

// Get details of this leave application
$query = "SELECT * FROM rd_view_leaves where `id` = '$leaveId'";

$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
    $leaveRec = $row;
}

$thisUId        = $leaveRec['user_id'];
$leave_type_id  = $leaveRec['leave_type_id'];
$nod            = $leaveRec['nod_units'] * 100;

/*
+-------------------------------------------------------+
| Execute query                                         |
+-------------------------------------------------------+
*/

$mysqli = cn2();
$mysqli->begin_transaction();



// Set status as reject
$query = "UPDATE 
            `rd_leave_records` 
        SET 
            `status_id` = '11',
            `dt_last_updated` = CURRENT_TIMESTAMP(),
            `emoji` = '3'
        WHERE 
            `id` = '$leaveId'";

if (!$mysqli->query($query)){
    die(bdReturnJSON(["F", "System error[1] in leave Rejection."]));
}

# Log
#
$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveReject', 'ok')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[2] in leave Rejection."]));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave rejected."]);
} else {
    bdReturnJSON(["F", "System error[3] in leave Rejection."]);
}


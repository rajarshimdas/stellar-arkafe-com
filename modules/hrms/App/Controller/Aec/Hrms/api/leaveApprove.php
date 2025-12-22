<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   26-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$leaveId    = $_POST['leaveId'];
$attrId     = $_POST['attrId'];
$noOfDays   = $_POST['noOfDays'] * 100;     // Convert to integer
$log        = $_POST['log'];

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/



/*
+-------------------------------------------------------+
| Leave details                                         |
+-------------------------------------------------------+
*/
require_once __DIR__ . "/leaveApproveTs.php";

/*
+-------------------------------------------------------+
| Execute query                                         |
+-------------------------------------------------------+
*/

$mysqli = cn2();
$mysqli->begin_transaction();



// Set status as approved
$query = "UPDATE `rd_leave_records` 
        SET 
            `status_id` = '10',
            `leave_attr_id` = '$attrId',
            `nod_units` = '$noOfDays',
            `dt_last_updated` = CURRENT_TIMESTAMP(),
            `emoji` = '2' 
        WHERE 
            `id` = '$leaveId'";

if (!$mysqli->query($query)) {
    die(bdReturnJSON(["F", "System error[1] in leave Approval. $query"]));
}


# Log
#
$logM = json_encode([
    'actionByUId' => $uid,
    'message' => $log,
]);


$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveApprove', '$logM')";
if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[4] in leave Revoke Approval."]));
}


# Timesheet entry | 27-Sep-25
#
if (!addLeaveToTimesheet($leaveRec, $leaveDates, $leaveUnits, $mysqli)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[timesheet] in leave Revoke Approval."]));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave approved."]);
} else {
    bdReturnJSON(["F", "System error[3] in leave Approval."]);
}

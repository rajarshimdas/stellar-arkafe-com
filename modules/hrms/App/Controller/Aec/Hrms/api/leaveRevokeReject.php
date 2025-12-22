<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 04-Feb-2025  @nysa                        |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$leaveId = $_POST['leaveId'];

/*
+-------------------------------------------------------+
| Validation                                            |
+-------------------------------------------------------+
*/

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
            `revoke` = '2', 
            `status_id` = '33',
            `dt_last_updated` = CURRENT_TIMESTAMP(),
            `emoji` = '3'
        WHERE 
            `id` = '$leaveId'";

if (!$mysqli->query($query)) {
    die(bdReturnJSON(["F", "System error[1] in leave Revoke Reject."]));
}

/* 
# Update Leave Entitelments - None
#
*/


# Log
#
$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveRevokeReject', 'ok')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[2] in leave Revoke Reject."]));
}


// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave approved."]);
} else {
    bdReturnJSON(["F", "System error[3] in leave Revoke Reject."]);
}

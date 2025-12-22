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

// Get details of this leave application
$query = "SELECT * FROM rd_view_leaves where `id` = '$leaveId'";

$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
    $leaveRec = $row;
}

//die(bdReturnJSON(['F', $leaveRec]));

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



// Set status as approved
$query = "UPDATE `rd_leave_records` 
        SET
            `revoke` = '3', 
            `status_id` = '31',
            `dt_last_updated` = CURRENT_TIMESTAMP(),
            `emoji` = '2'
        WHERE 
            `id` = '$leaveId'";

if (!$mysqli->query($query)) {
    die(bdReturnJSON(["F", "System error[1] in leave Revoke Approval."]));
}

# Log
#
$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$thisUId', 'leaveRevokeApprove', 'ok')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[4] in leave Revoke Approval."]));
}



# Timesheet entry | 27-Sep-25
#
$query = "UPDATE 
                `timesheet` 
            SET 
                `active` = '0' 
            WHERE 
                `rd_leave_records_id` = '$leaveId' and 
                `rd_leave_records_id` > 0 and
                `project_id` < 5";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[5] in leave Revoke Approval."]));
}

// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave approved."]);
} else {
    bdReturnJSON(["F", "System error[3] in leave Revoke Approval."]);
}

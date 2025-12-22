<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   05-Feb-2025                             |
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
            `active` = '0',
            `dt_last_updated` = CURRENT_TIMESTAMP()
        WHERE 
            `id` = '$leaveId'";

if (!$mysqli->query($query)){
    die(bdReturnJSON(["F", "System error[1] in leave Delete."]));
}

# Log
#
$query = "INSERT INTO `rd_leave_log` 
            (`leave_records_id`, `user_id`, `command`, `log`) 
        VALUES 
            ('$leaveId', '$uid', 'leaveDelete', 'ok')";

if (!$mysqli->query($query)) {
    $mysqli->rollback();
    die(bdReturnJSON(["F", "System error[4] in leave Delete."]));
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
    die(bdReturnJSON(["F", "System error[5] in leave Delete."]));
}

// Confirm transaction
if ($mysqli->commit()) {
    bdReturnJSON(["T", "Leave deleted."]);
} else {
    bdReturnJSON(["F", "System error[3] in leave Delete."]);
}


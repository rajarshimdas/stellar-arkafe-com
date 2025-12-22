<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   26-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/


$leaveId = $_POST['leaveId'];
$revokeOp = $_POST['dxRevokeOp'];
$revokeRx = $_POST['dxRevokeRx'];

if ($revokeOp < 1){
    die(bdReturnJSON(['F', 'Select a Reason for Leave Revoke.', 'dxRevokeRx']));
}

$query = "UPDATE `rd_leave_records` 
        SET 
            `revoke` = '1', 
            `revoke_reason` = '$revokeRx',
            `status_id` = '30',
            `dt_last_updated` = CURRENT_TIMESTAMP(),
            `emoji` = '1'
        WHERE 
            `id` = '$leaveId' and
            `user_id` = '$uid'";

$mysqli = cn2();
if ($mysqli->query($query)) {
    bdReturnJSON(["T", "Leave Revoke applied."]);
} else {
    bdReturnJSON(["F", "System error in leave Revoke."]);
}

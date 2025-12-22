<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 26-Jan-2025                               |
| Updated On: 06-Jul-2025                               |
|               18-Dec-2025 Simplified code. Single     |
|                           leave_type_id               |
+-------------------------------------------------------+
| Sets the Starting Leave balance for the Year          |
+-------------------------------------------------------+
*/
$thisUId = $_POST['thisUId'];
$dxMonth = $_POST['dxMonth'];
$dxLeaveEL = $_POST['dxLeaveEL'];
// die(bdReturnJSON(['F', "$thisUId | $dxMonth | $dxLeaveEL"]));

// Single leave type - Earned Leaves only | 18-Dec-2025
$leave_type_id = 2;

// UserId
if ($thisUId < 1 || !$validate->integer($thisUId)) {
    die(bdReturnJSON(['F', 'System Error: UId missing']));
}

// Validate Integer 


// Save in database
$mysqli = cn2();
$mysqli->begin_transaction();

// die(bdReturnJSON(['F',"$thisUId, $leave_type_id, $added_nod, $uid, $dxMonth, $mysqli"]));

if (!bdSetLeaveEnt4User($thisUId, $leave_type_id, /*$added_nod*/ $dxLeaveEL, $uid, $dxMonth, $mysqli)) {
    $mysqli->rollback();
    die(bdReturnJSON(['F', 'System Error: Data was not saved.']));
}


// Done
$mysqli->commit();
bdReturnJSON(['T', 'ok']);

/*
+-------------------------------------------------------+
| bdSetLeaveEnt4User                                    |
+-------------------------------------------------------+
*/
function bdSetLeaveEnt4User(
    int $thisUId,           // UserId of Employee who's balance is being stored
    int $leave_type_id, 
    float $added_nod, 
    int $uid,               // Added by UserId
    string $dxMonth, 
    object $mysqli
    ): bool
{
    ## Leave Add Register
    ##
    $dt = $dxMonth . '-01';
    $year = date("Y", strtotime($dt));
    $month = date('n', strtotime($dt));
    $added_nod = $added_nod * 100;

    ## Insert or Update
    $flag = 0;
    $query = "select 
                1 as `flag`,
                `id`
            from 
                `rd_leave_add` 
            where     
                `user_id` = '$uid' and
                `year` = '$year' and
                `leave_type_id` = '$leave_type_id'";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $flag += $row['flag'];
        $rd_leave_add_id = $row['id'];
    }

    if ($flag < 1) {
        $query = "INSERT INTO `rd_leave_add` 
            (`user_id`, `dt`, `leave_type_id`, `added_nod`, `added_by_uid`, `year`, `month`, `starting_balance_flag`) 
        VALUES 
            ('$thisUId', '$dt', '$leave_type_id', '$added_nod', '$uid', '$year', '$month', '1')";
    } else {
        $query = "Update `rd_leave_add` set 
                    `added_nod` = '$added_nod',
                    `dt` = '$dt',
                    `year` = '$year',
                    `month` = '$month'
                where 
                    `id` = '$rd_leave_add_id'";
    }

    return ($mysqli->query($query)) ? true : false;
}

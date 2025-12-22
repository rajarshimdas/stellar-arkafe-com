<?php

## Month Selector
##
$monthNo = empty($_SESSION['leaveMISMonth']) ? date('n') : (float)$_SESSION['leaveMISMonth'];
require_once W3APP . '/View/Widgets/fxMonthSelect.php';

$usersById = bdGetUsersById($mysqli);
// rx($u);

## Get Leaves
##
$leaveRec = bdGetAllLeaveRecordsForMonth($monthNo, $year, $mysqli);
// rx($leaveRec);

?>
<table class='rd-table'>
    <thead>
        <tr>
            <td rowspan="2" style="width: 35px;">No</td>
            <td rowspan="2" style="width: 150px;">Name</td>
            <td rowspan="2" style="width: 100px;">Applied On</td>
            <td colspan="2" style="width: 200px;">Leave</td>
            <td rowspan="2" style="width: 120px;">Type</td>
            <td rowspan="2" style="width: 60px;">Days</td>
            <td rowspan="2">Reason</td>
            <td rowspan="2" style="width: 140px;">Status</td>
        </tr>
        <tr>
            <td style="width:100px;">From</td>
            <td style="width:100px;">To</td>
        </tr>
    </thead>

    <?php
    $no = 1;
    foreach ($leaveRec as $x):

            // $reason = ($x['status_id'] == 5) ? $x['reason'] : $x['revoke_reason'];
            $reason = ($x['revoke_reason'] == 'X') ? $x['reason'] : $x['revoke_reason'];
    ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align: left;"><?= $usersById[$x['user_id']]['displayname'] ?></td>
                <td><?= $x['dt_applied'] ?></td>
                <td><?= $x['dt_from'] . ' ' . $x['from_dt_units'] ?></td>
                <td><?= $x['dt_end'] . ' ' . $x['end_dt_units'] ?></td>
                <td style="text-align: left;"><?= $x['attribute'] ?></td>
                <td><?= (float)$x['nod_units'] ?></td>
                <td style="text-align: left;"><?= $reason ?></td>
                <td style="text-align: left;"><?= $x['status'] ?></td>
            </tr>
    <?php
    endforeach;
    ?>
</table>
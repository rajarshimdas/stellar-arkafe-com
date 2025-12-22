<?php

require_once __DIR__ . '/../leaves/manage-ux.php';

$cn0 = cn0();
$usersById = bdGetUsersById($cn0);
// var_dump($usersById);
// $subs = bdGetSubordinates($uid, cn0());
$subs = [];
$users = bdGetUsers($cn0);
foreach ($users as $u) {
    $subs[] = $u['user_id'];
}
$leaveReq = bdGetLeaveRequestForUsers($subs, $mysqli);

?>

<div id="navbox">
    Firmwide Active Leave Requests
</div>
<table class='rd-table'>
    <thead>
        <tr>
            <td rowspan="2" style="width: 35px;">No</td>
            <td rowspan="2" style="width: 250px;">Name</td>
            <td rowspan="2" style="width: 100px;">Applied On</td>
            <td colspan="2" style="width: 200px;">Leave</td>
            <td rowspan="2" style="width: 150px;">Type</td>
            <td rowspan="2" style="width: 80px;">Days</td>
            <td rowspan="2">Reason</td>
            <td rowspan="2" style="width: 180px;">Status</td>
        </tr>
        <tr>
            <td style="width:100px;">From</td>
            <td style="width:100px;">To</td>
        </tr>
    </thead>

    <?php
    $no = 1;
    foreach ($leaveReq as $x):
        if ($x['status_id'] == 5 || $x['status_id'] == 30):

            $reason = ($x['status_id'] == 5) ? $x['reason'] : $x['revoke_reason'];
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
        endif;
    endforeach;
    ?>
</table>
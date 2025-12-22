<?php
require_once __DIR__ . '/../leaves/dxDeleteLeave.php';

// rx($thisUId);
$users = bdGetUsersById(cn0());
// rx($users);
$dname = $users[$thisUId]['displayname'];
// echo $dname;

$leaveReq = bdGetLeaveRequestForUsers([$thisUId], $mysqli);
// rx($leaveReq);
?>

<div class="navbox">
    <?= $dname ?>
</div>
<table class='rd-table'>
    <thead>
        <tr>
            <td rowspan="2" style="width: 35px;">No</td>
            <td rowspan="2" style="width: 100px;">Applied On</td>
            <td colspan="2" style="width: 200px;">Leave</td>
            <td rowspan="2" style="width: 150px;">Type</td>
            <td rowspan="2" style="width: 80px;">Days</td>
            <td rowspan="2">Reason</td>
            <td rowspan="2" style="width: 140px;border-right:0px;text-align:left;">Status</td>
            <td rowspan="2" style="width: 220px;border-left:0px;"><!-- buttons --></td>
        </tr>
        <tr>
            <td style="width:100px;">From</td>
            <td style="width:100px;">To</td>
        </tr>
    </thead>

    <?php
    $no = 1;
    foreach ($leaveReq as $x):
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $x['dt_applied'] ?></td>
            <td><?= $x['dt_from'] . ' ' . $x['from_dt_units'] ?></td>
            <td><?= $x['dt_end'] . ' ' . $x['end_dt_units'] ?></td>
            <td style="text-align: left;"><?= $x['attribute'] ?></td>
            <td><?= (float)$x['nod_units'] ?></td>
            <td style="text-align: left;"><?= $x['reason'] ?></td>
            <td style="border-right:0px;text-align:left;"><?= $x['status'] ?></td>
            <td style="border-left:0px;text-align:right;">
                <button class="button-18" onclick="dxEditLeaveRec('<?= $x['id'] ?>', '<?= $dname ?>')" title="Edit">E</button>
                <?php /* if ($x['status_id'] == 5 || $x['status_id'] == 6 || $x['status_id'] == 25): */ ?>
                <button class="button-18" onclick="dxDeleteLeave('<?= $x['id'] ?>')" title="Delete">D</button>
                <?php /* endif */ ?>

            </td>
        </tr>
    <?php
    endforeach;
    ?>
</table>
<?php
// rx($x);

$leaveReqById = [];
foreach ($leaveReq as $r) {
    $leaveReqById[$r['id']] = $r;
}

// require_once __DIR__ . '/dxSpecialLeave.php';
require_once __DIR__ . '/dxEditLeaveRec.php';
?>

<script>
    let leaveId = 0;
    const dname = '<?= $dname ?>';
    const thisUserId = <?= $thisUId ?>;
    const leaveReqById = <?= json_encode($leaveReqById) ?>;
</script>
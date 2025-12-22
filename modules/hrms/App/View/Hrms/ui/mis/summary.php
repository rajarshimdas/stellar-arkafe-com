<!-- <div class="alert">Beta! Work-in-progress</div> -->
<?php

$leave_type_id = 2; // Caveat.

## Month Selector
##
$monthNo = empty($_SESSION['leaveMISMonth']) ? date('n') : (int)$_SESSION['leaveMISMonth'];
require_once W3APP . '/View/Widgets/fxMonthSelect.php';

$users = bdGetUsers($mysqli);
//var_dump($users);

$leaveTypes = bdGetLeaveTypes($mysqli);
//var_dump($leaveTypes);
$leaveTypesById = bdGetLeaveTypesById($mysqli);

// Employee Data
$usersData = bdGetEmployeeDataById($mysqli);

// Starting Balance
$allUserStartBal = bdGetAllUserLeaveStartMonthAndBal(
    $year,
    $mysqli
);

// Leave Availed 
$lxAvailed = bdGetAllUserLeaveAvailedMonthly($year, $mysqli);
// die(rx($lxAvailed[$thisUId]));

// Leave Applied
$lxApplied = bdGetAllUserLeaveAppliedMonthly($year, $mysqli);
// rx($lxApplied);

?>

<table class="rd-table">
    <thead>
        <tr>
            <td rowspan="3" style="width:30px;">No</td>
            <td rowspan="3">Employee</td>
            <?php
            $leaveTypesCo = 0;
            $ltId = [];

            foreach ($leaveTypes as $t) {
                if ($t['is_normal'] > 0 && $t['active']):
                    $leaveTypesCo++;
                    $ltId[] = $t['id'];
            ?>
                    <td colspan="9"><?= $t['type'] ?></td>
            <?php
                endif;
            }
            ?>
            <td colspan="2">Status</td>
            <td rowspan="3" style="width: 80px;">Max<br>Encashment</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < $leaveTypesCo; $i++) : ?>
                <td colspan="2" style="width:80px;">Starting Balance</td>
                <td colspan="2" style="width:80px;"><?= $monthNm[$monthNo] ?></td>
                <td colspan="5">Total Availed till <?= $months[$monthNo] ?></td>
                <td rowspan="2" style="width:80px;">Paid</td>
                <td rowspan="2" style="width:80px;">Unpaid</td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width:80px;">Month</td>
            <td style="width:80px;">Leaves</td>
            <td style="width:80px;">Total</td>
            <td style="width:80px;">Balance</td>
            <td style="width:80px;">SL</td>
            <td style="width:80px;">IL</td>
            <td style="width:80px;">IL(M)</td>
            <td style="width:80px;">UNSL</td>
            <td style="width:80px;">Total</td>
        </tr>
    </thead>
    <?php
    $no = 1;
    // var_dump($leaveEnt);
    foreach ($users as $u):

        $thisUId = $u['user_id'];
        // die(rx($u));

        if ($u['active'] > 0 && !in_array(strtolower($u['loginname']), $noTabulationForUsers)):

            // Fetch Leave data
            $x = bdLeaveMonthyStatsForUser(
                $u,
                empty($allUserStartBal[$thisUId]) ? [] : $allUserStartBal[$thisUId],
                empty($lxAvailed[$thisUId]) ? [] : $lxAvailed[$thisUId],
                empty($lxApplied[$thisUId]) ? [] : $lxApplied[$thisUId],
                $year
            );
            // die(rx($x));

            $m = empty($x['month'][$monthNo]) ? [] : $x['month'][$monthNo];

            $dname = $u['displayname'];
            $doj = $usersData[$thisUId]['dt_doj'];
            $doe = $usersData[$thisUId]['dt_doe'];

            $dojMonthNo = ($doj < "$year-01-01") ? 0 : (float)date('n', strtotime($doj));

            $attrs = ($doj > "$year-01-01") ? " (doj: " . bdISODateToCal($doj) . ")" : "";
            $attrs = ($doe < "$year-12-31") ? $attrs . " (doe: " . bdISODateToCal($doe) . ")" : $attrs;
    ?>
            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align:left;"><?= $x['employee'] /*. $attrs */ ?></td>
                <td><?= $months[$x['user']['startBalMoNo']] ?></td>
                <td><?= $x['user']['startBalNOD'] ?></td>
                <td><?= /* NOD Accrued */ $m['totalLeaveAccrued']  ?></td>
                <td><?= /* NOD Available */ $m['balEOM'] ?></td>
                <td><?= /* Sanctioned */ $m['gtSL'] ?></td>
                <td><?= /* Informed */ $m['gtIL'] ?></td>
                <td><?= /* Informed */ $m['gtIM'] ?></td>
                <td><?= /* Un-sanctioned */ $m['gtUS'] ?></td>
                <td><?= /* Total EL */ $m['gtAvailed'] ?></td>
                <td><?= /* Paid */ $m['gtLeavePaid'] ?></td>
                <td><?= /* Unpaid */ $m['gtLeaveUnpaid'] ?></td>
                <td style="text-align: center;"><?= ($m['totalLeaveAccrued'] - $m['gtLeavePaid']) ?></td>
            </tr>
    <?php
        endif;
    endforeach;
    ?>
</table>
<script>
    let thisUserId = 0

    let leaveTypeSname = [
        <?php
        foreach ($ltId as $i):
            $leaveTypeSname = $leaveTypesById[$i]['sname'];
            echo '"' . $leaveTypeSname . '",';
        endforeach;
        ?>
    ];

    function manageEmp(UId) {
        window.location = '<?= BASE_URL ?>aec/hrms/ui/employees/manage-emp/' + UId;
    }
</script>
<?php
/*
require_once BD . '/Toolbox/dxMessageBox.php';
require_once __DIR__ . '/dxStartBal.php';
require_once __DIR__ . '/dxSpecialLeave.php';
*/
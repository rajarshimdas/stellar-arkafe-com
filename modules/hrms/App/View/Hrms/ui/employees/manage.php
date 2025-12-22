<?php
/*
# Year set to future or past
#
$curYear = date('Y');
if ($leaveMISYear > $curYear)
    # Future
    $curMonthNo = 1;
elseif ($leaveMISYear < $curYear)
    # Past
    $curMonthNo = 12;
else
    # This Year
    $curMonthNo = date('n');
*/
// rx($year);

$leave_type_id = 2; // Caveat.

## Month Selector
##
$monthNo = empty($_SESSION['leaveMISMonth']) ? date('n') : (float)$_SESSION['leaveMISMonth'];
require_once W3APP . '/View/Widgets/fxMonthSelect.php';

$users = bdGetUsers($mysqli);
//var_dump($users);

/*
$leaveTypes = bdGetLeaveTypes($mysqli);
//var_dump($leaveTypes);
$leaveTypesById = bdGetLeaveTypesById($mysqli);
$leaveTypesCo = 0;
$ltId = [];
*/

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
            <td colspan="9">Earned Leave</td>
            <td colspan="2">Status</td>
            <td rowspan="3" style="width:150px;"></td>
        </tr>
        <tr>
            <td colspan="2" style="width:80px;">Starting Balance</td>
            <td rowspan="2" style="width:80px;">Total Eligible</td>
            <td rowspan="2" style="width:80px;">Current Balance</td>
            <td colspan="5">Availed in <?= $months[$monthNo] ?></td>
            <td rowspan="2" style="width:80px;">Paid</td>
            <td rowspan="2" style="width:80px;">Unpaid</td>
        </tr>
        <tr>
            <td style="width:80px;">Month</td>
            <td style="width:80px;">Leaves</td>
            <td style="width:80px;">SL</td>
            <td style="width:80px;">IL</td>
            <td style="width:80px;">IL(M)</td>
            <td style="width:80px;">UNSL</td>
            <td style="width:80px;">Total</td>
        </tr>
    </thead>
    <?php
    $no = 1;
    foreach ($users as $u) {

        $thisUId = $u['user_id'];

        if ($u['active'] > 0 && !in_array(strtolower($u['loginname']), $noTabulationForUsers)):

            $dname = $u['displayname'];
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

    ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="hotKey" style="text-align: left;" onclick="manageEmp(<?= $thisUId ?>)"><?= $dname ?></td>

                <td><?= $months[$x['user']['startBalMoNo']] ?></td>
                <td><?= $x['user']['startBalNOD'] ?></td>
                <td><?= /* NOD Accrued */ $m['totalLeaveAccrued']  ?></td>
                <td><?= /* NOD Available */ $m['balEOM'] ?></td>
                <td><?= /* Sanctioned */ $m['availedSL'] ?></td>
                <td><?= /* Informed */ $m['availedIL'] ?></td>
                <td><?= /* Informed */ $m['availedIM'] ?></td>
                <td><?= /* Un-sanctioned */ $m['availedUS'] ?></td>
                <td><?= /* Total EL */ $m['availedTotal'] ?></td>
                <td><?= /* Paid */ $m['leavePaid'] ?></td>
                <td><?= /* Unpaid */ $m['leaveUnpaid'] ?></td>

                <td style="text-align: center;">
                    <button class="button-18"
                        onclick="dxLeaveUpdate(<?= $thisUId . ',\'' . $dname . '\'' ?>)">Balance</button>
                    <a class="button-18"
                        href="<?= BASE_URL ?>aec/hrms/ui/employees/manage-emp-mo/<?= $thisUId ?>">M</a>
                </td>
            </tr>
    <?php
        endif;
    }
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
require_once BD . '/Toolbox/dxMessageBox.php';
require_once __DIR__ . '/dxStartBal.php';
require_once __DIR__ . '/dxSpecialLeave.php';

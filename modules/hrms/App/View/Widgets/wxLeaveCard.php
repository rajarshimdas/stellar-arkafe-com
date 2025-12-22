<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   12-Jul-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
| Variables                                             |
|                                                       |                                                             
| $thisUId                                              |
| $leave_type_id                                        |
+-------------------------------------------------------+
*/
?>

<style>
    table#tbl-my-leave-status {
        border-spacing: 0px;

    }

    table#tbl-my-leave-status tr td {
        /* background-color: rgb(59, 77, 102); */
        color: white;
        padding: 4px;
        height: 25px;
    }

    table#tbl-my-leave-status tr td.box2 {
        width: 70px;
        font-family: 'Roboto Bold';
        /* background-color: rgb(29, 56, 88); */
        border-right: 8px solid cadetblue;
        text-align: center;
    }

    table.rd-table-card {
        border-collapse: collapse;
        background-color: rgb(24 118 120);
    }

    table.rd-table-card tr td {
        border: 1px solid #5f9ea0;
        text-align: center;
        color: white;
        height: 25px;
    }
</style>

<?php
// Caveat
if (empty($leave_type_id)) $leave_type_id = 2;

// Employee joining and termination date
$usersData = bdGetEmployeeDataById($mysqli);
$thisUser = $usersData[$thisUId];

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

// Fetch Leave data
$x = bdLeaveMonthyStatsForUser(
    $thisUser,
    empty($allUserStartBal[$thisUId]) ? [] : $allUserStartBal[$thisUId],
    empty($lxAvailed[$thisUId]) ? [] : $lxAvailed[$thisUId],
    empty($lxApplied[$thisUId]) ? [] : $lxApplied[$thisUId],
    $year,
    date('n')
);

$curMonthNo = date('n');
if (empty($monthNo)) $monthNo = $curMonthNo;

$doj = $x['user']['doj'];
$doe = $x['user']['doe'];

$m = empty($x['month'][$monthNo]) ? [] : $x['month'][$monthNo];
?>

<table class="rd-table-card">
    <thead>
        <tr>
            <td colspan="8">Earned Leave</td>
            <td colspan="2">Status</td>
        </tr>
        <tr>
            <td colspan="2" style="width:90px;">Balance</td>
            <td colspan="5">Availed</td>
            <td rowspan="2" style="width:90px;">Applied</td>
            <td rowspan="2" style="width:90px;">Paid</td>
            <td rowspan="2" style="width:90px;">Unpaid</td>
        </tr>
        <tr>
            <td style="width:90px;">Accrued</td>
            <td style="width:90px;">Current</td>
            <td style="width:90px;">SL</td>
            <td style="width:90px;">IL</td>
            <td style="width:90px;">IL(M)</td>
            <td style="width:90px;">UNSL</td>
            <td style="width:90px;">Total</td>
        </tr>
    </thead>
    <tbody>
        <tr style="font-family: 'Roboto Bold'; height: 40px;">
            <td><?= /* NOD Accrued */ $m['totalLeaveAccrued'] ?></td>
            <td><?= /* NOD Available */ $m['balEOM'] ?></td>
            <td><?= /* Sanctioned */ $m['gtSL'] ?></td>
            <td><?= /* Informed */ $m['gtIL'] ?></td>
            <td><?= /* Informed */ $m['gtIM'] ?></td>
            <td><?= /* Un-sanctioned */ $m['gtUS'] ?></td>
            <td><?= /* Total EL */ $m['gtAvailed'] ?></td>
            <td><?= /* Applied */ $m['appliedLeaves'] ?></td>
            <td><?= /* Paid */ $m['gtLeavePaid'] ?></td>
            <td><?= /* Unpaid */ $m['gtLeaveUnpaid'] ?></td>
        </tr>
    </tbody>
</table>
<?php /* Monthly Report
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   12-Jul-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
| Variables                                             |
|                                                       |                                                                 
| $thisUId                                              |
| $monthNo                                              |
| $year                                                 |
+-------------------------------------------------------+
*/
// $thisUId = 290; // Amruta Patel
// $thisUId = 152; // Anand Thakur
// $thisUId = 308; // Rishika More

// $monthNo = 12;
$leave_type_id = 2;

// Employee Data
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
    $monthNo
);

// die(rx($x));
?>
<table class="rd-table">
    <thead>
        <tr>
            <td colspan="13">
                <!-- <div style="color:red;">Beta!&nbsp;</div> -->
                <span style="font-family:'Roboto Bold';color:var(--rd-light-gray);">
                    <?= $x['employee'] /*. $doJoinAndExit */ ?>
                </span>
                <div class="rd-footnote" style="text-align:center;padding:2px;">
                    <?= $x['user']['designation'] ?>
                </div>
            </td>
        </tr>
        <tr>
            <td rowspan="3" style="width:45px;">No</td>
            <td rowspan="3" style="text-align: left; padding-left: 15px;"><?= 'Month ' . $year ?></td>
            <td colspan="9">Earned Leave</td>
            <td colspan="2">Status</td>
        </tr>
        <tr>
            <td colspan="3" style="width:90px;">Balance</td>
            <td colspan="5">Availed</td>
            <td rowspan="2" style="width:90px;">Applied</td>
            <td rowspan="2" style="width:90px;">Paid<br>Leaves</td>
            <td rowspan="2" style="width:90px;">Unpaid<br>Leaves</td>
        </tr>
        <tr>
            <td style="width:90px;">Total</td>
            <td style="width:90px;" title="Start of Month">SOM</td>
            <td style="width:90px;" title="End of Month">EOM</td>
            <td style="width:90px;" title="Santioned Leave">SL</td>
            <td style="width:90px;" title="Informed Leave">IL</td>
            <td style="width:90px;" title="Informed Leave">IL(M)</td>
            <td style="width:90px;" title="Un-santioned Leave">UNSL</td>
            <td style="width:90px;">Total</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $curMonthNo = date('n');
        if (empty($monthNo)) $monthNo = empty($leaveMISMonth) ? $curMonthNo : (int)$leaveMISMonth;

        $doj = $x['user']['doj'];
        $doe = $x['user']['doe'];

        for ($i = 0; $i < 12; $i++) :

            $no = $i + 1;
            $m = empty($x['month'][$no]) ? [] : $x['month'][$no];
            // die(rx($m));
        ?>
            <tr
                <?= ($curMonthNo > $i) ? 'class="highlight"' : ''; ?>
                <?= ($monthNo == $no) ? ' style="background-color:cadetblue;"' : ''; ?>>

                <td><?= /* Serial No */ $no ?></td>
                <td style="text-align: left; padding-left: 15px;">
                    <?php
                    /* Month */
                    echo $months[$no];

                    if (date('Y-M-01', strtotime($doj)) == $year . '-' . $months[$no] . '-01') echo "&nbsp;(doj: " . date("d-M-y", strtotime($doj)) . ')';
                    if (date('Y-M-01', strtotime($doe)) == $year . '-' . $months[$no] . '-01') echo "&nbsp;(doe: " . date("d-M-y", strtotime($doe)) . ')';
                    ?>
                </td>
                <td><?= /* Balance Accrued */ bdReplaceZerosWithBlank($m['totalLeaveAccrued']) ?></td>
                <td><?= /* Balance SOM */ bdReplaceZerosWithBlank($m['balSOM']) ?></td>
                <td><?= /* Balance EOM */ bdReplaceZerosWithBlank($m['balEOM']) ?></td>
                <td><?= /* Availed SL */ bdReplaceZerosWithBlank($m['availedSL']) ?></td>
                <td><?= /* Availed IL */ bdReplaceZerosWithBlank($m['availedIL']) ?></td>
                <td><?= /* Availed IL */ bdReplaceZerosWithBlank($m['availedIM']) ?></td>
                <td><?= /* Availed UNSL */ bdReplaceZerosWithBlank($m['availedUS']) ?></td>
                <td><?= /* Availed Total */ bdReplaceZerosWithBlank($m['availedTotal']) ?></td>
                <td><?= /* Leave Applied */ bdReplaceZerosWithBlank($m['appliedLeaves']) ?></td>
                <td><?= /* Paid Leaves */ bdReplaceZerosWithBlank($m['leavePaid']) ?></td>
                <td><?= /* Unpaid Leaves */ bdReplaceZerosWithBlank($m['leaveUnpaid']) ?></td>
            </tr>
        <?php endfor; ?>
        <tr>
            <td colspan="5" style="text-align: right;padding-right:10px;">Total</td>
            <td><?= $x['month'][12]['gtSL'] ?></td>
            <td><?= $x['month'][12]['gtIL'] ?></td>
            <td><?= $x['month'][12]['gtIM'] ?></td>
            <td><?= $x['month'][12]['gtUS'] ?></td>
            <td><?= $x['month'][12]['gtAvailed'] ?></td>
            <td></td>
            <td><?= $x['month'][12]['gtLeavePaid'] ?></td>
            <td><?= $x['month'][12]['gtLeaveUnpaid'] ?></td>
        </tr>
    </tbody>
</table>
<div class="rd-footnote">
    * Hover and hold over abbreviated text to display full form.
</div>


<table class="rd-table" style="width: 800px;">
    <caption style="padding:30px 0px 10px 0px;font-family:'Roboto Bold';color:var(--rd-light-gray);">
        <?= 'Leave Encashment ' . $months[$monthNo] . '&nbsp;' . $year . ' EOM' ?>
    </caption>
    <thead>
        <tr>
            <td style="width: 25%;">Eligibility</td>
            <td style="width: 25%;">Total Availed</td>
            <td style="width: 25%;">Paid Leaves</td>
            <td>Max Encashment</td>
        </tr>
    </thead>
    <tr>
        <?php $e = $x['encashment']; ?>
        <td><?= $e['enEntitlement'] ?></td>
        <td><?= $e['enTotalAvailed'] ?></td>
        <td><?= $e['enPaidLeave'] ?></td>
        <td><?= $e['enMaxEntitlement'] ?></td>
    </tr>
</table>
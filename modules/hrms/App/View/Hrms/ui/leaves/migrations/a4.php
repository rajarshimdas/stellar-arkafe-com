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
$thisUId = 290; // Amruta Patel
// $thisUId = 152; // Anand Thakur
// $thisUId = 308; // Rishika More

$monthNo = 12;
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
    12
);

die(rx($x));
?>
<table class="rd-table">
    <thead>
        <tr>
            <td colspan="12">
                <span style="color:red;">Beta!&nbsp;</span>
                <span style="font-family:'Roboto Bold';color:var(--rd-light-gray);">
                    <?= $x['employee'] /*. $doJoinAndExit */ ?>
                </span>
                <?= $m['designation'] ?>
            </td>
        </tr>
        <tr>
            <td rowspan="3" style="width:45px;">No</td>
            <td rowspan="3" style="text-align: left; padding-left: 15px;"><?= 'Month ' . $year ?></td>
            <td colspan="8">Earned Leave</td>
            <td colspan="2">Status</td>
        </tr>
        <tr>
            <td colspan="3" style="width:100px;">Balance</td>
            <td colspan="4">Availed</td>
            <td rowspan="2" style="width:100px;">Applied</td>
            <td rowspan="2" style="width:100px;">Paid<br>Leaves</td>
            <td rowspan="2" style="width:100px;">Unpaid<br>Leaves</td>
        </tr>
        <tr>
            <td style="width:100px;">Total</td>
            <td style="width:100px;" title="Start of Month">SOM</td>
            <td style="width:100px;" title="End of Month">EOM</td>
            <td style="width:100px;" title="Santioned Leave">SL</td>
            <td style="width:100px;" title="Informed Leave">IL</td>
            <td style="width:100px;" title="Un-santioned Leave">UNSL</td>
            <td style="width:100px;">Total</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $curMonthNo = date('n');
        $doj = $x['user']['doj'];
        $doe = $x['user']['doe'];

        for ($i = 0; $i < 12; $i++) :

            $no = $i + 1;
            $m = $x['month'][$no];
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
            <td><?= $x['month'][12]['gtUS'] ?></td>
            <td><?= $x['month'][12]['gtAvailed'] ?></td>
            <td></td>
            <td><?= $x['month'][12]['gtLeavePaid'] ?></td>
            <td><?= $x['month'][12]['gtLeaveUnpaid'] ?></td>
        </tr>
    </tbody>
</table>
<div style="font-size: 0.85em;">&nbsp;* Hover and hold over abbreviated text to display full form.</div>


<table class="rd-table" style="width: 800px;">
    <caption style="padding-top:20px;font-family:'Roboto Bold';color:var(--rd-light-gray)">Leave Encashment</caption>
    <thead>
        <tr>
            <td style="width: 25%;">Leave eligibility till end of <?= $monthNm[$monthNo] . '&nbsp;' . $year ?></td>
            <td style="width: 25%;">Total Availed</td>
            <td style="width: 25%;">Paid Leaves</td>
            <td>Max Encashment</td>
        </tr>
    </thead>
    <tr>
        <td><?= $x['month'][$monthNo]['totalLeaveAccrued'] ?></td>
        <td><?= $x['month'][$monthNo]['gtAvailed'] ?></td>
        <td><?= $x['month'][$monthNo]['gtLeavePaid'] ?></td>
        <td>
            <?= ($x['month'][$monthNo]['totalLeaveAccrued'] - $x['month'][$monthNo]['gtLeavePaid']) ?>
        </td>
    </tr>
</table>
<style>
    table.rd-table tbody tr td {
        text-align: left;
        vertical-align: top;
        height: min-content;
    }

    div.hday {
        background-color: #bebebe;
        color: white;
        padding: 0px 6px;
    }

    div.wday {
        padding: 0px 6px;
    }
</style>
<table class="rd-table">
    <thead>
        <tr>
            <td rowspan="2" style="width: 40px;">No</td>
            <td rowspan="2" style="text-align: left;">Employee</td>
            <td colspan="4">Half Day</td>
            <td colspan="4">Full Day</td>
            <td colspan="2">Short Leave</td>
            <td colspan="2">Status</td>
            <td rowspan="2" style="width:70px;">Max<br>Encashment</td>
        </tr>
        <tr>
            <td style="width:70px;">SL</td>
            <td style="width:70px;">IL</td>
            <td style="width:70px;">IL(M)</td>
            <td style="width:70px;">UNSL</td>
            <td style="width:70px;">SL</td>
            <td style="width:70px;">IL</td>
            <td style="width:70px;">IL(M)</td>
            <td style="width:70px;">UNSL</td>
            <td style="width:70px;">LC</td>
            <td style="width:70px;">EG</td>
            <td style="width:70px;">Paid</td>
            <td style="width:70px;">Unpaid</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($users as $u) :
            $thisUId = $u['user_id'];


            if ((!empty($pto[$thisUId]) || $u['active'] > 0) && !in_array(strtolower($u['loginname']), $noTabulationForUsers)):

                // Paid | Unpaid
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


                // PTO 
                $hdSL = '';
                $hdIL = '';
                $hdIM = '';
                $hdUN = '';

                $fdSL = '';
                $fdIL = '';
                $fdIM = '';
                $fdUN = '';

                $lc = '';
                $eg = '';

                if (!empty($pto[$thisUId])) {

                    $p = $pto[$thisUId];
                    // rx($p);

                    if (!empty($p['halfday'][10])) {
                        $dates = $p['halfday'][10];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $hdSL = $hdSL . $div . dateFx($dt) . '</div>';
                        }
                    }

                    if (!empty($p['halfday'][30])) {
                        $dates = $p['halfday'][30];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $hdIL = $hdIL . $div . dateFx($dt) . '</div>';
                        }
                    }




                    if (!empty($p['halfday'][31])) {
                        $dates = $p['halfday'][31];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $hdIM = $hdIM . $div . dateFx($dt) . '</div>';
                        }
                    }


                    if (!empty($p['halfday'][20])) {
                        $dates = $p['halfday'][20];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $hdUN = $hdUN . $div . dateFx($dt) . '</div>';
                        }
                    }

                    if (!empty($p['fullday'][10])) {
                        $dates = $p['fullday'][10];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $fdSL = $fdSL . $div . dateFx($dt) . '</div>';
                        }
                    }

                    if (!empty($p['fullday'][30])) {
                        $dates = $p['fullday'][30];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $fdIL = $fdIL . $div . dateFx($dt) . '</div>';
                        }
                    }



                    if (!empty($p['fullday'][31])) {
                        $dates = $p['fullday'][31];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $fdIM = $fdIM . $div . dateFx($dt) . '</div>';
                        }
                    }




                    if (!empty($p['fullday'][20])) {
                        $dates = $p['fullday'][20];
                        sort($dates);
                        foreach ($dates as $dt) {
                            $div = (in_array($dt, $hDates)) ? '<div class="hday">' : '<div class="wday">';
                            $fdUN = $fdUN . $div . dateFx($dt) . '</div>';
                        }
                    }


                    if (!empty($p['shortleave']['LC'])) $lc = dateFx($p['shortleave']['LC'][0]);
                    if (!empty($p['shortleave']['EG'])) $eg = dateFx($p['shortleave']['EG'][0]);
                }
        ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= $u['displayname'] ?></td>
                    <td><?= $hdSL ?></td>
                    <td><?= $hdIL ?></td>
                    <td><?= $hdIM ?></td>
                    <td><?= $hdUN ?></td>
                    <td><?= $fdSL ?></td>
                    <td><?= $fdIL ?></td>
                    <td><?= $fdIM ?></td>
                    <td><?= $fdUN ?></td>
                    <td><?= $lc ?></td>
                    <td><?= $eg ?></td>

                    <td style="text-align: center;"><?= $m['leavePaid'] ?></td>
                    <td style="text-align: center;"><?= $m['leaveUnpaid'] ?></td>

                    <td style="text-align: center;"><?= ($m['totalLeaveAccrued'] - $m['gtLeavePaid']) ?></td>
                </tr>
        <?php
            endif;
        endforeach;
        ?>
    </tbody>
</table>
<div class="rd-footnote">
    * To generate correct PTO, please check calendar view in employee tab to ensure approval status of all employee leave application are green.
</div>
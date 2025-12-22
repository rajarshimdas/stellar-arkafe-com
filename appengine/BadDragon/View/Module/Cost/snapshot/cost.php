<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   23-Dec-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// echo "pid: $pid | fdt: $fdt | tdt: $tdt";
if ($pid < 500){
    die("<div class='messagebarError'>Please select a project.</div>");
}

// Validate date range is from same fimancial year
$fy1 = bdFinancialYear($fdt);
$fy2 = bdFinancialYear($tdt);

if ($fy1['finStartYear'] != $fy2['finStartYear']) {
    die("<div class='messagebarError'>Please select a date range from same Financial Year.</div>");
}

$finStartYear = $fy1['finStartYear'];
//echo "FY: ". $finStartYear;

$mhRateMultiYear = bdManhourCost($mysqli);
$mhRate = $mhRateMultiYear[$finStartYear];
// echo '<pre>', var_dump($mhRate), '</pre>';

$scope = bdGetProjectScopeArray($mysqli);
array_push($scope, ['id' => 1, 'scope' => 'NA']);

$stage = bdGetProjectStageArray($mysqli);
array_push($stage, ['id' => 1, 'sname' => 'NA']);

$users = bdGetUsersArray($mysqli);
$userById = bdGetUsersArrayX($mysqli);
/*
$query = "SELECT 
            t2.projectscope_id as scope_id,
            t2.projectstage_id as stage_id,
            t1.user_id,
            sum(t1.no_of_hours) as h,
            sum(t1.no_of_min) as m
        FROM 
            timesheet as t1,
            task as t2
        where
            t1.project_id = '$pid' and
            t1.dt >= '$fdt' and
            t1.dt <= '$tdt' and
            t1.task_id = t2.id and
            t1.quality < 1 and
            t1.active > 0
        group by
            scope_id,
            stage_id,
            user_id";
*/

$query = "SELECT 
            projectscope_id as scope_id,
            projectstage_id as stage_id,
            user_id,
            sum(no_of_hours) as h,
            sum(no_of_min) as m
        FROM 
            timesheet
        where
            project_id = '$pid' and
            dt >= '$fdt' and
            dt <= '$tdt' and
            quality < 1 and
            active > 0
        group by
            scope_id,
            stage_id,
            user_id";

//echo $query;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {
        $team[] = $row['user_id'];
        $tm[$row['scope_id']][$row['stage_id']][$row['user_id']] = $row;
    };

    $result->close();
}

// Team: Remove duplicates
// $team = array_unique($team);

// Team: Sort by Name
foreach ($users as $u) {
    if (in_array($u['user_id'], $team)) {
        $teamFn[] = [$u['user_id'], $userById[$u['user_id']]['displayname']];
        // echo '<br>'. $userById[$u['user_id']]['displayname'];

        # Totals
        $mt[$u['user_id']] = [
            'h' => 0,
            'm' => 0,
            'cost' => 0,
        ];
    }
}

// Team: count
if (empty($teamFn)) {
    /*
    $teamCo = 1;
    $teamFn[] = [0, 'NA'];
    */
    die("<div class='messagebarError'>No data available for selected date range.</div>");
} else {
    $teamCo = count($teamFn);
}
?>
<style>
    table.tabulation tr td {
        text-align: center;
    }

    table.tabulation thead tr td {
        text-align: center;
        vertical-align: middle;
        line-height: 1.1em;
    }

    table.tabulation tr td.team {
        padding: 4px;
        font-size: 0.85em;
    }

    div.mh {
        color: green;
        line-height: 1.2em;
    }

    div.cost {
        color: blue;
        line-height: 1.2em;

    }
</style>
<table class="tabulation" style="width: 100%;">
    <thead>
        <tr class="headerRow">
            <td rowspan="2" style="width: 25px;">No</td>
            <td rowspan="2" style="width: 70px;">Scope</td>
            <td rowspan="2" style="width: 70px;">Milestone</td>
            <td colspan="<?= $teamCo ?>">Team</td>
            <td colspan="2">Total</td>
            <td colspan="2">Total</td>
        </tr>
        <tr class="headerRow">
            <?php
            foreach ($teamFn as $u) {
                echo '<td>' . $u[1] . '</td>';
            }
            ?>
            <td style="width: 60px;">MH</td>
            <td style="width: 90px;">Cost</td>
            <td style="width: 60px;">MH</td>
            <td style="width: 90px;">Cost</td>
        </tr>
    </thead>


    <?php
    ## Scope Totals
    foreach ($scope as $sc) {

        $scId = $sc['id'];
        $scNm = $sc['scope'];

        $scopeTotal[$scId] = [
            'h' => 0,
            'm' => 0,
            'cost' => 0,
        ];

        if (!empty($tm[$scId])) {

            $tmSc = $tm[$scId];

            foreach ($stage as $st) {

                $stId = $st['id'];
                $stNm = $st['sname'];

                if (!empty($tmSc[$stId])) {

                    $tmSt = $tmSc[$stId];

                    ## Team
                    $rowH = 0;
                    $rowM = 0;
                    $rowCx = 0;

                    foreach ($teamFn as $m) {

                        $mId = $m[0];
                        if (!empty($tmSt[$mId])) {

                            $e = $tmSt[$mId];
                            $ex = bdFinCalculateMHCost4User($mId, $mhRate, $e['h'], $e['m']);

                            $rowH   += $e['h'];
                            $rowM   += $e['m'];
                            $rowCx  += $ex['costFloat'];
                        }
                    }

                    # Milestone 
                    // $rowMH = bdAddHourMin($rowH, $rowM);
                    // $rowCx = number_format($rowCx, 2, '.', ',');

                    $scopeTotal[$scId]['h'] += $rowH;
                    $scopeTotal[$scId]['m'] += $rowM;
                    $scopeTotal[$scId]['cost'] += $rowCx;
                }
            }
        }
    }
    // var_dump($scopeTotal);
    ?>

    <?php
    ## Rows
    $sno = 1;
    ## Scope
    foreach ($scope as $sc) {

        $scId = $sc['id'];
        $scNm = $sc['scope'];

        if (!empty($tm[$scId])) {

            $tmSc = $tm[$scId];

            // $totalTickets = array_sum(array_map("count", $tickets));
            //$scCo = array_sum(array_map("count", $tmSc));
            $scCo = count($tmSc);

            //echo '<br>' . $scNm . ' | ' . $scCo;
            echo "<tr><td rowspan='$scCo'>" . $sno++ . "</td><td rowspan='$scCo'>$scNm</td>";

            ## Milestone
            $flag1st = "T";
            foreach ($stage as $st) {

                $stId = $st['id'];
                $stNm = $st['sname'];

                if (!empty($tmSc[$stId])) {

                    $tmSt = $tmSc[$stId];
                    //echo "<br>+ $stNm:";

                    // 2nd row onwards
                    if ($flag1st != "T") {
                        echo "<tr>";
                    }

                    echo "<td>$stNm</td>";

                    ## Team
                    //if (!empty($tX)) unset($tX);
                    $rowH = 0;
                    $rowM = 0;
                    $rowCx = 0;

                    foreach ($teamFn as $m) {

                        $mId = $m[0];
                        if (!empty($tmSt[$mId])) {

                            $e = $tmSt[$mId];
                            $ex = bdFinCalculateMHCost4User($mId, $mhRate, $e['h'], $e['m']);

                            $e1 = '<div class="mh">' . $ex['manhours'] . '</div><div class="cost">' . $ex['cost'] . '</div>';

                            $rowH   += $e['h'];
                            $rowM   += $e['m'];
                            $rowCx  += $ex['costFloat'];

                            $mt[$mId]['h'] += $e['h'];
                            $mt[$mId]['m'] += $e['m'];
                            $mt[$mId]['cost'] += $ex['costFloat'];
                        } else {
                            $e1 = '<!-- Zero -->';
                        }

                        //echo " $e1 |";
                        echo "<td class='team'>$e1</td>";
                    }

                    # Milestone 
                    $rowMH = bdAddHourMin($rowH, $rowM);
                    $rowCx = number_format($rowCx, 2, '.', ',');

                    echo "<td>$rowMH</td><td style='text-align:right'>$rowCx</td>";

                    # Scope Total
                    if ($flag1st == "T") {
                        $flag1st = "F";
                        $s = $scopeTotal[$scId];
                        echo "<td rowspan='$scCo'>" . bdAddHourMin($s['h'], $s['m']) . "</td>";
                        echo "<td rowspan='$scCo' style='text-align:right'>" . number_format($s['cost'], 2, '.', ',') . "</td>";
                    }

                    echo "</tr>";
                }
            }
        }
    }
    ?>
    <tr>
        <td colspan="3" style='text-align:left;'>Total Manhours</td>
        <?php
        $tH = 0;
        $tM = 0;

        foreach ($teamFn as $m) {
            $mh = bdAddHourMin($mt[$m[0]]['h'], $mt[$m[0]]['m']);
            echo "<td>$mh</td>";

            $tH += $mt[$m[0]]['h'];
            $tM += $mt[$m[0]]['m'];
        }
        ?>
        <td></td>
        <td></td>
        <td style="color:green;font-weight:bold;">
            <?= bdAddHourMin($tH, $tM) ?>
        </td>
        <td></td>
    </tr>

    <tr>
        <td colspan="3" style='text-align:left;'>Manhour Rate</td>
        <?php
        foreach ($teamFn as $m) {
            echo '<td>' . $mhRate[$m[0]]['costRs'] . '</td>';
        }
        ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3" style='text-align:left;'>Total Cost</td>
        <?php
        $totalCx = 0;
        foreach ($teamFn as $m) {

            $tcx = number_format($mt[$m[0]]['cost'], 2, '.', ',');
            echo "<td>$tcx</td>";

            $totalCx += $mt[$m[0]]['cost'];
        }
        ?>
        <td></td>
        <td></td>
        <td></td>
        <td style='text-align:right;color:blue;font-weight:bold;'>
            <?= number_format($totalCx, 2, '.', ',') ?>
        </td>
    </tr>
</table>

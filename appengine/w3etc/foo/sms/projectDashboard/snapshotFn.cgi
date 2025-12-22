<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   15-Jun-2012       			            |
| Updated On:   07-Oct-2023                             |
|               27-Oct-2023                             |
+-------------------------------------------------------+
*/
// All are from w3etc
require_once 'foo/timesheets/projectViewFn.php';
require_once 'foo/sms/projectSchedule.php';
require_once 'foo/dateMysql2Cal.php';
require_once 'foo/getStageArray.php';
require_once 'foo/timeAdd.php';
require_once 'foo/timesheets/feeEstimate.php';
/*
+-------------------------------------------------------+
| Fn :: getProjectManager                               |
+-------------------------------------------------------+

function getProjectManager ($pid, $mysqli) {

    $query = "select
                t2.loginname,
                t2.fullname
            from
                roleinproject as t1,
                users as t2
            where
                t1.roles_id = 10 and
                t1.user_id = t2.id and
                t1.project_id = $pid";

    if ($result = $mysqli->query($query)) {

        $row 		= $result->fetch_row();
        $teamleader 	= $row[1];
        $loginname_tm 	= $row[0];

        $result->close();
    }

    return $teamleader;
}
*/




/*
+-------------------------------------------------------+
| Fn :: signOffStatus                                   |
+-------------------------------------------------------+
*/
function signOffStatus($pid, $roleId, $mysqli)
{

    $query = "select projectstatus_id, signoffdt from projects where id = " . $pid;

    if ($result = $mysqli->query($query)) {

        $row    = $result->fetch_row();
        $flag   = $row[0];
        $dt     = $row[1];

        $result->close();
    }

    // Sign-Off date formatting
    if ($dt !== '0000-00-00') {
        $signOffDate = 'Date: ' . dateMysql2Cal($dt);
    } else {
        $signOffDate = '&nbsp;';
    }

    // Sign-Off Status and Buttons
    if ($flag < 2) {
        $tag1 = 'Pending';
        // Only PM has this button
        if ($roleId <= 12) {
            $tag2 = '<span class="button" onclick="javascript:contractSignOffFlag()">
                        Flag as Signed Off
                    </span>';
        } else {
            $tag2 = '&nbsp;';
        }
    } else {
        $tag1 = '&nbsp;';
        $tag2 = '<span class="button" onclick="javascript:contractSignOffFlag()">Edit</span>';
    }

    // Generate HTML
    echo '<table class="tabulation" cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr id="signOffRow" style="height: 35px">
                <!-- <td id="signOffStatus" width="10%">' . $tag1 . '</td> -->
                <td id="signOffDate" width="55%">' . $signOffDate . '</td>
                <td id="signOffButton" style="text-align: right;">' . $tag2 . '&nbsp;</td>
            </tr>
        </table>';
}
/*
+-------------------------------------------------------+
| Fn ::                                                 |
+-------------------------------------------------------+
*/
function feeEstimator($pid, $mysqli)
{

    $flag = timeEstimateFlag($pid, $mysqli);

    if ($flag > 0) {
        $tag1 = '<img src="/da/tick.png" alt="Yes">';
    } else {
        $tag1 = 'To be uploaded.';
    }

    echo '<table class="tabulation" cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr style="height: 35px">
                <td width="100%">' . $tag1 . '</td>
            </tr>
        </table>';
}
/*
+-------------------------------------------------------+
| Fn ::                                                 |
+-------------------------------------------------------+
*/
function deliverableList($pid, $stageX, $mysqli)
{

    $co_stageX      = count($stageX);
    $headerColor    = 'background: whitesmoke;';

    // Get the data
    $query = 'select
                newstage as stageId,
                r0issuedflag
            from
                dwglist
            where
                project_id = ' . $pid . ' and
                active = 1';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $listX[] = array("stageId" => $row[0], "r0flag" => $row[1]);
        }

        $result->close();
    }

    $co_listX = isset($listX) ? count($listX) : 0;



    /* HEADER */
    $currentStageX  = getCurrentProjectStage($pid, $mysqli);
    $currentStage   = $currentStageX["stageId"] + 0;

    echo '<table class="tabulation" cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr class="headerRow" style="height: 35px">
                <td class="headerRowCell2" width="175px" style="border-top:0;border-left:0;' . $headerColor . '">&nbsp;Milestone</td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $sid = $stageX[$n]["id"] + 0;

        if ($currentStage === $sid) {
            $bgcolor = 'background: #fecd61';
        } else {
            $bgcolor = $headerColor;
        }
        echo '<td class="headerRowCell2" width="85px" title="' . $stageX[$n]["stage"] . '" style="text-align:center;border-top:0;cursor:pointer;' . $bgcolor . '">' . $stageX[$n]["sname"] . '</td>';
    }

    echo '<td class="headerRowCell2" style="text-align: center; border-top: 0; border-right: 0;' . $headerColor . '">Total</td></tr>';

    /* DATA ROW :: Target Dates for these milestones */
    $n = 0;
    echo '<tr class="dataRow" style="height: 35px;border-left:0;">
            <td class="dataRowCell2">
                &nbsp;Target Date
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"];
        $tdt        = getStageTargetDate($pid, $stageId, $mysqli);

        echo '<td class="dataRowCell2" style="text-align:center;">' . $tdt . '</td>';
    }

    echo '<td class="dataRowCell2" style="border-right:0;">&nbsp;</td></tr>';

    /* DATA ROW */
    // Generate Row :: Total Deliverables
    echo '<tr class="dataRow" style="height: 35px">
            <td class="dataRowCell2" style="border-left:0;">&nbsp;Deliverables Total</td>';

    // Totals column
    $totalNo    = 0;

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"] + 0;
        $stageNo    = $stageX[$n]["stageno"] + 0;
        $no         = 0;

        for ($s = 0; $s < $co_listX; $s++) {

            $thisStageId = $listX[$s]["stageId"] + 0;

            if ($stageNo === $thisStageId) {
                $no = $no + 1;
            }
        }

        $totalNo = $totalNo + $no;

        if ($no < 1) $no = '&nbsp;';
        echo '<td class="dataRowCell2" style="text-align:center">' . $no . '</td>';
    }

    if ($totalNo < 1) $totalNo = '&nbsp;';
    echo '<td class="dataRowCell2" style="border-right: 0;text-align: center;">' . $totalNo . '</td></tr>';

    // Store Total Deliverables for percentage calculations
    $totalDeliverablesCount = $totalNo;

    // Generate Row :: Released Drawings
    echo '<tr class="dataRow" style="height: 35px">
            <td class="dataRowCell2" style="border-left:0;">
                &nbsp;Released
            </td>';

    // Totals column
    $totalNo = 0;

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"] + 0;
        $stageNo    = $stageX[$n]["stageno"] + 0;
        $no         = 0;

        for ($s = 0; $s < $co_listX; $s++) {

            $thisStageId = $listX[$s]["stageId"] + 0;

            if ($stageNo === $thisStageId && $listX[$s]["r0flag"] > 0) {
                $no = $no + 1;
            }
        }

        $totalNo = $totalNo + $no;

        if ($no < 1) $no = '&nbsp;';
        echo '<td class="dataRowCell2" style="text-align:center;">' . $no . '</td>';
    }

    // Totals Column
    if ($totalNo < 1) {

        echo '<td class="dataRowCell2" style="border-right: 0">&nbsp;</td>';
    } else {

        // Percentage completed
        $px = round((($totalNo * 100) / $totalDeliverablesCount), 2);

        echo '<td class="dataRowCell2" style="border-right: 0;text-align: center;">'
            . $totalNo .
            '<br><span style="color:RGB(100,100,100)">'
            . $px .
            ' %</span></td>';
    }

    echo '</tr>';


    // Generate Row :: Pending Drawings
    echo '<tr class="dataRow" style="height: 35px">
            <td class="dataRowCell2" style="border-left:0;border-bottom:0;">
                &nbsp;Pending
            </td>';

    // Totals column
    $totalNo = 0;
    $n = 0;
    $s = 0;

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"] + 0;
        $stageNo    = $stageX[$n]["stageno"] + 0;
        $no         = 0;

        for ($s = 0; $s < $co_listX; $s++) {

            $thisStageId = $listX[$s]["stageId"] + 0;

            if ($stageNo === $thisStageId && $listX[$s]["r0flag"] < 1) {
                $no = $no + 1;
            }
        }

        $totalNo = $totalNo + $no;

        if ($no < 1) $no = '&nbsp;';
        echo '<td class="dataRowCell2" style="text-align:center;border-bottom:0;">' . $no . '</td>';
    }

    if ($totalNo < 1) {
        $totalNo = '<td class="dataRowCell2" style="border-right: 0;text-align: center; border-bottom: 0;">&nbsp;</td>';
    } else {
        $px = (100 - $px);
        echo '<td class="dataRowCell2" style="border-right: 0;text-align: center; border-bottom: 0;">'
            . $totalNo .
            '<br><span style="color:RGB(100,100,100)">'
            . $px .
            '%</span></td></tr>';
    }
    // Close Table
    echo '</table>';
}

/*
+-------------------------------------------------------+
| Fn ::                                                 |
+-------------------------------------------------------+
*/
function manhoursStatus($pid, $stageX, $mysqli)
{

    $co_stageX = count($stageX);
    $totalManhours = 0;
    echo '<table class="tabulation" border="0" cellpadding="0" cellspacing="0" width="100%">';

    /* DATA ROW :: Estimated */
    echo '<tr class="dataRow" style="height: 35px">
            <td width="50px" class="dataRowCell2" style="border-top:0;border-left:0;" rowspan="2">Time</td>
            <td width="150px" class="dataRowCell2" style="border-top:0;border-left:0;">
                &nbsp;Estimated (Hours)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId            = $stageX[$n]["id"];
        $estimatedManhours  = manhourEstimation4Stage($pid, $stageId, $mysqli);
        $totalManhours      = $totalManhours + $estimatedManhours;

        echo '<td class="dataRowCell2" width="58px" style="border-top:0;border-left:0;text-align:center;">' . $estimatedManhours . '</td>';
    }

    echo '<td class="dataRowCell2" style="border-right:0;text-align:center;">' . $totalManhours . '</td></tr>';

    /* DATA ROW :: Timesheets */
    $query  = "select
                    projectstage_id,
                    no_of_hours,
                    no_of_min
                from
                    timesheet
                where
                    project_id = $pid and
                    quality < 1 and
                    active = 1";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $tmX[] = array("stageId" => $row[0], "h" => $row[1], "m" => $row[2]);
        }

        $result->close();
    }

    $co_tmX = count($tmX);
    //echo "co_tmX: ".$co_tmX;

    $totalH = 0;
    $totalM = 0;

    echo '<tr class="dataRow" style="height: 35px;border-left:0;">
            <td class="dataRowCell2">
                &nbsp;Timesheets (HH:MM)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $thisStageId = $stageX[$n]["id"] + 0;
        $h = 0;
        $m = 0;

        for ($z = 0; $z < $co_tmX; $z++) {

            $sid = $tmX[$z]["stageId"] + 0;

            if ($sid === $thisStageId) {

                $h = $h + $tmX[$z]["h"];
                $m = $m + $tmX[$z]["m"];
            }
        }
        // Add to column totals
        $totalH = $totalH + $h;
        $totalM = $totalM + $m;

        echo '<td class="dataRowCell2" style="text-align:center;">' . timeAdd($h, $m) . '</td>';
    }

    echo '<td class="dataRowCell2" style="border-right:0;text-align:center;">' . timeadd($totalH, $totalM) . '</td></tr>';


    /* DATA ROW :: Budgeted Cost */
    // If FeeCalculator is not uploaded -> 0 are displayed (consious decision to display 0s).
    $totalCost      = 0;

    echo '<tr class="dataRow" style="height: 35px;border-left:0;">
            <td class="dataRowCell2" rowspan="2" style="border-bottom: 0;">Money</td>
            <td class="dataRowCell2">
                &nbsp;<!--Cumulative -->Budget (Rs.)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"] + 0;
        // stageEstimatedCost function from w3etc/foo/timesheets/feeEstimate.php
        $cost       = stageEstimatedCost($pid, $stageId, $mysqli);

        // Add to column totals
        $totalCost = $totalCost + $cost;

        // If you want to show stage cost replace $totalCost with $cost.
        echo '<td class="dataRowCell2" style="text-align:center;">' . $cost . '</td>';
    }

    echo '<td class="dataRowCell2" style="border-right:0;text-align:center;">' . $totalCost . '</td></tr>';
    // Save $totalCost for calculating percentage later
    $totalBudgetedCost = $totalCost;


    /* DATA ROW :: Accrued Cost */
    $totalCost = 0;

    echo '<tr class="dataRow" style="height: 35px;border-left:0;">
            <td class="dataRowCell2" style="border-bottom: 0;">
                &nbsp;Accrued (Rs.)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId    = $stageX[$n]["id"] + 0;
        $cost       = stageAccruedCost($pid, $stageId, $mysqli);

        // Add to column totals
        $totalCost = $totalCost + $cost;

        if ($cost < 1) {
            echo '<td class="dataRowCell2" style="border-bottom:0;text-align:center;">&nbsp;</td>';
        } else {
            // If you want to show stage cost replace $totalCost with $cost.
            echo '<td class="dataRowCell2" style="border-bottom:0;text-align:center;">' . $cost . '</td>';
        }
    }

    // Accrued :: Totals Cell Formatting
    if ($totalCost < 1) {
        $totalCell = '&nbsp;';
    } else {
        if ($totalBudgetedCost < 1) {
            $totalCell = $totalCost;
        } else {
            $px = round((($totalCost * 100) / $totalBudgetedCost), 2);
            $totalCell = $totalCost . '<br><span style="color:RGB(100,100,100);>' . $px . ' %</span>';
        }
    }

    echo '<td class="dataRowCell2" style="border-right:0;border-bottom:0;text-align:center;">' . $totalCell . '</td></tr>';


    echo '<table>';
}


/*
+-------------------------------------------------------+
| Fn ::                                                 |
+-------------------------------------------------------+
*/
function manhourEstimation4Stage($pid, $stageId, $mysqli)
{

    $manhours = 0;

    $query = 'select
                manhours
            from
                timeestimate
            where
                project_id  = ' . $pid . ' and
                stage_id    = ' . $stageId . ' and
                active      = 1';

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_row()) {
            $manhours = $manhours + $row[0];
        }
        $result->close();
    }

    return $manhours;
}

/*
+-------------------------------------------------------+
| Fn :: Custom for Abhikalpan                           |
+-------------------------------------------------------+
*/
function manhoursStatusShort($pid, $stageX, $role_id, $mysqli)
{

    $co_stageX = count($stageX);
    $totalManhours = 0;
    echo '<table class="tabulation" border="0" cellpadding="0" cellspacing="0" width="100%">';

    /* DATA ROW :: Estimated */
    echo '<tr class="dataRow" style="height: 35px">
            <td class="dataRowCell2" width="175px" style="border-top:0;border-left:0;">
                &nbsp;Estimated (Hours)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $stageId            = $stageX[$n]["id"];
        $estimatedManhours  = manhourEstimation4Stage($pid, $stageId, $mysqli);
        $totalManhours      = $totalManhours + $estimatedManhours;

        if ($estimatedManhours < 1) $estimatedManhours = "<!-- NA -->";

        echo '<td class="dataRowCell2" width="85px" style="border-top:0;border-left:0;text-align:center;">' . $estimatedManhours . '</td>';
    }

    echo '<td class="dataRowCell2" style="border-right:0;text-align:center;">' . $totalManhours . '</td></tr>';

    /* DATA ROW :: Timesheets */
    $query  = "select
                    projectstage_id,
                    no_of_hours,
                    no_of_min
                from
                    timesheet
                where
                    project_id = $pid and
                    quality < 1 and
                    active = 1";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $tmX[] = array("stageId" => $row[0], "h" => $row[1], "m" => $row[2]);
        }

        $result->close();
    }

    $co_tmX = count($tmX);
    //echo "co_tmX: ".$co_tmX;

    $totalH = 0;
    $totalM = 0;

    echo '<tr class="dataRow" style="height: 35px;border-left:0;">
            <td class="dataRowCell2" style="border-bottom:0;">
                &nbsp;Timesheets (HH:MM)
            </td>';

    for ($n = 0; $n < $co_stageX; $n++) {

        $thisStageId = $stageX[$n]["id"] + 0;
        $h = 0;
        $m = 0;

        for ($z = 0; $z < $co_tmX; $z++) {

            $sid = $tmX[$z]["stageId"] + 0;

            if ($sid === $thisStageId) {

                $h = $h + $tmX[$z]["h"];
                $m = $m + $tmX[$z]["m"];
            }
        }
        // Add to column totals
        $totalH = $totalH + $h;
        $totalM = $totalM + $m;

        $totalEstimatedTime = timeAdd($h, $m);
        // if ($totalEstimatedTime < 1) $totalEstimatedTime = "<!-- NA -->";

        if ($role_id < 14) {
            echo '<td class="dataRowCell2a" style="text-align:center;border-bottom:0;">
                    <a href="project.cgi?a=t1xsnapshot-tm-milestone&sid=' . $thisStageId . '">
                        ' . $totalEstimatedTime . '
                    </a>
                </td>';
        } else {
            echo '<td class="dataRowCell2" style="text-align:center;border-bottom:0;">
                    ' . $totalEstimatedTime . '
                </td>';
        }
    }

    echo '<td class="dataRowCell2" style="border-right:0;text-align:center;border-bottom:0;">' . timeadd($totalH, $totalM) . '</td>';

    echo '</tr>
        <table>';
}


/*
+-------------------------------------------------------+
| manhoursStatusWithScope                               |
+-------------------------------------------------------+
*/
function manhoursStatusWithScope($projectid, $scopeX, $stageX, $mysqli)
{
    array_push($scopeX, [
        'id' => 1,
        'scope' => 'NA',
        'description' => 'NA',
        'displayorder' => 1,
        'active' => 0,
        'scope_with_id' => 'NA'
    ]);

    // var_dump($scopeX);
    // var_dump($stageX);

    $query  = "select
                    `projectscope_id` as `scopeid`,
                    `projectstage_id` as `stageid`,
                    sum(`no_of_hours`) as `h`,
                    sum(`no_of_min`) as `m`
                from
                    `timesheet`
                where
                    `project_id` = '$projectid' and
                    `quality` < 1 and
                    `active` > 0
                group by
                    `scopeid`,
                    `stageid`";

    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $timeSheetSum[$row['scopeid']][$row['stageid']] = [
            'mh' => bdAddHourMin($row['h'], $row['m']),
            'h' => $row['h'],
            'm' => $row['m'],
        ];
    }

    // var_dump($timeSheetSum);

    # Generate table
?>
    <table class="tabulation" style="width: 100%;">

        <?php
        // Grand Total
        $gth = 0;
        $gtm = 0;
        
        foreach ($scopeX as $sc) :
            # Weed out empty scope
            if (isset($timeSheetSum[$sc['id']])) :
                
                # Scope Id
                $scId = $sc['id'];

                // Scope Total
                $th = 0;
                $tm = 0;
        ?>
                <tr class="dataRow">
                    <td class="dataRowCell2" style="width: 175px;">&nbsp;<?= $sc['description'] ?></td>
                    <?php
                    foreach ($stageX as $st) {

                        # Stage Id
                        $stId = $st['id'];

                        $hx = isset($timeSheetSum[$scId][$stId]['h'])? $timeSheetSum[$scId][$stId]['h']: 0;
                        $mx = isset($timeSheetSum[$scId][$stId]['m'])? $timeSheetSum[$scId][$stId]['m']: 0;

                        // Scope total
                        $th += $hx;
                        $tm += $mx;

                        // Stage total
                        $stx[$stId]['h'] = isset($stx[$stId]['h']) ? $stx[$stId]['h'] + $hx: $hx;
                        $stx[$stId]['m'] = isset($stx[$stId]['m']) ? $stx[$stId]['m'] + $mx: $mx;

                        echo '<td class="dataRowCell2" style="width:85px;text-align:center;">' . $timeSheetSum[$scId][$stId]['mh'] . '</td>';
                    }
                    $gth += $th;
                    $gtm += $tm;
                    ?>
                    <td class="dataRowCell2" style="border-right:0px;;text-align:center;"><?= bdAddHourMin($th, $tm) ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="dataRow">
            <td class="dataRowCell2" style="border-bottom:0px;">&nbsp;Total</td>
            <?php
            // Total check
            $tch = 0;
            $tcm = 0;

            foreach ($stageX as $s) {
                $stId = $s['id'];

                $tch += $stx[$stId]['h'];
                $tcm += $stx[$stId]['m'];

                echo '<td class="dataRowCell2a" style="text-align:center;border-bottom:0px;">
                        <a href="project.cgi?a=t1xsnapshot-tm-milestone&sid=' . $stId . '">'
                         . bdAddHourMin($stx[$stId]['h'], $stx[$stId]['m']) . 
                        '</a>
                    </td>';
            }
            ?>
            <td style="font-weight:bold;text-align:center;border-bottom:0px;">
                <!-- <?= bdAddHourMin($gth, $gtm) ?> -->
                <div><?= bdAddHourMin($tch,$tcm) ?></div>
            </td>
        </tr>
    </table>
<?php
}

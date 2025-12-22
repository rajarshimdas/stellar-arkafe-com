<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 20-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Date range selection form                             |
+-------------------------------------------------------+
*/
$formTitle = 'Task Statistics';

echo '<div class="task-table-form">';
require BD . 'View/Module/setDateRange.php';
echo '</div>';
/*
+-------------------------------------------------------+
| Fetch data                                            |
+-------------------------------------------------------+
*/
$userx  = bdGetUsersArray($mysqli);
$scopex = bdGetProjectScopeArray($mysqli);
$stagex = bdGetProjectStageArray($mysqli);

// var_dump($userx);
// var_dump($stagex);


/*
+-------------------------------------------------------+
| Time data - OVERALL                                   |
+-------------------------------------------------------+
*/
$query = "select            
            `task_id` as `tid`,
            sum(`no_of_hours`) as `h`,
            sum(`no_of_min`) as `m`
        from
            `timesheet`
        where
            `project_id` = '$pid' and
            `active` > 0 and
            `quality` < 1
        group by
            `task_id`";

# echo $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $tkUtilizedMH[$row['tid']] = [
            bdAddHourMin($row['h'], $row['m']),
            (($row['h'] * 60) + $row['m'])
        ];
    }
}

/*
+-------------------------------------------------------+
| Time data - Current Month                             |
+-------------------------------------------------------+
*/
$cm = date("Y-m-01");
$query = "select            
            `task_id` as `tid`,
            sum(`no_of_hours`) as `h`,
            sum(`no_of_min`) as `m`
        from
            `timesheet`
        where
            `project_id` = '$pid' and
            `dt` >= '$cm' and
            `active` > 0 and
            `quality` < 1
        group by
            `task_id`";

# echo $query;

$gtCMutilizedm = 0;
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $tkCmUtilizedMH[$row['tid']] = bdAddHourMin($row['h'], $row['m']);
        $gtCMutilizedm += ($row['h'] * 60) + $row['m'];
    }
}


/*
+-------------------------------------------------------+
| Time data - Last Month                                |
+-------------------------------------------------------+
*/
$tkUtillm = bdTaskUtilizedMH($pid, $mysqli, "LM");

/*
+-------------------------------------------------------+
| Time data - Date Range                                |
+-------------------------------------------------------+
*/
$query = "select
            `user_id` as uid,
            `task_id` as tid,
            sum(`no_of_hours`) as h,
            sum(`no_of_min`) as m,
            max(`percent`) as percent
        from
            `timesheet`
        where
            `project_id` = '$pid' and
            `dt` >= '$fdt' and
            `dt` <= '$tdt' and
            `active` > 0 and
            `quality` < 1
        group by
            `user_id`, `task_id`";

# echo $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $userTs[$row['uid']][$row['tid']] = $row;
    };
    $result->close();
}

//var_dump($userTs);

/*
+-------------------------------------------------------+
| Deduce team                                           |
+-------------------------------------------------------+
*/
foreach ($userx as $ux) {

    // Header Row
    if (isset($userTs[$ux['user_id']])) {
        $teamX[] = [
            'uid'   => $ux['user_id'],
            'dname' => $ux['displayname'],
        ];

        // For Total MH
        $ut[$ux['user_id']] = [
            'h' => 0,
            'm' => 0,
        ];
    }

    // Data Row
    if (isset($userTs[$ux['user_id']])) {

        foreach ($userTs[$ux['user_id']] as $dX) {
            $tsDataX[$dX['tid']][$ux['user_id']] = [
                'mh' => bdAddHourMin($dX['h'], $dX['m']),
                'h' => $dX['h'],
                'm' => $dX['m'],
                'percent' => $dX['percent'],
            ];
        }
    }
}

// var_dump($teamX);
// var_dump($tsDataX);

/*
+-------------------------------------------------------+
| Tasks data                                            |
+-------------------------------------------------------+
*/
$query = "SELECT * FROM `view_tasks` where `project_id` = '$pid' order by `task_id` desc";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {

        # Tabulate | Weed out empty tasks
        if (isset($tsDataX[$row['task_id']]) || ($row['status_last_month'] < 100 && $row['onhold'] < 1 && $row['active'] > 0)) {

            # Table - Scope > Stage
            $tblTask[$row["scope_id"]][$row["stage_id"]][]  = $row;

            # for row count
            $co[$row["scope_id"]][] = 1;

            # for allotted manhours
            $tk[$row['task_id']] = [
                'alloted' => $row['manhours'] . ':' . $row['manminutes'],
                'target' => $row['status_this_month_target'],
                'completed' => $row['status_last_month'],
            ];
        }
    };
    $result->close();
}
?>

<table class="task-table">
    <thead>
        <tr>
            <td style="width: 30px;" rowspan="2">No</td>
            <td style="width: 60px;" rowspan="2">Scope</td>
            <td style="width: 60px;" rowspan="2">Milestone</td>
            <td style="min-width: 200px; text-align: left; padding-left: 5px;" rowspan="2">Work Description</td>
            <td class="tdDateRange" colspan="<?= empty($teamX) ? 1 : (count($teamX) + 1) ?>">Date Range</td>
            <!-- <td style="width: 80px;" rowspan="2">Completed %</td> -->
            <td style="background-color:lightgray;" colspan="4">Work Plan | <?= date("M") ?></td>
            <td style="width: 80px;" colspan="2">Total Manhours</td>
        </tr>
        <tr>
            <?php
            foreach ($teamX as $tmX) {
                echo '<td class="tdDateRange" style="width: 80px;">' . $tmX['dname'] . '</td>';
            }
            ?>
            <td class="tdDateRange" style="width: 80px;">Total Manhours</td>
            <td style="width: 80px; background-color:lightgray;border-left: 0px;">Completed</td>
            <td style="width: 80px; background-color:lightgray;">Target</td>

            <td style="width: 80px; background-color:lightgray;">Allotted<div style="color: red;">beta</div>
            </td>
            <td style="width: 80px; background-color:lightgray;">Utilized</td>

            <td style="width: 80px;">Alloted</td>
            <td style="width: 80px;">Utilized</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $srno = 1;

        // Grand total current month
        $gtCMm = 0;

        // Grand total allotted
        $gtAm = 0; // totalmin

        // Grand total Utilized
        $gtUh = 0;
        $gtUm = 0;

        for ($x = 0; $x < count($scopex); $x++) {

            # Scope
            $sc             = $scopex[$x];
            $scope_id       = $sc["id"];
            $flag1r_scope   = 0;

            $scope_rows     = isset($co[$scope_id]) ? count($co[$scope_id]) : 0;

            for ($n = 0; ($n < count($stagex) && $scope_rows > 0); $n++) {

                # Stage/Milestone
                $st              = $stagex[$n];
                $stage_id       = $st["id"];
                $flag1r_stage   = 0;

                # Tasks array 
                $t      = $tblTask[$scope_id][$stage_id];
                $no1    = isset($t) ? count($t) : 0;

                for ($i = 0; $i < $no1; $i++) {

                    # Task
                    $tx         = $t[$i];
                    $task_id    = $tx["task_id"];
                    // echo "<div>ScopeId: $scope_id | stageId: $stage_id | taskId: $task_id</div>";

                    // Generate Task Row 1st line
                    if ($flag1r_scope < 1 && $flag1r_stage < 1) {

                        echo '<tr>
                                <td rowspan="' . $scope_rows . '">' . $srno++ . '</td>
                                <td rowspan="' . $scope_rows . '">' . $tx["scope_sn"] . '</td>
                                <td rowspan="' . $no1 . '">' . $tx["stage_sn"] . '</td>';
                        $flag1r_scope++;
                        $flag1r_stage++;
                    } else if ($flag1r_stage < 1) {

                        echo '<tr>
                                <td rowspan="' . $no1 . '">' . $tx["stage_sn"] . '</td>';
                        $flag1r_stage++;
                    } else {

                        echo '<tr>';
                    }

                    // Generate Task Row
                    echo '<td style="text-align:left;vertical-align:middle;"><div class="taskDiv">' . $tx["work"] . '</div></td>';

                    $th = 0;
                    $tm = 0;
                    $px = 0;

                    foreach ($teamX as $t5X) {

                        $mh = (isset($tsDataX[$task_id][$t5X['uid']])) ? $tsDataX[$task_id][$t5X['uid']]['mh'] : '0';

                        // Manhours
                        echo '<td>' . $mh . '</td>';

                        if ($mh != '0') {

                            // Percent 
                            if ($tsDataX[$task_id][$t5X['uid']]['percent'] > $px) $px = $tsDataX[$task_id][$t5X['uid']]['percent'];

                            // Row total
                            $th = $th + $tsDataX[$task_id][$t5X['uid']]['h'];
                            $tm = $tm + $tsDataX[$task_id][$t5X['uid']]['m'];

                            // Column total
                            $ut[$t5X['uid']]['h'] = $ut[$t5X['uid']]['h'] + $tsDataX[$task_id][$t5X['uid']]['h'];
                            $ut[$t5X['uid']]['m'] = $ut[$t5X['uid']]['m'] + $tsDataX[$task_id][$t5X['uid']]['m'];

                            // Grand total Utilized 
                            $gtUh = $gtUh + $tsDataX[$task_id][$t5X['uid']]['h'];
                            $gtUm = $gtUm + $tsDataX[$task_id][$t5X['uid']]['m'];
                        }
                    }
                    // Completed % | Removed Sept 25
                    # echo '<td>' . $px . '%</td>';

                    // Total MH for date range
                    echo '<td>' . bdAddHourMin($th, $tm) . '</td>';

                    // Work Plan | Completed %
                    echo '<td>' . $tk[$task_id]['completed'] . '%</td>';
                    // Work Plan | Target %
                    echo '<td>' . $tk[$task_id]['target'] . '%</td>';


                    // Work Plan | Allotted 
                    // Current Month added MH | Weed out last month data
                    $cm_added_mh = ($tx['cm_date_flag'] < $cm) ? 0 : $tx['cm_added_mh'];

                    $gtCMm += $cm_added_mh;
                    echo "<td>" . bdMinutes2Manhours($cm_added_mh) . "</td>";

                    // Work Plan | Utilized 
                    $cmh = empty($tkCmUtilizedMH[$task_id]) ? '0:0' : $tkCmUtilizedMH[$task_id];
                    echo "<td>" . $cmh . "</td>";


                    // Total | Alloted mh

                    // Total Allotted = Last month manhours + Current month added manhours
                    $tk0 = empty($tkUtillm[$task_id]) ? $tkUtillm[0] : $tkUtillm[$task_id]; // Last Month
                    $totalAllotted = (int)$tk0['totalmin'] + (int)$cm_added_mh;


                    $gtAm += $totalAllotted;
                    echo '<td style="font-weight:bold;">' . bdMinutes2Manhours($totalAllotted) . '</td>';

                    // Total | Utilized mh
                    $amh = empty($tkUtilizedMH[$task_id]) ? '0:0' : $tkUtilizedMH[$task_id][0];
                    $cx = ((empty($tkUtilizedMH[$task_id]) ? 0 : $tkUtilizedMH[$task_id][1]) > $tx['cm_allotted_mh']) ? 'red' : 'blue';
                    echo '<td style="color:' . $cx . ';">' . $amh . '</td>';

                    echo '</tr>';
                }
            }
            echo '</tr>';
        }

        ?>
        <tr style="font-weight: bold;">
            <td colspan="4" style="text-align: right;padding-right:5px;">Total</td>
            <?php
            // Total
            $dtRh = 0;
            $dtRm = 0;
            foreach ($teamX as $t6X) {
                $dtRh += $ut[$t6X['uid']]['h'];
                $dtRm += $ut[$t6X['uid']]['m'];
                echo '<td>' . bdAddHourMin($ut[$t6X['uid']]['h'], $ut[$t6X['uid']]['m']) . '</td>';
            }
            ?>
            <td><?= bdAddHourMin($dtRh, $dtRm) ?></td>
            <td colspan="2"></td>
            <td><?= bdMinutes2Manhours($gtCMm) ?></td>
            <td><?= bdMinutes2Manhours($gtCMutilizedm) ?></td>
            <td><?= bdMinutes2Manhours($gtAm) ?></td>
            <td><?= bdAddHourMin($gtUh, $gtUm) ?></td>
        </tr>
    </tbody>
</table>

<style>
    .dxTable tr td {
        border: 0px solid gray;
    }

    td.tdDateRange {
        background-color: #e5e599;
    }

    .error {
        border-color: red;
    }
</style>

<dialog id="dxTimesheet1">
    <table class='dxTable' style="width:450px;">
        <tr>
            <td style="font-weight: bold; color: var(--rd-nav-light);">
                Timesheet Add
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= BASE_URL ?>da/fa5/window-close.png" onclick="dxClose('dxTimesheet1')">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="taskCell" style="background-color: #e5e599; width: 100%; border-spacing:10px;">
                    <tr>
                        <td id="tkScope" style="width:50%;"></td>
                        <td id="tkStage" style="text-align:right;"></td>
                    </tr>
                    <tr>
                        <td id="tkWork" colspan="2" style="border: 1px solid gray; padding: 5px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: right;">Date</td>
                        <td colspan="3">
                            <input type="hidden" id="ts_date_cal" value="<?= $timesheetDateCal ?>">
                            <input type="date" id="ts_date" value="<?= $timesheetDate ?>" max="<?= date("Y-m-d") ?>" min="<?= $lockdt ?>" onchange="javascript:setActiveDate()">
                        </td>
                        <td id="dayMh" style="text-align: center; border: 2px solid white; background-color:cadetblue; color: white;">
                            <?= $dayManhours ?>
                        </td>
                        <td>
                            <img class="fa5button" src="<?= BASE_URL ?>da/fa5/eye.png" alt="view" onclick="javascript:viewDay()">
                        </td>
                    </tr>
                    <tr>

                        <input type="hidden" id="ts_task_id">

                        <td style="text-align: right;">Work</td>
                        <td style="width: 50px;">
                            <input type="number" id="ts_h" placeholder="hour" min="0" max="24">
                        </td>
                        <td style="width: 50px;">
                            <input type="number" id="ts_m" placeholder="min" min="0" max="59">
                        </td>
                        <td style="width: 50px;">
                            <input type="number" id="ts_p" placeholder="%" min="1" max="99">
                        </td>
                        <td style="width: 80px;">
                            <input type="button" value="Add" onclick="javascript:addTimesheet()">
                        </td>
                        <td></td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
</dialog>

<?php require_once BD . 'View/Module/Concert/timesheet/dxViewDay.php'; ?>

<script>
    let activeDayTimesheet
    let activeDate

    window.onload = (event) => {
        // console.log("page is fully loaded");
        activeDate = document.getElementById("ts_date").value
        // activeDayTimesheet = JSON.parse(document.getElementById("activeDayTimesheet").innerHTML)
    };

    function dxTimesheetAdd(task_id) {

        const tk = JSON.parse(document.getElementById("task").innerHTML)
        console.log(tk)

        // Formdata
        document.getElementById("ts_task_id").value = task_id

        // Dialog box
        document.getElementById("tkStage").innerHTML = tk[task_id]["stage"]
        document.getElementById("tkScope").innerHTML = tk[task_id]["scope"]
        document.getElementById("tkWork").innerHTML = tk[task_id]["work"]

        // Reset error class if any
        document.getElementById("ts_h").classList.remove("error")
        document.getElementById("ts_m").classList.remove("error")
        document.getElementById("ts_p").classList.remove("error")

        // Reset value entered previously if any
        document.getElementById("ts_h").value = ''
        document.getElementById("ts_m").value = ''
        document.getElementById("ts_p").value = ''

        dxShow('dxTimesheet1', "ts_h")
    }

    function addTimesheet() {

        const task_id = document.getElementById("ts_task_id").value
        console.log(task_id)

        // Data Validation
        const h = parseInt(document.getElementById("ts_h").value)
        const m = parseInt(document.getElementById("ts_m").value)
        const p = parseInt(document.getElementById("ts_p").value)

        if (h < 1 && m < 1) {

            document.getElementById("ts_h").classList.add("error")
            document.getElementById("ts_m").classList.add("error")
            console.log("Mahours is zero.")
            return false

        } else {

            document.getElementById("ts_h").classList.remove("error")
            document.getElementById("ts_m").classList.remove("error")

        }

        if (p < 1 || p > 100) {
            document.getElementById("ts_p").classList.add("error")
            console.log('Percent validation failed. Value: ' + p)
            return false
        } else {
            document.getElementById("ts_p").classList.remove("error")
        }

        const tk = JSON.parse(document.getElementById("task").innerHTML)

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        const dt = document.getElementById("ts_date").value

        var formData = new FormData()
        formData.append("a", "concert-api-addTimesheet")
        formData.append("task_id", task_id)
        formData.append("date", document.getElementById("ts_date").value)
        formData.append("h", document.getElementById("ts_h").value)
        formData.append("m", document.getElementById("ts_m").value)
        formData.append("percent", document.getElementById("ts_p").value)

        formData.append("project_id", tk[task_id]["project_id"])
        formData.append("scope_id", tk[task_id]["scope_id"])
        formData.append("stage_id", tk[task_id]["stage_id"])
        formData.append("work", tk[task_id]["work"])

        // console.log(formData)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response[0]);
            if (response[0] == "T") {
                setActiveDate(dt)

                document.getElementById("pe_" + task_id).innerHTML = response[1]
                document.getElementById("mh_" + task_id).innerHTML = response[2]

                dxClose("dxTimesheet1")
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }
</script>
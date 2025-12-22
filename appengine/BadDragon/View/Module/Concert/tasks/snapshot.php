<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 08-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once __DIR__ . "/snapshot-fn.php";
require_once __DIR__ . "/snapshot-js.php";

/*
+-------------------------------------------------------+
| Calander                                              |
+-------------------------------------------------------+
*/
$y = date("Y");
$m = date('m');
$mo_current = date('M');
$mo_previous = date('M', mktime(0, 15, 0, $m, -1, $y));
$mo_first = $y . '-' . $m . '-01';
// echo $mo_first;
$cm = date("Y-m-01");

/*
+-------------------------------------------------------+
| taskFilterNo                                          |
+-------------------------------------------------------+
*/
// rx($route);
$x = isset($route->parts[4]) ? $route->parts[4] : "F";

// taskFilterNo
if ($x == 'fx') {
    // 1. Check URI
    $taskFilterNo = isset($route->parts[5]) ? $route->parts[5] : 2;
} else {
    // 2. Check Session
    $taskFilterNo = isset($_SESSION["taskFilterNo"]) ? $_SESSION["taskFilterNo"] : 2;
}

// Set session
$_SESSION["taskFilterNo"] = $taskFilterNo;

/*
+-------------------------------------------------------+
| Scope and Milestone                                   |
+-------------------------------------------------------+
*/
$scope = bdGetProjectScopeArray($mysqli);
$co_scope = count($scope);
// var_dump($scope);

$stage = bdGetProjectStageArray($mysqli);
$co_stage = count($stage);
// var_dump($stage);


/*
+-------------------------------------------------------+
| Filters                                               |
+-------------------------------------------------------+
*/
// echo "taskFilterNo: $taskFilterNo";

switch ($taskFilterNo) {
    case 2:
        // echo "Active";
        $fx = "`active` > 0 and `status_last_month` < 100 and `onhold` < 1";
        break;
    case 3:
        //echo "Completed";
        $fx = "`active` > 0 and `status_last_month` = 100";
        break;
    case 4:
        // Onhold
        $fx = "`active` > 0 and `onhold` = 1";
        break;
    default:
        //echo "All";
        $fx = "`active` > 0";
}


/*
+-------------------------------------------------------+
| Tasks                                                 |
+-------------------------------------------------------+
*/
$query = "SELECT * FROM `view_tasks` where `project_id` = '$pid' and $fx order by `task_id` desc";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $task[$row['scope_id']][$row['stage_id']][] = $row;
    $taskById[$row['task_id']] = $row;
}

//var_dump($task);


/*
+-------------------------------------------------------+
| Tasks timesheet data                                  |
+-------------------------------------------------------+
*/
$tkUtilam = bdTaskUtilizedMH($pid, $mysqli, "All");
$tkUtilcm = bdTaskUtilizedMH($pid, $mysqli, "CM");
$tkUtillm = bdTaskUtilizedMH($pid, $mysqli, "LM");

?>

<style>
    table.btn tr td {
        border: 0px solid red;
        line-height: 0px;
        vertical-align: middle;
        height: 35px;
        padding: 0px 2px;
        margin: 0px;
    }
</style>


<table class="task-table">
    <thead>
        <tr class="header">
            <td rowspan="2" style="width: 30px;">No</td>
            <td rowspan="2" style="width: 60px;">Scope</td>
            <td rowspan="2" style="width: 60px;">Milestone</td>
            <td rowspan="2" style="text-align: left;padding-left:5px;">Work Description</td>
            <td rowspan="2" style="width: 60px;">Created</td>
            <td rowspan="2" style="width: 80px;">
                <div>Manhours</div><?= $mo_previous ?>
            </td>
            <td colspan="4"><?= 'Work Plan | ' . $mo_current ?></td>
            <td colspan="2">Total MH</td>
            <td rowspan="2" style="width: 160px;">
                <select name="fx" id="fx" style="width:140px;" onchange="go2a()">
                    <?php
                    $fx = ['x','All', 'Ongoing', 'Completed', 'On hold'];

                    echo '<option value="' . $taskFilterNo . '">' . $fx[$taskFilterNo] . '</option>';

                    for ($i = 1; $i < count($fx); $i++) {
                        if ($i != $taskFilterNo) echo '<option value="' . $i . '">' . $fx[$i] . '</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr class="header">

            <td style="width: 80px;">Completed</td>
            <td style="width: 80px;">Target</td>
            <td style="width: 80px;">Alloted MH<div style="color: red;">beta</div>
            </td>
            <td style="width: 80px;">Utilized MH</td>

            <td style="width: 80px;">Alloted</td>
            <td style="width: 80px;">Utilized</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $sno = 1;
        // Calculate rowspans
        for ($i = 0; $i < $co_scope; $i++) {

            $s = $scope[$i];
            $s_id = $s['id'];

            // Set/Reset Rowspan for scope
            $rs_scope = 0;

            for ($n = 0; $n < $co_stage; $n++) {

                $m = $stage[$n];
                $m_id = $m['id'];

                // Rowspans
                $rs_stage = isset($task[$s_id][$m_id]) ? count($task[$s_id][$m_id]) : 0;
                $rs_scope = $rs_scope + $rs_stage;

                //echo '<br>M:' . $m['id'] . ' | ' . $m['stage_sn'] . ': ' . $rs_stage;
            }

            //echo '<br>' . $s['id'] . ' | ' . $s['scope'] . ': ' . $rs_scope;
            if ($rs_scope > 0) {

                $flag_tr = 0;

                $t = "<tr>
                        <td rowspan='$rs_scope'>" . $sno++ . "</td>
                        <td rowspan='$rs_scope'>" . $s['scope'] . "</td>";

                for ($x = 0; $x < $co_stage; $x++) {

                    $m = $stage[$x];
                    $m_id = $m['id'];
                    $m_sn = $m['stage_sn'];
                    $flag = 0;

                    $co_task = isset($task[$s_id][$m_id]) ? count($task[$s_id][$m_id]) : 0;

                    for ($e = 0; $e < $co_task; $e++) {

                        $tx = $task[$s_id][$m_id][$e]; // This task
                        //var_dump($tx);die;
                        $task_id = $tx['task_id'];


                        // Task color
                        $taskColor = ';color:var(--rd-dark-gray);';
                        $taskColor = ($mo_first > $tx['dt']) ? 'color:blue;' : $taskColor;
                        $taskColor = ($tx["status_last_month"] > 99) ? 'color:green;' : $taskColor;
                        $taskColor = ($tx["onhold"] > 0) ? 'color:red;' : $taskColor;


                        // Accrued Manhours & Percent
                        /*
                        $tk1 = bdTaskStats($task_id, 1, 0, $mysqli); // Current
                        $tk0 = bdTaskStats($task_id, 0, 0, $mysqli); // Last month
                        */
                        $tk2 = empty($tkUtilcm[$task_id]) ? $tkUtilcm[0] : $tkUtilcm[$task_id]; // Current month
                        $tk1 = empty($tkUtilam[$task_id]) ? $tkUtilam[0] : $tkUtilam[$task_id]; // All
                        $tk0 = empty($tkUtillm[$task_id]) ? $tkUtillm[0] : $tkUtillm[$task_id]; // Last Month

                        $minAlloted = ($tx["manhours"] * 60) + $tx["manminutes"];
                        $minAccrued = minAccrued($tk1["manhours"]);

                        # Store this data in $taskTsData for Javascript array
                        # Date: 2 Jun 2025
                        #
                        $taskTsData[$task_id] = [
                            [
                                'manhours' => $tx['manhours'] . ':' . $tx['manminutes'],
                                'percent' => 'NA',
                                'totalmin' => $minAlloted,
                            ],
                            $tk0,
                            $tk1
                        ];

                        $color = ($minAccrued <= $minAlloted) ? 'green' : 'red';

                        if ($flag < 1) {
                            $t = $t . "<td rowspan='$co_task'>$m_sn</td>";
                            $flag++;
                        }

                        if ($rid < 14) {

                            $btnEdit = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/edit.png" alt="edit" onclick="javascript:dxEditTask(' . $task_id . ')">';

                            if ($tx["onhold"] < 1) {
                                $btnOnhold = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/taskHold.png" onclick="taskOnhold(\'' . $tx["task_id"] . '\', 1)" title="Set on hold">';
                            } else {
                                $btnOnhold = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/taskOnhold.png" onclick="taskOnhold(\'' . $tx["task_id"] . '\', 0)" title="Remove hold">';
                            }

                            $btnCompleted = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/taskComplete.png" onclick="taskCompleted(\'' . $tx["task_id"] . '\')" title="Completed">';

                            $btnDelete = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/delete.png" alt="delete" onclick="javascript:dxDeleteTask(' . $task_id . ')">';
                        }


                        // Current Month added MH | Weed out last month data
                        $cm_added_mh = ($tx['cm_date_flag'] < $cm) ? 0 : $tx['cm_added_mh'];

                        // Total Allotted = Last month manhours + Current month added manhours
                        $totalAllotted = (int)$tk0['totalmin'] + (int)$cm_added_mh;

                        $t = $t . "<td data-hover-group='group-$task_id' style='text-align:left;vertical-align:middle;'>
                                        <div id='wk_$task_id' class='taskDiv' style='$taskColor'>" . $tx['work'] . "</div>
                                    </td>
                                    
                                    <td data-hover-group='group-$task_id'>" . $tx["dt_month"] . "</td>
                                    
                                    <td data-hover-group='group-$task_id'>" . $tk0['manhours'] . "</td>
                                    <td id='taskStatus-$task_id' data-hover-group='group-$task_id'>" . $tx["status_last_month"] . "%</td>
                                    
                                    <td data-hover-group='group-$task_id'>" . $tx["status_this_month_target"] . "%</td>
                                    <td data-hover-group='group-$task_id'>" . bdMinutes2Manhours($cm_added_mh) . "</td>
                                    <td data-hover-group='group-$task_id'>" . $tk2['manhours'] . "</td>

                                    <td data-hover-group='group-$task_id'>" . bdMinutes2Manhours($totalAllotted) . "</td>
                                    
                                    <td data-hover-group='group-$task_id' style='font-weight:bold;color:$color;'>" . $tk1['manhours'] . "</td>
                                    
                                    <td id='tk-$task_id' data-hover-group='group-$task_id' style='padding:0px;text-align:center;'>
                                        <table class='btn'>
                                            <tr>
                                                <td>
                                                    <img class='fa5button' src='" . BASE_URL . "da/fa5/list.png' onclick=\"getTask('$task_id','" . $tx["work"] . "')\">
                                                </td>
                                                <td>$btnEdit</td>
                                                <td id='taskHold-$task_id'>$btnOnhold</td>
                                                <td>$btnCompleted</td>
                                                <td id='dx_$task_id'>$btnDelete</td>
                                            </tr>
                                        </table>
                                    </td>";


                        $t = $t . "</tr>";
                        $flag_tr++;
                    }
                }

                if ($flag_tr < 0) {
                    $t = $t . "</tr>";
                }

                echo $t;
            }
        }
        ?>

    </tbody>

</table>

<script>
    // ChatGPT | Select all cells with a hover group
    document.querySelectorAll('[data-hover-group]').forEach(cell => {
        const groupId = cell.getAttribute('data-hover-group');
        const groupCells = document.querySelectorAll(`[data-hover-group="${groupId}"]`);

        cell.addEventListener('mouseenter', () => {
            groupCells.forEach(c => c.style.backgroundColor = 'lightgray');
        });

        cell.addEventListener('mouseleave', () => {
            groupCells.forEach(c => c.style.backgroundColor = '');
        });
    });
</script>

<?php
// Caveat
$scopex = $scope;
$stagex = $stage;
require __DIR__ . "/edit-dialog-box.php";

// rx($taskTsData);

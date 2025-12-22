<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 20-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$dayManhours = bdGetDayManhours($timesheetDate, $user_id, $mysqli);
$dayTimesheet = bdGetDayTimesheet($timesheetDate, $user_id, $mysqli);

$scopex = bdGetProjectScopeArray($mysqli);
$stagex = bdGetProjectStageArray($mysqli);

// var_dump($stagex);

// Fetch my tasks
// $query = "SELECT * FROM `view_tasks_my` where `user_id` = '$user_id' and `project_id` = '$pid'";
$query = "SELECT 
                * 
            FROM 
                `view_tasks` 
            where 
                `project_id` = '$pid' and 
                `active` > '0' and
                `onhold` < '1' and
                `status_last_month` < '100'
            order by 
                task_id desc";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {

        # Tabulate
        $tblTask[$row["scope_id"]][$row["stage_id"]][]  = $row; // Table - Scope > Stage

        # for row count
        $co[$row["scope_id"]][] = 1;

        # Fill Timesheet form data
        $tk[$row["task_id"]] = $row; // JSON

    };
    $result->close();
}
?>

<!--
<pre>
<?php
//var_dump($tk);
//var_dump($tblTask);
?>
</pre>
-->

<table class="task-table">
    <thead>
        <tr class="header">
            <td style="width: 30px;">No</td>
            <td style="width: 60px;">Scope</td>
            <td style="width: 60px;">Milestone</td>
            <td style="text-align: left; padding-left:5px;">Work Description</td>
            <td style="width: 100px;">Completed %</td>
            <td style="width: 100px;">Manhours</td>
            <td style="width: 80px;">Timesheet</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $srno = 1;

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
                $t = isset($tblTask[$scope_id][$stage_id]) ? $tblTask[$scope_id][$stage_id] : [];
                $no1 = isset($t) ? count($t) : 0;

                for ($i = 0; $i < $no1; $i++) {

                    # Task
                    $tx         = $t[$i];
                    $task_id    = $tx["task_id"];
                    // echo "<div>ScopeId: $scope_id | stageId: $stage_id | taskId: $task_id</div>";

                    $txx = bdTaskStats($task_id, 1, $user_id, $mysqli);

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
                    echo '<td data-hover-group="t_'.$task_id.'" style="text-align:left;vertical-align:middle;"><div class="taskDiv">' . $tx["work"] . '</div></td>
                            <td data-hover-group="t_'.$task_id.'" class="task" id="pe_' . $task_id . '">' . $txx["percent"] . '%</td>
                            <td data-hover-group="t_'.$task_id.'" class="task" id="mh_' . $task_id . '">' . $txx["manhours"] . '</td>
                            <td data-hover-group="t_'.$task_id.'" class="task" valign="center">
                                <img style="vertical-align:middle;" src="' . BASE_URL . 'da/fa5/plus-square.png" alt="Add Timesheet" class="fa5button" onclick="javascript:dxTimesheetAdd(' . $task_id . ')">
                            </td>
                        </tr>';
                }
            }
        }

        ?>
    </tbody>
</table>

<style>
    .dxTable tr td {
        border: 0px solid gray;
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
    const tk = <?= json_encode($tk) ?>;

    let activeDayTimesheet = <?= json_encode($dayTimesheet) ?>;

    let activeDate = '<?= $timesheetDate ?>';

    let dxTaskId = 0;


    function dxTimesheetAdd(task_id) {

        // console.log(tk)

        // Set Variable
        dxTaskId = task_id

        // Dialog box
        e$("tkStage").innerHTML = tk[task_id]["stage"]
        e$("tkScope").innerHTML = tk[task_id]["scope"]
        e$("tkWork").innerHTML = tk[task_id]["work"]

        // Reset error class if any
        e$("ts_h").classList.remove("error")
        e$("ts_m").classList.remove("error")
        e$("ts_p").classList.remove("error")

        // Reset value entered previously if any
        e$("ts_h").value = ''
        e$("ts_m").value = ''
        e$("ts_p").value = ''

        dxShow('dxTimesheet1', "ts_h")
    }

    function addTimesheet() {

        let task_id = dxTaskId
        console.log('addTimesheet :: task_id: '+task_id)

        // Data Validation
        const h = (e$("ts_h").value) ? parseInt(e$("ts_h").value) : 0;
        const m = (e$("ts_m").value) ? parseInt(e$("ts_m").value) : 0;
        const p = (e$("ts_p").value) ? parseInt(e$("ts_p").value) : 0;

        if (h < 1 && m < 1) {

            e$("ts_h").classList.add("error")
            e$("ts_m").classList.add("error")
            console.log("Mahours is zero.")
            return false

        } else {

            e$("ts_h").classList.remove("error")
            e$("ts_m").classList.remove("error")

        }

        if (p < 1 || p > 100) {
            e$("ts_p").classList.add("error")
            console.log('Percent validation failed. Value: ' + p)
            return false
        } else {
            e$("ts_p").classList.remove("error")
        }

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        const dt = e$("ts_date").value

        var formData = new FormData()
        formData.append("a", "concert-api-addTimesheet")
        formData.append("task_id", task_id)
        formData.append("date", e$("ts_date").value)
        formData.append("h", e$("ts_h").value)
        formData.append("m", e$("ts_m").value)
        formData.append("percent", e$("ts_p").value)

        formData.append("project_id", tk[task_id]["project_id"])
        formData.append("scope_id", tk[task_id]["scope_id"])
        formData.append("stage_id", tk[task_id]["stage_id"])
        formData.append("work", tk[task_id]["work"])

        // console.log(formData)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response[0]);
            if (response[0] == "T") {
                setActiveDate(dt)

                e$("pe_" + task_id).innerHTML = response[1]
                e$("mh_" + task_id).innerHTML = response[2]

                dxClose("dxTimesheet1")
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }
</script>


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

<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 15-Apr-2025       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$dayManhours = bdGetDayManhours($timesheetDate, $user_id, $mysqli);
$dayTimesheet = bdGetDayTimesheet($timesheetDate, $user_id, $mysqli);
//rx($dayTimesheet);

$scopeX = bdGetProjectScopeArray($mysqli);
//rx($scopeX);

$scopeXflags = bdGetThisProjectScope($pid, $scopeX, $mysqli);
//rx($scopeXflags);
?>
<style>
    table#formWork {
        width: 100%;
        max-width: var(--rd-max-width);
        margin: auto;
        padding: 15px 0 25px 0;
    }

    table#formWork tr td {
        border: 1px none gray;
    }

    table#formWork tr td:first-child {
        text-align: right;
    }

    table.task-table tr td {
        vertical-align: middle;
        line-height: 1.2em;
    }

    table.task-table thead tr td {
        height: 40px;
    }

    div.tsW {
        font-family: "Roboto Bold";

        height: 1.2em;
        line-height: 1.2em;
        vertical-align: middle;
    }
</style>
<table id='formWork' class="task-table-form">
    <tr>
        <td></td>
        <td colspan="3" style="font-family:'Roboto Bold';text-align:center;line-height:40px;">
            <?= empty($pname) ? 'Select a Project' : $pname; ?>
        </td>
        <td></td>
    </tr>
    <tr>
        <td style="width: 450px;">Date:</td>
        <td style="width:220px;">
            <input type="date" id="ts_date" value="<?= $timesheetDate ?>" max="<?= date("Y-m-d") ?>" min="<?= $lockdt ?>" onchange="javascript:setActiveDate();window.location.reload();">
        </td>
        <td style="width: 120px;">
            <select id="ts_h">
                <option value="0">0 hour</option>
                <option value="1">1 hour</option>
                <option value='2'>2 hours</option>
                <option value='3'>3 hours</option>
                <option value='4'>4 hours</option>
                <option value='5'>5 hours</option>
                <option value='6'>6 hours</option>
                <option value='7'>7 hours</option>
                <option value='8'>8 hours</option>
                <option value='9'>9 hours</option>
                <option value='10'>10 hours</option>
            </select>
        </td>
        <td style="width: 120px;">
            <select id="ts_m">
                <option value="0">0 min</option>
                <option value="15">15 min</option>
                <option value="30">30 min</option>
                <option value="45">45 min</option>
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>Scope:</td>
        <td colspan="3">
            <select id="scopeId">
                <option value="0">-- Select --</option>
                <?php
                foreach ($scopeX as $s) {
                    $scopeId = $s['id'];
                    $scopeNm = $s['scope_with_id'];

                    if ($scopeXflags[$scopeId] == 'T'):
                ?>
                        <option value="<?= $scopeId ?>"><?= $scopeNm ?></option>
                <?php
                    endif;
                }
                ?>
            </select>
        </td>
        <td></td>
    </tr>

    <tr>
        <td>Milestone:</td>
        <td colspan="3">
            <!-- Project Stage -->
            <select id="stageId" onchange="onStageChange();">
                <option value='0'>-- Select --</option>
                <?php
                $stageX = bdGetProjectStageArray($mysqli);

                foreach ($stageX as $s) {
                    $stageId = $s['id'];
                    $stageNm = $s['stage_with_id'];

                    echo "<option value='$stageId'>$stageNm</option>";
                }
                ?>
            </select>
        </td>
        <td></td>
    </tr>

    <tr>
        <td>Task:</td>
        <td colspan="3">
            <select id="taskId">
                <option value='0'>++ Select Stage First ++</option>
            </select>
        </td>
        <td></td>
    </tr>

    <tr>
        <td>Work:</td>
        <td colspan="2">
            <input type='text' id="work" placeholder="Work done">
        </td>
        <td>
            <input type="number" id="percent" min="0" max="100" placeholder="%">
        </td>
        <td></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td colspan="3" style="text-align: center;">
            <button class="button" onclick="dxAddTimesheet()" style="width:150px;">Add</button>
        </td>
        <td></td>
    </tr>

</table>

<table class="task-table">
    <thead>
        <tr>
            <td width="18%" style="text-align:left;">Project</td>
            <td width="5%">Scope</td>
            <td width="7%">Milestone</td>
            <td style="text-align:left;">Work Description</td>
            <td width="7%">% Completed</td>
            <td width="9%">Hours<br>Worked</td>
            <!-- <td width="7%">Approval<br>Status</td> -->
            <td width="7%">Total<br>Hours</td>

        </tr>
    </thead>
    <tbody>
        <?php
        /* Get Today's timesheet entries */
        $count = 0;
        if (isset($dayTimesheet)) {
            $count = count($dayTimesheet);
            $rowspan = $count;
        } else {
            $rowspan = 1;
        }

        $th = 0;
        $tm = 0;
        foreach ($dayTimesheet as $t) {
            $th += $t['no_of_hours'];
            $tm += $t['no_of_min'];
        }

        $flag = 0;
        foreach ($dayTimesheet as $t):
        ?>
            <tr>
                <td style="text-align: left;"><?= $t['projectname'] ?></td>
                <td><?= $t['scope'] ?></td>
                <td><?= $t['sname'] ?></td>
                <td style="text-align: left;"><?= $t['work'] ?></td>
                <td><?= ($t['percent'] > 0) ? $t['percent'] . '%' : '&nbsp;'; ?></td>
                <td><?= bdAddHourMin($t['no_of_hours'], $t['no_of_min']) ?></td>
                <!-- <td></td> -->
                <?php if ($flag < 1): $flag++; ?>
                    <td rowspan="<?= $rowspan ?>" style="vertical-align:top;line-height:40px;">
                        <?= bdAddHourMin($th, $tm) ?>
                    </td>
                <?php endif; ?>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<?php
$query = "select
                t2.id as stageId,
                t2.`name` as stageNm,
                t2.sname as stageSc,
                t3.id as taskId,
                t3.`name` as task
            from
                projectstagetasks as t1,
                projectstage as t2,
                timesheettasks as t3
            where
                t1.projectstage_id = t2.id
                and t1.timesheettask_id = t3.id
                and t1.department_id = 3 /* Studio */
                and t1.active > 0
                and t2.active > 0
                and t3.active > 0
            order by
                t1.displayorder";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {
        //$taskX[$row['stageId']][$row['taskId']] = [$row['task']];
        $tX[] = $row;
    };

    $result->close();
}
$taskJs = json_encode($tX);
?>

<script>
    const apiUrl = "<?= $base_url ?>index.cgi";
    const project_id = <?= $pid ?>;

    let selectTask = e$('taskId');

    /* JSON Array */
    let taskJs = <?= $taskJs ?>;

    function onStageChange() {

        let stageId = e$('stageId').value
        rx('onStageChange | stageId: ' + stageId)

        //rx(taskJs);
        let tkStageId = 0;


        selectTask.replaceChildren(); // clears all children
        let option = document.createElement('option');
        option.value = 0; // value=""
        option.text = '-- Select --'; // visible text
        selectTask.appendChild(option);


        taskJs.forEach(function(t) {
            //rx(t)
            // rx(t.stageId)
            tkStageId = t.stageId

            if (tkStageId == stageId) {
                rx(t)

                let option = document.createElement('option');
                option.value = tkStageId; // value=""
                option.text = t.task; // visible text
                selectTask.appendChild(option);
            }
        })

    }


    function dxAddTimesheet() {
        rx("dxAddTimesheet")
        /* Validation done on server 
        if (selectTask.value < 1) {
            dxAlertBox("Timesheet Add Error", 'Please select Task.');
            return false;
        }
        */
        selectTaskTxt = selectTask.options[selectTask.selectedIndex].text;
        let work = '<div class="tsW">' + selectTaskTxt + '</div>' + e$("work").value;

        var formData = new FormData()
        formData.append("a", "concert-api-addTimesheet")

        formData.append("project_id", project_id)
        formData.append("date", e$("ts_date").value)
        formData.append("h", e$("ts_h").value)
        formData.append("m", e$("ts_m").value)
        formData.append("scope_id", e$('scopeId').value)
        formData.append("stage_id", e$('stageId').value)
        formData.append("task_id", e$('taskId').value)
        formData.append("work", work)
        formData.append("percent", e$('percent').value)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] == "T") {
                // console.log("Added.")
                window.location.reload()
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }
</script>

<?php
//rx($taskX);
//rx($taskJs);
//echo $query;
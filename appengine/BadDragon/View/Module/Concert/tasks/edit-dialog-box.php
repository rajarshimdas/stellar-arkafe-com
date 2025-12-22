<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 09-Aug-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<script>
    const stagex = <?= json_encode($stagex) ?>;
    const scopex = <?= json_encode($scopex) ?>;
    const taskById = <?= json_encode($taskById) ?>;
    const taskTsData = <?= json_encode($taskTsData) ?>;

    let dxTaskId = 0;

    function dxEditTask(task_id) {

        // document.getElementById("dxTaskId").value = task_id
        dxTaskId = task_id
        console.log('dxEditTask :: task_id: ' + dxTaskId)

        const thisTask = taskById[task_id]
        console.log(thisTask)

        const scope_id = thisTask.scope_id
        const scope = thisTask.scope_with_id
        const stage_id = thisTask.stage_id
        const stage = thisTask.stage_with_id
        const wk = thisTask.work
        const status_lm = thisTask.status_last_month
        const target_tm = thisTask.status_this_month_target

        // Timesheet data
        const dxAddTsData = taskTsData[task_id]
        // console.log(dxAddTsData)
        e$('dxAddIndex0').innerHTML = dxAddTsData[0]['manhours']
        e$('dxAddIndex1').innerHTML = dxAddTsData[1]['manhours']
        e$('dxAddIndex2').innerHTML = dxAddTsData[2]['manhours']

        // console.log(stagex)
        let st = '<option value="' + stage_id + '">' + stage + '</option>'
        for (let i = 0; i < stagex.length; i++) {
            if (stagex[i]["id"] != stage_id) st += '<option value="' + stagex[i]["id"] + '">' + stagex[i]["stage_with_id"] + '</option>'
        }
        // console.log(st)
        document.getElementById("dxStageId").innerHTML = st


        // console.log(scopex)
        // console.log(scope_id + ' | ' + scope)
        let sc = '<option value="' + scope_id + '">' + scope + '</option>'
        for (let e = 0; e < scopex.length; e++) {
            const s = scopex[e]
            if (s["id"] != scope_id) sc += '<option value="' + s["id"] + '">' + s["scope_with_id"] + '</option>'
        }
        document.getElementById("dxScopeId").innerHTML = sc


        // console.log('Work: ' + wk)
        document.getElementById("dxWorkEdit").value = wk

        // status_last_month 
        document.getElementById("dxCompPer").value = status_lm

        document.getElementById("dxTargPer").value = target_tm

        lockPage()
        document.getElementById("dxEditTask").showModal()
    }

    function dxEditTaskNow() {

        e$('dxEditBtn').style.display = 'none'
        e$('dxHoldOn').style.display = 'block'
        lockPage()

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-tkEdit")
        formData.append("dxTaskId", dxTaskId)
        formData.append("dxStageId", document.getElementById("dxStageId").value)
        formData.append("dxScopeId", document.getElementById("dxScopeId").value)
        formData.append("dxWork", document.getElementById("dxWorkEdit").value)
        formData.append("dxCompPer", document.getElementById("dxCompPer").value)
        formData.append("dxTargPer", document.getElementById("dxTargPer").value)

        /* 
        Updated: 02-Jun-2025
        */
        const dxTsData = taskTsData[dxTaskId][dxAddIndex]
        // console.log(dxTsData)

        let addH = (!e$('dxMhr').value) ? 0 : e$('dxMhr').value;
        let addM = (!e$('dxMmn').value) ? 0 : e$('dxMmn').value;

        const minutes = Number(dxTsData['totalmin']) + (Number(addH) * 60) + Number(addM)
        // console.log('minutes: ' + minutes)

        const dxH = Math.floor(minutes / 60);
        const dxM = minutes % 60;

        formData.append("dxMhr", dxH)
        formData.append("dxMmn", dxM)

        bdPostData(apiUrl, formData).then((response) => {
            // console.log(response);
            if (response[0] == "T") {
                window.location.reload()
            } else {
                dxAlertBox('Task Edit Error', response[1])
            }
        });
    }


    function dxDeleteTask(task_id) {
        const btn = '<input type="button" value="Y" onclick="javascript:dxDeleteTaskNow(' + task_id + ')">'
        document.getElementById("dx_" + task_id).innerHTML = btn
    }


    function dxDeleteTaskNow(task_id) {

        lockPage()

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-tkDelete")
        formData.append("taskId", task_id)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] == "T")
                window.location.reload()
            else
                dxAlertBox('Task Edit Error', response[1])
        });

    }

    let dxAddIndex = 2 // Default Option
    function addIndex(i) {
        // Set 
        dxAddIndex = i

        // Remove class tdActive
        e$('dxAddIndex0').classList.remove("tdActive")
        e$('dxAddIndex1').classList.remove("tdActive")
        e$('dxAddIndex2').classList.remove("tdActive")

        // Add class
        e$('dxAddIndex' + i).classList.add("tdActive")
    }
</script>

<style>
    table.tblTsInfo {
        width: 100%;
        font-size: 85%;
    }

    table.tblTsInfo tr td {
        text-align: center;
        padding: 4px;
        border-radius: 15px;
    }

    table.tblTsInfo tr td.tdAdd {
        background-color: lightgray;
        cursor: pointer;
    }

    table.tblTsInfo tr td.tdAdd:hover {
        background-color: #585858;
        color: white;
    }

    table.tblTsInfo tr td.tdActive {
        background-color: #585858;
        color: white;
    }
</style>

<dialog id="dxEditTask">
    <table class='dxTable' style="width:450px;">
        <tr>
            <td style="font-weight: bold; color: var(--rd-nav-light);">
                Edit Work Plan
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="unlockPage();dxClose('dxEditTask')">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" id="dxTaskId">
                <table>
                    <tr>
                        <td></td>
                        <td>
                            <label for="dxScopeId">Scope</label>
                            <select name="" id="dxScopeId"></select>
                        </td>
                        <td>
                            <label for="dxStageId">Milestone</label>
                            <select name="" id="dxStageId"></select>
                        </td>

                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <label for="dxWorkEdit">Work</label>
                            <input type="text" id="dxWorkEdit">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <label for="dxCompPer">Completed % by PM</label>
                            <input type="number" id="dxCompPer">
                        </td>
                        <td>
                            <label for="dxTargPer">Target %</label>
                            <input type="number" id="dxTargPer">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            <table class="tblTsInfo">
                                <tr>
                                    <td style="width: 33%;">Alloted</td>
                                    <td>Utilized Last Month</td>
                                    <td style="width: 33%;">Utilized Till Now</td>
                                </tr>
                                <tr>
                                    <td id='dxAddIndex0' class="tdAdd" onclick="addIndex(0)">1</td>
                                    <td id='dxAddIndex1' class="tdAdd" onclick="addIndex(1)">2</td>
                                    <td id='dxAddIndex2' class="tdAdd tdActive" onclick="addIndex(2)">3</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <label for="dxMhr">Add Manhours</label>
                            <input type="number" id="dxMhr">
                        </td>
                        <td>
                            <label for="dxMmn">Add Minutes</label>
                            <input type="number" id="dxMmn">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" style="height: 45px;text-align:center;">
                            <input id="dxEditBtn" class="button" type="button" value="Edit" onclick="javascript:dxEditTaskNow()">
                            <img id="dxHoldOn" src="<?= BASE_URL ?>da/icons/hold-on.gif" alt="Wait" style="display: none; height:25px;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</dialog>
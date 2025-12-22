<?php
require_once BD . 'Controller/User/User.php';

$users = bdGetUsersArray($mysqli);
// var_dump($users);
?>
<style>
    table.formTBL tr td {
        border: 0px solid blue;
    }

    table#formBox {
        width: 100%;
        border: 0px solid green;
    }

    table#formBox tr td {
        padding: 2px;
        font-weight: normal;
        color: black;
        background-color: transparent;
        border: 0px solid red;
    }
</style>
<table class="formTBL" style="background:#fff255">
    <tr>
        <td>Leave</td>
        <td style="width: 60%;">
            <select name="activeUId" id="activeUID" onchange="setActiveUID()">
                <?php

                if ($activeUID < 1) {
                    echo '<option value="0">-- Select User --</option>';
                } else {
                    $ux = bdGetUsersArrayByUID($activeUID, $mysqli);
                    echo '<option value="' . $activeUID . '">' . $ux['displayname'] . '</option>';
                }

                for ($i = 0; $i < count($users); $i++) {

                    if ($users[$i]["user_id"] != $activeUID && $users[$i]["active"] > 0) {
                        echo '<option value="' . $users[$i]['user_id'] . '">' . $users[$i]['displayname'] . '</option>';
                    }
                }
                ?>

            </select>
        </td>
    </tr>
    <tr id="formBoxTr" style="background-color:white;">
        <td colspan="2">
            <table id="formBox" style="width:100%;display:none;">
                <tr>
                    <td style="width:80px;text-align:right;">Date</td>
                    <td style="width:140px;"><input type="date" name="dt" id="dt"></td>
                    <td style="width:250px;">
                        <select name="leaveId" id="leaveId">
                            <option value="0">-- Select --</option>
                            <option value="2">Leave | Full Day</option>
                            <option value="3">Leave | Half Day</option>
                            <option value="4">Time off</option>
                        </select>
                    </td>
                    <td style="width:60px;">
                        <button class="button" onclick="addTimesheetLeave()">Add</button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="background-color:white;">

        <table id='table5' class="tabulation" style="width:100%;display:none;">
            <thead>
                <tr class="headerRow">
                    <td class="headerRowCell1" style="width:50px">No</td>
                    <td class="headerRowCell2" style="width:150px">Date</td>
                    <td class="headerRowCell2" style="width:340px;border-right:0px;">Type</td>
                    <td class="headerRowCell2" style="width:50px;border-left:0px;">&nbsp;</td>
                </tr>
            </thead>
            <tbody id="leaveTable"></tbody>
        </table>

    </tr>
</table>
<script>
    let activeUID = <?= $activeUID ?>;
    const apiUrl = "<?= BASE_URL ?>index.cgi";

    window.onload = (event) => {
        if (activeUID > 0) {
            getUserBox(activeUID)
        }
    };

    function setActiveUID() {

        const userId = document.getElementById("activeUID").value


        var formData = new FormData()
        formData.append("a", "sysadmin-api-setActiveUID")
        formData.append("userId", userId)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] == "T") {
                activeUID = userId
                document.getElementById('table5').style.display = 'block'
                getUserBox(userId)
            } else {
                console.log(response[1])
            }
        });

    }

    function getUserBox(userId) {

        document.getElementById("formBoxTr").style.backgroundColor = '#fecd61'
        document.getElementById("formBox").style.display = 'block'

        leaveTable(userId)
    }

    function leaveTable(userId) {

        var formData = new FormData()
        formData.append("a", "sysadmin-api-leaveTableUID")
        formData.append("userId", userId)

        bdPostData(apiUrl, formData).then((response) => {
            // console.log(response)
            if (response[0] == "T") {
                document.getElementById('table5').style.display = 'block'
                document.getElementById('leaveTable').innerHTML = response[1]
            } else {
                console.log(response[1])
            }
        });
    }

    function addTimesheetLeave() {

        console.log('addTimesheetLeave')

        let leaveId = document.getElementById("leaveId").value
        let hours = 0

        switch (leaveId) {
            case '2':
                // Full day
                hours = 10
                saveLeave(leaveId, hours, 0)
                break;
            case '3':
                // Half day
                hours = 5
                saveLeave(leaveId, hours, 0)
                break;
            case '4':
                // Time off
                setTimeOffHours(leaveId)
                break;
            default:
                console.log('switch error')
                return false
        }
    }

    function saveLeave(leaveId, hours, min) {

        var formData = new FormData()
        formData.append("a", "concert-api-addTimesheet")

        formData.append("activeUID", activeUID)

        formData.append("task_id", 1)
        formData.append("date", e$("dt").value)
        formData.append("h", hours)
        formData.append("m", min)
        formData.append("work", '-')
        formData.append("percent", 0)

        formData.append("project_id", leaveId);
        formData.append("scope_id", '1')
        formData.append("stage_id", '1')

        bdPostData(apiUrl, formData).then((response) => {

            // console.log(response);

            if (response[0] == "T") {
                console.log("Added.")
                leaveTable(activeUID)
            } else {
                console.log("Failed.")
                showAlert("Leave Add Error", response[1])
            }
        });
    }

    function deleteLeave(tsId) {

        console.log('tsId: ' + tsId)

        var formData = new FormData()
        formData.append("a", "concert-api-tsDelete")

        formData.append("tsId", tsId)

        bdPostData(apiUrl, formData).then((response) => {

            console.log(response);

            if (response[0] == "T") {
                console.log("Deleted.")
                leaveTable(activeUID)
            } else {
                console.log("Failed.")
                showAlert("Timesheet Delete Error", response[1])
            }
        });
    }

    function setTimeOffHours(leaveId) {

        var formData = new FormData()
        formData.append("a", "concert-api-getTimeOffHours")

        formData.append("uid", activeUID)
        formData.append("date", e$('dt').value)

        bdPostData(apiUrl, formData).then((response) => {

            if (response[0] == "T") {
                console.log("TimeOff: ", response[1])
                
                saveLeave(leaveId, response[1], response[2])
            } else {
                showAlert("Error", response[1])
            }
        });
    }
</script>
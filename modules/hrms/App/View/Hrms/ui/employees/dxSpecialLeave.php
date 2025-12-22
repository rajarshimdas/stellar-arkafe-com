<style>
    .dxHead {
        background-color: cadetblue;
        color: white;
    }
</style>
<dialog id="dxSpecialLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="3">Leave Without Pay</td>
                <td>
                    <img
                        class="fa5button"
                        src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png"
                        onclick="dxClose('dxSpecialLeave')"
                        alt="Close">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr class="dxHead">
                <td style="width:120px;">Employee</td>
                <td id='dxSpecialLeaveEmp' style="font-weight: bold;" colspan="2"></td>
                <td style="width:45px;"></td>
            </tr>

            <tr class="dxHead">
                <td style="width: 60px;">
                    Start
                </td>
                <td style="width: 150px;">
                    <input type="date" name="sdt" id="sdt" readonly>
                </td>
                <td style="width: 150px;">
                    <!-- <select name="sx" id="sx">
                        <option value="F">Full day</option>
                        <option value="H">Half day</option>
                    </select> -->
                    <input type="text" name="sx" id="sx" readonly>
                </td>
                <td></td>
            </tr>

            <tr class="dxHead">
                <td>End</td>
                <td>
                    <input type="date" name="edt" id="edt" readonly>
                </td>
                <td>
                    <!-- <select name="ex" id="ex">
                        <option value="F">Full day</option>
                        <option value="H">Half day</option>
                    </select> -->
                    <input type="text" name="ex" id="ex" readonly>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>Leave Type</td>
                <td colspan="2">
                    <select name="dxleaveTypeId" id="dxleaveTypeId">
                        <option value="0">-- Select --</option>
                        <?php
                        if (empty($leaveTypes)) $leaveTypes = bdGetLeaveTypes($mysqli);
                        foreach ($leaveTypes as $t) {
                            if ($t['is_normal'] < 1 && $t['active'] > 0) {
                                echo "<option value='" . $t['id'] . "'>" . $t['type'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>Days</td>
                <td colspan="2">
                    <input type="text" name="dxNod" id="dxNod" placeholder="Number of days">
                </td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <button class="button-18" style="width: 80px;" onclick="leaveSpecialAdd()">Add</button>
                    <button class="button-18" style="width: 80px;" onclick='dxClose("dxSpecialLeave")'>Cancel</button>
            </tr>
        </tfoot>
    </table>
</dialog>

<script>

    // function dxSpecialLeave(thisUId, dname) {

    //     thisUserId = thisUId
    //     e$('dxSpecialLeaveEmp').innerHTML = dname
    //     e$('dxSpecialLeave').showModal()

    // }

    const dxLWP = (leave_id) => {
        rx('dxLWP: ' + leave_id)
        leaveId = leave_id

        const thisLeaveRec = leaveReqById[leaveId]
        rx(thisLeaveRec)

        e$('sdt').value = thisLeaveRec['from_dt']
        e$('edt').value = thisLeaveRec['end_dt']

        e$('sx').value = thisLeaveRec['from_dt_units']
        e$('ex').value = thisLeaveRec['end_dt_units']

        e$('dxNod').value = thisLeaveRec['nod_units']

        e$('dxSpecialLeaveEmp').innerHTML = dname

        e$('dxSpecialLeave').showModal()
        e$("dxleaveTypeId").focus()

    }


    function leaveSpecialAdd() {

        rx('leaveSpecialAdd: UId: ' + thisUserId)
        rx('leaveId: ' + leaveId)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveSpecialAdd")

        formData.append('leaveId', leaveId)
        formData.append("thisUserId", thisUserId)
        formData.append('dxleaveTypeId', e$('dxleaveTypeId').value)
        formData.append("sdt", e$("sdt").value)
        formData.append('sx', e$('sx').value)
        formData.append('edt', e$('edt').value)
        formData.append('ex', e$('ex').value)
        formData.append('nod', e$('dxNod').value)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] != "T") {
                // Error
                if (response[2] != 'x') e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                window.location.reload()
            }
        });
    }
</script>
<dialog id="dxLeaveUpdate">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="3">Leave Starting Balance</td>
                <td>
                    <img
                        class="fa5button"
                        src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png"
                        onclick="dxClose('dxLeaveUpdate')"
                        alt="Close">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Employee</td>
                <td id='dxLeaveUpdateEmp' style="font-weight: bold;"></td>
                <td></td>
            </tr>
            <tr>
                <td>Start Month</td>
                <td><input type="month" name="dxMonth" id="dxMonth" value="<?= $year ?>-01" min="<?= $year ?>-01" max="<?= $year ?>-12"></td>
                <td></td>
            </tr>
            <tr>
                <td>Earned Leave</td>
                <td>
                    <input id="dxLeaveEL" type="number" step="0.01" min="0" />
                </td>
                <td></td>
            </tr>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <button class="button-18" style="width: 80px;" onclick="leaveUpdate()">Update</button>
                    <button class="button-18" style="width: 80px;" onclick='dxClose("dxLeaveUpdate")'>Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>

<script>
    const dxLeaveUpdate = (thisUId, dname) => {

        thisUserId = thisUId
        rx('thisUserId: ' + thisUserId)

        e$('dxLeaveUpdateEmp').innerHTML = dname
        e$('dxLeaveUpdate').showModal();
    }

    const leaveUpdate = () => {
        rx('leaveUpdate: ' + thisUserId)

        // Data Validation
        let mo = e$('dxMonth').value
        let nod = e$('dxLeaveEL').value

        if (!bdValidate.isValidISODate(mo + '-01')) {
            // Error
            e$(e$('dxMonth').focus())
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = 'Select Month'
            e$("dxMessageBox").showModal()
            return false
        }

        if (!bdValidate.isPositiveNumber(nod)) {
            // Error
            e$(e$('dxLeaveEL').focus())
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = 'Starting balance must be a positive number.'
            e$("dxMessageBox").showModal()
            return false
        }


        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveStartBal")

        formData.append('thisUId', thisUserId)
        formData.append("dxMonth", mo)
        formData.append("dxLeaveEL", nod)

        bdFetchAPI(apiUrl, formData).then((response) => {
            rx(response)

            if (response[0] != "T") {
                // Error
                e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }

        })

    }
</script>
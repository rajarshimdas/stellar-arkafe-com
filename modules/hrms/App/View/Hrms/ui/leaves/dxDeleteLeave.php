<dialog id="dxDeleteLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="2">Confirm Delete Leave</td>
                <td style="text-align: right;">
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxDeleteLeave')">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td id="dxRec"></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button class="button-18" style="width: 80px;" onclick="deleteLeave()">Delete</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxDeleteLeave')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>
<script>
    const dxDeleteLeave = (lId) => {
        
        leaveId = lId
        rx('dxDeleteLeave: ' + leaveId)
        let leaveRec = leaveReqById[leaveId]
        rx(leaveRec['reason'])
        e$('dxRec').innerHTML = leaveRec['reason']
        
        e$('dxDeleteLeave').showModal()
    }

    const deleteLeave = () => {
        rx('deleteLeave: ' + leaveId)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveSpecialDelete")
        formData.append("leaveId", leaveId)

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

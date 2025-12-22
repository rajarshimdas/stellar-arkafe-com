<dialog id="dxEditLeaveRec">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="2">Edit Leave :: <span id="dxDname"></span></td>
                <td style="text-align: right;">
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxEditLeaveRec')">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Leave Type</td>
                <td>
                    <select onchange="javascript:dxAttrId = this.value;" id="dxAttrId">
                        <option value="9">Short Leave</option>
                        <option value="10">Sanctioned</option>
                        <option value="30">Informed</option>
                        <option value="31">Informed Medical</option>
                        <option value="20">Un-sanctioned</option>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Days</td>
                <td><input type="number" name="dxNoday" id="dxNoday"></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button class="button-18" style="width: 80px;" onclick="editLeaveRec()">Edit</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxEditLeaveRec')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>

<script>
    let dxLeaveId = 0
    let dxAttrId = 1

    function dxEditLeaveRec(leaveId, displayName) {

        dxLeaveId = leaveId
        rx('dxLeaveId: ' + dxLeaveId)

        let leaveRec = leaveReqById[dxLeaveId]

        e$("dxDname").innerHTML = displayName
        e$('dxAttrId').value = leaveRec['leave_attr_id']
        e$('dxNoday').value = leaveRec['nod_units']

        e$("dxEditLeaveRec").showModal()
    }

    function editLeaveRec(){

        rx('editLeaveRec::dxLeaveId: ' + dxLeaveId)

        const attrId = e$('dxAttrId').value
        const dxNoday = e$('dxNoday').value

         var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveApprove")
        formData.append('leaveId', dxLeaveId)
        formData.append('attrId', attrId)
        formData.append('noOfDays', dxNoday)
        formData.append('log', 'Edit by HR')

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                // e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        })

    };
</script>
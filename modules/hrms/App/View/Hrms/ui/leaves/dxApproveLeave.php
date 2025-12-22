<dialog id="dxApproveLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="2">Approve Leave :: <span id="dn"></span></td>
                <td style="text-align: right;">
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxApproveLeave')">
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Leave Type</td>
                <td>
                    <select id='dxAttrId'>
                        <option value="9">Short Leave</option>
                        <option value="10">Sanctioned</option>
                        <option value="30">Informed</option>
                        <option value="20">Un-sanctioned</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <button class="button-18" style="width: 80px;" onclick="leaveApprove(dxLeaveId, e$('dxAttrId').value, dxNoOfDays)">Approve</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxApproveLeave')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>

<script>
    let dxLeaveId = 0
    let dxAttrId = 1
    let dxNoOfDays = 0

    function leaveApproveDx(leaveId, attrId, displayName, noOfDays) {

        console.log('leaveId: ' + leaveId)
        
        dxLeaveId = leaveId
        dxAttrId = attrId
        dxNoOfDays = noOfDays

        e$("dn").innerHTML = displayName
        e$("dxAttrId").value = attrId
        e$("dxApproveLeave").showModal()
    }
</script>
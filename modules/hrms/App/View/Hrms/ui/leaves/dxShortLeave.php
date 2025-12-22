<dialog id="dxApplyShortLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="3">Apply Short Leave</td>
                <td>
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxApplyShortLeave')">
                </td>
            </tr>
        </thead>
        <tr>
            <td style="width: 60px;">
                Date
            </td>
            <td style="width: 150px;">
                <input type="date" name="shortDt" id="shortDt"
                    min="<?= $dateRangeStart ?>"
                    max="<?= $dateRangeEnd ?>"
                    value="<?= date('Y-m-d') ?>">
            </td>
            <td style="width: 150px;">
                <select name="shortTx" id="shortTx">
                    <option value="X">-- Select --</option>
                    <option value="LC">Late Coming</option>
                    <option value="EG">Early Going</option>
                </select>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Reason</td>
            <td colspan="2">
                <input type="text" name="shortRn" id="shortRn">
            </td>
            <td></td>
        </tr>
        <tfoot>
            <tr>
                <td colspan="4">
                    <button class="button-18" style="width: 80px;" onclick="applyShortLeave()">Apply</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxApplyShortLeave')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>
<script>
    function dxApplyShortLeave() {
        e$("dxApplyShortLeave").showModal()
    }

    function applyShortLeave() {

        const shortDt = e$('shortDt').value
        const shortTx = e$('shortTx').value
        const shortRn = e$('shortRn').value

        // Check if this date is a holiday
        if (holidays.includes(shortDt)) {

            e$('shortDt').value = ''
            e$('shortDt').focus()
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = formatIsoDateToHuman(shortDt) + ' is a holiday.'
            e$("dxMessageBox").showModal()
            return false

        }

        if (shortTx == 'X') {
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = 'Select Late coming or Early going.'
            e$("dxMessageBox").showModal()
            return false
        }

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveApplyShort")
        formData.append("ltype", 9);                // Short Leaves  
        formData.append("sdt", shortDt)
        formData.append('sx', shortTx)
        formData.append('edt', shortDt)
        formData.append('ex', shortTx)
        formData.append('nod', 0)                   // No deduction
        formData.append('rx', shortRn)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] != "T") {
                // Error
                if (response[2] != 'x') e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        });

    }
</script>
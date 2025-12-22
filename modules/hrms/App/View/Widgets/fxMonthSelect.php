<table class="rd-table-fx">
    <tr>
        <td style="font-family:'Roboto Bold';color:var(--rd-light-gray);width:200px;"><?= $monthNm[$monthNo] ?></td>
        <!-- <td style="text-align: center;color:red;">Beta!</td> -->
        <td style="width:30px;text-align:right;">Month</td>
        <td style="width:30px;">
            <select name="leaveMISMonth" id="leaveMISMonth" onchange="monthChange(this.value)">
                <option value="<?= $months2d[$monthNo] ?>"><?= $months[$monthNo] ?></option>
                <?php
                for ($i = 1; $i <= 12; $i++):
                    if ($months[$i] != $months[$monthNo]):
                ?>
                        <option value="<?= $months2d[$i] ?>"><?= $months[$i] ?></option>
                <?php
                    endif;
                endfor;
                ?>
            </select>
        </td>
        <td style="width:30px;">
            <select name="leaveMISYear" id="leaveMISYear" onchange="yearChange(this.value)">
                <option value="<?= $year ?>"><?= $year ?></option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </td>
    </tr>
</table>

<script>
    function monthChange(monthNo) {
        
        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveMISMonth")
        formData.append('monthNo', monthNo)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'Month select error.')
            } else {
                // Success
                window.location.reload()
            }
        })
    }

    function yearChange(yearNo) {
        
        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveMISYear")
        formData.append('yearNo', yearNo)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'Year select error.')
            } else {
                // Success
                window.location.reload()
            }
        })
    }
</script>
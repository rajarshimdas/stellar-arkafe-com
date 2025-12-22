<dialog id="dxNewEmployee">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="2">Add New Employee</td>
                <td>
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxNewEmployee')">
                </td>
            </tr>
        </thead>


        <tr>
            <td width="30%">Loginname:</td>
            <td width="70%"><input type="text" id="dxLoginname"></td>
            <td></td>
        </tr>

        <tr>
            <td>Display Name:</td>
            <td><input type="text" id="dxDisplayname"></td>
            <td></td>
        </tr>


        <!-- HR Group -->
        <tr>
            <td>Designation:</td>
            <td>
                <select id="dxHrgroupId">
                    <option value="0">-- Select --</option>
                    <?php
                    $query = "SELECT * FROM userhrgroup order by displayorder";
                    $result = $mysqli->query($query);
                    while ($row = $result->fetch_assoc()) :
                        if ($row['active'] > 0):
                    ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php
                        endif;
                    endwhile;
                    ?>
                </select>
            </td>
            <td></td>
        </tr>

        <tr>
            <td>Reports To:</td>
            <td>
                <select id="dxReportsToUId">
                    <option value="0">-- Select --</option>
                    <?php
                    foreach ($users as $x):
                        if ($x['active'] > 0):
                            $xid = $x['user_id'];
                            $xnm = $x['displayname'];
                    ?>
                            <option value="<?= $xid ?>"><?= $xnm ?></option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </select>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Date of Joining</td>
            <td>
                <input type="date" id="dxdoj">
            </td>
            <td></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td style="text-align:center;">
                <a href="#" class="button-18" onclick="addNewEmployee()" style="width: 100px;">Add</a>
                <a href="#" class="button-18 button-18gray" onclick="dxClose('dxNewEmployee')" style="width: 100px;">Cancel</a>
            </td>
            <td></td>
        </tr>

    </table>
</dialog>

<script>
    // Dialogbox
    const dxNewEmployee = e$('dxNewEmployee')
    // Input fields
    const dxLoginname = e$('dxLoginname')
    const dxDisplayname = e$('dxDisplayname')
    const dxHrgroupId = e$('dxHrgroupId')
    const dxReportsToUId = e$('dxReportsToUId')
    const dxdoj = e$('dxdoj')

    function showDxNewEmployee() {
        // Reset
        dxLoginname.value = ''
        dxDisplayname.value = ''
        dxHrgroupId.value = 0
        dxReportsToUId.value=0
        dxdoj.value = ''

        dxNewEmployee.showModal()
    }

    function addNewEmployee() {
        rx('addNewEmployee')

        let dxLoginname = e$('dxLoginname').value
        let dxDisplayname = e$('dxDisplayname').value
        let dxHrgroupId = e$('dxHrgroupId').value
        let dxReportsToUId = e$('dxReportsToUId').value
        let dxdoj = e$('dxdoj').value

        var formData = new FormData()
        formData.append('a', 'aec-hrms-api-hrmsAddNewUser')
        formData.append('dxLoginname', dxLoginname)
        formData.append('dxDisplayname', dxDisplayname)
        formData.append('dxHrgroupId', dxHrgroupId)
        formData.append('dxReportsToUId', dxReportsToUId)
        formData.append('dxdoj', dxdoj)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', response[1])
            } else {
                // Success
                rx('ok')
                dxClose('dxNewEmployee')
                // Update Employee list
                gethrmsEmployeeTable()
                // Message
                showMessageBox('New Employee Added', response[1])
            }
        })

    }
</script>
<!-- 
 <div style="text-align: center; background-color:#D4D2DC; padding:25px;">
    Add | Edit | Delete | Reset Password | Timesheet Lockdate | MOD | Notice board
</div> 
-->
<?php
require_once __DIR__ . '/fx/dxNewEmployee.php';
?>
<div>
    <table style="width: 100%;">
        <tr>
            <td style="width: 300px;"></td>
            <td style="text-align: center;">
                <a href="#" class="button-18" onclick="showDxNewEmployee()">+ Add New Employee</a>
            </td>
            <td style="width:200px;text-align: right;">Deleted Users</td>
            <td style="width: 100px;">
                <?php $opt = ['Show', 'Hide']; ?>
                <select onchange="deletedUsersToggle(this.value)">
                    <option value="<?= $hrmsDeletedUsersToggle ?>"><?= $hrmsDeletedUsersToggle ?></option>
                    <?php
                    foreach ($opt as $o):
                        if ($o != $hrmsDeletedUsersToggle):
                    ?>
                            <option value="<?= $o ?>"><?= $o ?></option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </select>
            </td>
        </tr>
    </table>
</div>

<style>
    .rd-table tr td:nth-child(2),
    .rd-table tr td:nth-child(3),
    .rd-table tr td:nth-child(4),
    .rd-table tr td:nth-child(5),
    .rd-table tr td:nth-child(6) {
        text-align: left;
    }
</style>

<table class="rd-table">
    <thead>
        <tr>
            <td style="width: 50px;">No</td>
            <td>Employees</td>
            <td style="width: 150px;">Employee ID</td>
            <td style="width: 200px;">Designation</td>
            <td style="width: 200px;">Reporting Manager</td>
            <td style="width: 150px;">Mobile</td>
            <td style="width: 150px;">Date of Joining</td>
            <td style="width: 80px;"></td>
        </tr>
    </thead>
    <tbody id="employees">
    </tbody>
</table>

<script>
    function showDxDeleteConfirm(uid, name) {
        showMessageBox(
            'Confirm Delete',
            '<div style="text-align:center;"><a href="#" class="button-18" onclick="deleteEmployee(' + uid + ')">Delete ' + name + '</a></div>'
        )
    }

    function deleteEmployee(uid) {
        rx('deleteEmployee: ' + uid)
        e$('dxMessageBox').close()

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-hrmsDeleteUser")
        formData.append("uid", uid)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'Could not delete Employee.<br>'+response[1])
            } else {
                // Success
                rx('ok')
                gethrmsEmployeeTable()
            }
        })

    }

    function gethrmsEmployeeTable() {

        rx('gethrmsEmployeeTable')

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-hrmsEmployeesTable")
        formData.append("hrmsDeletedUsersToggle", "<?= $hrmsDeletedUsersToggle ?>")

        bdFetchAPI(apiUrl, formData).then((response) => {
            // console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'Could not fetch Employee data.')
            } else {
                // Success
                rx('ok')
                e$('employees').innerHTML = response[1]
            }
        })

    }

    function sethrmsActiveUser(uid) {

        rx('hrmsActiveUser: ' + uid)
        // rx('apiUrl: ' + apiUrl)

        // Fetch API
        var formData = new FormData()
        formData.append("a", "aec-hrms-api-hrmsActiveUser")
        formData.append("hrmsActiveUser", uid)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'User select error.')
            } else {
                // Success
                rx('ok')
                // window.location.reload()
                go2('aec/hrms/ui/admin/employees-edit')
            }
        })

    }

    // Show|Hide deleted users
    function deletedUsersToggle(opt) {
        rx('deletedUsersToggle: ' + opt)

        // Fetch API
        var formData = new FormData()
        formData.append("a", "aec-hrms-api-hrmsDeletedUsersToggle")
        formData.append("hrmsDeletedUsersToggle", opt)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'set deletedUsersToggle failed.')
            } else {
                // Success
                rx('ok')
                window.location.reload()
            }
        })

    }

    window.onload = function() {
        gethrmsEmployeeTable()
    };
</script>
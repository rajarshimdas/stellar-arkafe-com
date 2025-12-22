<?php
$user = $users[(int)$hrmsActiveUser]; // Set in Session.php
$u = $user;

require_once W3APP . '/View/Widgets/_profile.php';
?>
<style>
    a[disabled] {
        pointer-events: none;
        opacity: 0.6;
        background-color: var(--rd-dark-gray);
        cursor: not-allowed;
    }

    table.ui-dialog-box {
        font-size: var(--rd-table-font-size);
        color: var(--rd-dark-gray);
        background-color: var(--rd-form-bg);
        border: 20px solid var(--rd-form-bg);
        border-radius: 5px;
        width: 600px;
        margin: auto;
    }

    table.ui-dialog-box tr td {
        text-align: left;
    }

    table.ui-dialog-box tr td:first-child {
        color: gray;
        width: 150px;
        text-align: right;
    }

    table.ui-dialog-box caption {
        padding: 35px 0px 10px 15px;
        text-align: left;
        color: gray;
        font-family: 'Roboto Bold';
    }

    table.ui-card-box {
        border-collapse: collapse;
    }

    table.ui-card-box tr:first-child td {
        height: 45px;
        text-align: right;
    }

    table.ui-card-box tr td {
        line-height: 2em;
        border-bottom: 1px solid var(--rd-border-light);
        padding: 2px 5px;
    }
</style>
<script>
    const thisUId = <?= $u['user_id'] ?>;

    function dxShowDialog(dx) {
        e$(dx).showModal()
    }

    function dxHideDialog(dx) {
        e$(dx).close()
    }

    function showDxDeleteConfirm(uid, name) {
        showMessageBox(
            'Confirm Delete',
            '<div style="text-align:center;"><a href="#" class="button-18 button-18red" onclick="deleteEmployee(' + uid + ')">Delete ' + name + '</a></div>'
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
                showMessageBox('Error', 'Could not delete Employee.<br>' + response[1])
            } else {
                // Success
                rx('ok')
                window.location.reload()
            }
        })

    }
</script>

<div style="margin: auto; text-align: center;">

    <table style="width: 100%;">
        <tr>
            <td style="width: 29%;"></td>
            <td style="width: 14%;">
                <a style="width: 100%;" href="#" class="button-18">Reset Password</a>
            </td>
            <td style="width: 14%;">
                <a style="width: 100%;" href="#" class="button-18">Timesheet Lockdate</a>
            </td>
            <td style="width: 14%;">
                <a style="width: 100%;" href="#" class="button-18" onclick="showDxDeleteConfirm(<?= $u['user_id'] ?>, '<?= $u['displayname'] ?>')">Delete Employee</a>
            </td>
            <td style="width: 29%;"></td>
        </tr>
    </table>


    <?php
    // Personal Details
    require_once __DIR__ . '/fx/dxEmployeePD.php';
    // Employment Details
    require_once __DIR__ . '/fx/dxEmployeeED.php';
    ?>
    
    
    <!-- Emergency -->
    <table class="ui-dialog-box">
        <caption>
            Emergency Contact
        </caption>
        <tr>
            <td>Name</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Phone Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Contact -->
    <table class="ui-dialog-box">
        <caption>
            Contact
        </caption>
        <tr>
            <td>Mobile Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Personal Email</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Current Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Permanent Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <!-- Accounts Detail -->
    <table class="ui-dialog-box">
        <caption>
            Accounts Detail
        </caption>
        <tr>
            <td>Aadhaar No (4 digits)</td>
            <td>
                <input type="number" name="" id="" min="0" max="9999">
            </td>
        </tr>
        <tr>
            <td>PAN</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>PF Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>ESI Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>UAN Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Bank Name</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Account Number</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>IFSC</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Current Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td>Permanent Address</td>
            <td><input type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td><a href="#" class="button-18">Save</a></td>
        </tr>
    </table>

    <style>
        table.dxTable {
            width: 100%;
        }

        table.dxTable thead tr td,
        table.dxTable thead tr td:first-child {
            text-align: left;
            background-color: cadetblue;
            color: white;
        }
    </style>

    <table class="ui-dialog-box">
        <caption>
            Qualifications
        </caption>
        <tr>
            <td></td>
            <td style="text-align: right;">
                <a href="#" class="button-18">+ Add</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="dxTable">
                    <thead>
                        <tr>
                            <td style="width:50px;text-align:center;">No</td>
                            <td style="width:150px;">Qualifications</td>
                            <td style="width:80px;text-align:center;">Year</td>
                            <td>Institution</td>
                        </tr>
                    </thead>
                    <tbody id="dxSkillsList"></tbody>
                </table>
            </td>
        </tr>
    </table>

    <table class="ui-dialog-box">
        <caption>
            Skills
        </caption>
        <tr>
            <td></td>
            <td style="text-align: right;">
                <a href="#" class="button-18">+ Add</a>
                <a href="#" class="button-18">Skills List</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="dxTable">
                    <thead>
                        <tr>
                            <td style="width:50px;text-align:center;">No</td>
                            <td style="width:150px;">Skills</td>
                            <td style="width:150px;">Last used at</td>
                            <td>Description</td>
                        </tr>
                    </thead>
                    <tbody id="dxSkillsList"></tbody>
                </table>
            </td>
        </tr>
    </table>

</div>
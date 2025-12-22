<!-- Employment Details -->
<table class="ui-card-box ui-dialog-box">
    <caption>
        Employment Details <?= $u['user_id'] ?>
    </caption>
    <tr>
        <td></td>
        <td>
            <a class="button-18" onclick="dxShowDialog('updateED')">Update</a>
        </td>
    </tr>
    <tr>
        <td>Employee ID</td>
        <td><?= bd2hd($u['employee_code']) ?></td>
    </tr>
    <tr>
        <td>Type</td>
        <td><?= bd2hd($u['employment_type']) ?></td>
    </tr>
    <tr>
        <td>Department</td>
        <td><?= $u['departmentname'] ?></td>
    </tr>
    <tr>
        <td>Designation</td>
        <td><?= $u['hrgroup'] ?></td>
    </tr>
    <tr>
        <td>Reports To</td>
        <td><?= $u['reports_to'] ?></td>
    </tr>
    <tr>
        <td>Date of Joining</td>
        <td><?= $u['doj'] ?></td>
    </tr>
    <tr>
        <td>Date of Exit</td>
        <td><?= $u['doe'] ?></td>
    </tr>
</table>


<dialog id="updateED">
    <!-- Employment Details -->
    <table class="ui-dialog-box">
        <caption>
            Employment Details
        </caption>
        <tr>
            <td>Employee ID</td>
            <td>
                <input type="text" name="" id="">
            </td>
        </tr>
        <tr>
            <td>Type</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Department</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Designation</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Reports To</td>
            <td>
                <select>
                    <option></option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Date of Joining</td>
            <td><input type="date" /></td>
        </tr>
        <tr>
            <td>Date of Exit</td>
            <td><input type="date" /></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">
                <a class="button-18" style="width:100px;" onclick="updateED()">Save</a>
                <a class="button-18 button-18gray"  style="width:100px;" onclick="dxHideDialog('updateED')">Cancel</a>
            </td>
        </tr>
    </table>

</dialog>
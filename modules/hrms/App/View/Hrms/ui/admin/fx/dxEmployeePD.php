<!-- Personal Details -->
<table class="ui-card-box ui-dialog-box">
    <caption>
        Personal Details
    </caption>
    <tr>
        <td></td>
        <td>
            <a class="button-18" onclick="dxShowDialog('updatePD')">Update</a>
        </td>
    </tr>
    <tr>
        <td>Display name</td>
        <td><?= $u['displayname'] ?></td>
    </tr>
    <tr>
        <td>Loginname</td>
        <td><?= $u['loginname'] ?></td>
    </tr>
    <tr>
        <td>First Name:</td>
        <td><?= bd2hd($u['fname']) ?></td>
    </tr>
    <tr>
        <td>Middle Name:</td>
        <td><?= bd2hd($u['mname']) ?></td>
    </tr>
    <tr>
        <td>Last Name:</td>
        <td><?= bd2hd($u['lname']) ?></td>
    </tr>
    <tr>
        <td>Date of Birth:</td>
        <td><?= bd2fdH($u['dob']) ?></td>
    </tr>
    <tr>
        <td>Gender:</td>
        <td>
            <?php
            $gender = $u['gender'];

            foreach ($mlGender as $g):
                if ($g[0] == $gender):
                    echo ($g[0] != '-') ? $g[1] : "&nbsp;";
                endif;
            endforeach;
            ?>
        </td>
    </tr>
    <tr>
        <td>Blood Group:</td>
        <td><?= bd2hd($u['bloodgroup']) ?></td>
    </tr>

</table>
<dialog id="updatePD">
    <table class="ui-dialog-box">
        <caption>
            Personal Details
        </caption>
        <tr>
            <td>Display name</td>
            <td><input type="text" id="dxDisplayName" value="<?= $u['displayname'] ?>" /></td>
        </tr>
        <tr>
            <td>Loginname</td>
            <td><input type="text" id="dxLoginName" value="<?= $u['loginname'] ?>" /></td>
        </tr>
        <tr>
            <td>First Name</td>
            <td><input type="text" id="dxFname" value="<?= bd2hd($u['fname']) ?>" /></td>
        </tr>
        <tr>
            <td>Middle Name</td>
            <td><input type="text" id="dxMname" value="<?= bd2hd($u['mname']) ?>" /></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" id="dxLname" value="<?= bd2hd($u['lname']) ?>" /></td>
        </tr>
        <tr>
            <td>Date of Birth</td>
            <td><input type="date" id="dxDOB" value="<?= bd2fd($u['dob']) ?>" /></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td>

                <select name="dxGender" id="dxGender">
                    <?php
                    $gender = $u['gender'];

                    // Option displayed
                    foreach ($mlGender as $g):
                        if ($g[0] == $gender):
                    ?>
                            <option value="<?= $gender ?>"><?= $g[1] ?></option>
                        <?php
                        endif;
                    endforeach;

                    // Options drop down
                    foreach ($mlGender as $g):
                        if ($g[0] != $gender):
                        ?>
                            <option value="<?= $g[0] ?>"><?= $g[1] ?></option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Blood Group</td>
            <td><input type="text" id="dxBloodGroup" value="<?= bd2hd($u['bloodgroup']) ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center;">
                <a class="button-18" style="width:100px;" onclick="updatePD()">Save</a>
                <a class="button-18 button-18gray"  style="width:100px;" onclick="dxHideDialog('updatePD')">Cancel</a>
            </td>
        </tr>
    </table>
</dialog>
<script>
    const dxDisplayName = e$('dxDisplayName')
    const dxLoginName = e$('dxLoginName')
    const dxFname = e$('dxFname')
    const dxMname = e$('dxMname')
    const dxLname = e$('dxLname')
    const dxDOB = e$('dxDOB')
    const dxGender = e$('dxGender')
    const dxBloodGroup = e$('dxBloodGroup')

    function updatePD() {
        rx('updatePD')

        let formData = new FormData()
        formData.append("a", "aec-hrms-api-hrmsEmployeePD")
        formData.append("uid", thisUId)
        formData.append("dxDisplayName", dxDisplayName.value)
        formData.append("dxLoginName", dxLoginName.value)
        formData.append("dxFname", dxFname.value)
        formData.append("dxMname", dxMname.value)
        formData.append("dxLname", dxLname.value)
        formData.append("dxDOB", dxDOB.value)
        formData.append("dxGender", dxGender.value)
        formData.append("dxBloodGroup", dxBloodGroup.value)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] != "T") {
                // Error
                showMessageBox('Error', 'Could not update Employee.<br>' + response[1])
            } else {
                // Success
                rx('ok')
                window.location.reload()
            }
        })
    }
</script>
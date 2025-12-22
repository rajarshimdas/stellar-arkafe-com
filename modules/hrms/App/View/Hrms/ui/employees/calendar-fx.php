<?php
$users = bdGetUsers($mysqli);
$usersById = bdGetUsersById($mysqli);
?>
<table class="rd-table-fx">
    
        <tr>
            <td style="text-align: left; font-family:'Roboto Bold'; color: gray;">
                <?php
                if ($leaveCalendarUserId > 0) {
                    echo $usersById[$leaveCalendarUserId]['displayname'];
                } else {
                    echo 'Displaying All Employees';
                }
                ?>
            </td>
            <td style="width: 300px; text-align:right;">Filter</td>
            <td style="width: 300px;">
                <select name="fxUId" id="fxUId" onchange="leaveCalendarUserId(this.value)">

                    <?php
                    if ($leaveCalendarUserId > 0) {
                        echo '<option value="' . $leaveCalendarUserId . '">' . $usersById[$leaveCalendarUserId]['displayname'] . '</option>';
                    }

                    echo '<option value="0">All/Select Employee</option>';
                    foreach ($users as $u):
                        if ($u['active'] > 0):
                    ?>
                        <option value="<?= $u['user_id'] ?>"><?= $u['displayname'] ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </td>
        </tr>
    
</table>

<script>
    function leaveCalendarUserId(fxUId) {
        // let fxUId = e$("fxUId").value
        rx('fxUId: ' + fxUId);
        // return true;

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveCalendarUserId")
        formData.append('fxUId', fxUId)

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
    }
</script>
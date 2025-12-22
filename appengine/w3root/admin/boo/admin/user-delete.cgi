<form action="sysadmin.cgi" method="POST">
    <input type="hidden" name="a" value="<?php echo $activemenu; ?>">
    <input type="hidden" name="x" value="user-delete">
    <table class="formTBL" style="background:#fff255">
        <tr>
            <td colspan="3">Delete User</td>
        </tr>
        <tr>
            <td width="20%%">Name:</td>
            <td width="60%">
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers("-- Select --", 0, $mysqli,"delete_uid");
                ?>
            </td>
            <td>
                <input type="submit" name="go" value="Delete" style="width:100%">
            </td>
        </tr>
    </table>

</form>
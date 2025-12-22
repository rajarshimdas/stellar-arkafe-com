<form action="sysadmin.cgi" method="POST">
    <input type="hidden" name="a" value="<?php echo $activemenu; ?>">
    <input type="hidden" name="x" value="user-resetpw">
    <table class="formTBL" style="background:#efcaf3">
        <tr>
            <td colspan="3">
			Reset User Password
            </td>
        </tr>
        <tr>
            <td width="20%">Name:</td>
            <td width="60%">
                <?php
                include('boo/admin/comboUsers2.php'); // Hack - include deleted users in the drop down box
                comboUsers("-- Select --", 0, $mysqli);
                ?>
            </td>
            <td><input type="submit" name="go" value="Reset" style="width:100%"></td>
        </tr>
    </table>
    
</form>
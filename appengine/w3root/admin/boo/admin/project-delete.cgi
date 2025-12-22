<form action="sysadmin.cgi" method="POST">
    <input type="hidden" name="a" value="<?php echo $activemenu; ?>">
    <input type="hidden" name="x" value="project-delete">
    <table class="formTBL" style="background:#fff255;">
        <tr>
            <td colspan="3">
                Delete Project
            </td>
        </tr>
        <tr>
            <td width="30%">Project Name:</td>
            <td width="50%">
                <?php
                include('boo/admin/comboProjects.php');
                comboProjects("-- Select --", 0, $mysqli)
                ?>
            </td>
            <td><input type="submit" name="go" value="Delete" style="width:100%"></td>
        </tr>
    </table>
</form>
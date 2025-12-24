<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   09-Jan-09					            |
| Updated On:   12-Aug-10                               |
|               18-Jan-24 Project Coordinator Added     |
+-------------------------------------------------------+
*/
?>
<script type="text/javascript">
    window.onload = function() {
        document.getElementById('pn').focus();
    };
</script>
<style>
    .formTBL tr td {
        border: 0px solid red;
    }
</style>
<form action="sysadmin.cgi" method="POST">

    <input type="hidden" name="a" value="Project New">
    <input type="hidden" name="x" value="project-new">

    <table class="formTBL" style="background:cyan;">
        <tr>
            <td colspan="4">Create New Project</td>
        </tr>
        <tr>
            <td>Project Name</td>
            <td colspan="3">
                <input id="pn" type="text" name="pn">
            </td>
        </tr>
        <tr>
            <td>Jobcode</td>
            <td colspan="3"><input type="text" name="jc"></td>
        </tr>
        <tr>
            <td>Project Leader</td>
            <td colspan="3">
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers("-- Select --", 0, $mysqli, 'pm_uid');
                ?>
            </td>
        </tr>
        <!-- 
        <tr>
            <td>Branch</td>
            <td colspan="3">
                <?php
                include('boo/admin/comboBranch.php');
                comboBranch("-- Select --", 0, $mysqli);
                ?>
            </td>
        </tr> 
        -->
        <tr>
            <td></td>
            <td colspan="3">Project Attribute (optional):</td>
        </tr>
        <tr>
            <td>Project Coordinator</td>
            <td colspan="3">
                <?php
                comboUsers("-- Select (optional) --", 0, $mysqli, 'pc_uid');
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 30%;">Contract Date & Period</td>
            <td style="width: 30%;">
                <input type="date" name='cdt'>
            </td>
            <td style="width: 20%;">
                <select name="cpy" id="cpy">
                    <?php
                    for ($i = 0; $i < 11; $i++) {
                        echo " <option value=" . $i . ">" . $i . " years</option>";
                    }
                    ?>
                </select>
            </td>
            <td style="width: 20%;">
                <select name="cpm" id="cpm">
                    <?php
                    for ($i = 0; $i < 12; $i++) {
                        echo " <option value=" . $i . ">" . $i . " months</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Escalation Start Date</td>
            <td>
                <input type="date" name="esdt">
            </td>
            <td style="text-align: right;">Kickoff:</td>
            <td style="text-align: left;">
                <input type="checkbox" style="width:20px" name="ek">
            </td>
        </tr>
        <tr>
            <td>Escalation Rate</td>
            <td>
                <input type="text" name="erate" value="0">
            </td>
            <td colspan="2">
                <input type="text" name="enote" placeholder="Notes">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="3"><input type="submit" name="go" value="Create" style="width: 150px;"></td>
        </tr>
    </table>

</form>
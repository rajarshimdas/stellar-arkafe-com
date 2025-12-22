<?php

require 'boo/randomPasswd.php';
$randompasswd = randomPasswd(8);

?>
<form action="sysadmin.cgi" method="POST">

    <input type="hidden" name="a" value="<?php echo $activemenu; ?>">
    <input type="hidden" name="x" value="user-add">
    <input type="hidden" name="pw" value="<?php echo $randompasswd; ?>">

    <table class="formTBL" style="background:cyan;">
        <tr>
            <td colspan="2">
                New User
            </td>
        </tr>
        <tr>
            <td width="30%">Loginname:</td>
            <td width="70%"><input type="text" id="loginname" name="lx"></td>
        </tr>

        <tr>
            <td>Display Name:</td>
            <td><input type="text" id="displayname" name="dn"></td>
        </tr>

        <!-- Single Branch -->
        <tr>
            <td>Branch:</td>
            <td>
                <?php
                include('boo/admin/comboBranch.php');
                comboBranch("-- Select --", 0, $mysqli)
                ?>
            </td>
        </tr>

        <!-- Static 
        <tr>
            <td>Department:</td>
            <td>
                <?php
                /*
                include 'boo/admin/comboDepartment.php';
                comboDepartment("-- Select --", 0, $mysqli);
                */
                ?>
                <input type="hidden" name="did" value="3">
                <input type="text" name="dummy" value="Design Studio (Readonly)" readonly>
            </td>
        </tr>
        -->
        <input type="hidden" name="did" value="3">


        <!-- HR Group (17-Jan-2012) -->
        <tr>
            <td>HR Group:</td>
            <td>
                <?php
                include('boo/admin/hrGroup.php');
                hrGroup("-- Select --", 0, $mysqli);
                ?>
            </td>
        </tr>

        <tr>
            <td>Reporting Manager</td>
            <td>
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers("-- Select --", 1, $mysqli, 'rm_uid');
                ?>
            </td>
        </tr>
        <!--
        <tr>
            <td>Monthly Salary</td>
            <td>
                <input type="number" step=".01" name="salary" id="salary">
            </td>
        </tr>

        <tr>
            <td>Annual Incentives</td>
            <td>
                <input type="number" step=".01" name="incentives" id="incentives">
            </td>
        </tr>
        -->
        <tr>
            <td>Date of Joining</td>
            <td>
                <input type="date" name="doj" id="doj">
            </td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="go" value="Add" style="width:150px"></td>
        </tr>

    </table>

</form>
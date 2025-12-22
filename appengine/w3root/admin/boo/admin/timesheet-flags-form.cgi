<form action="engine.cgi" method="POST">
    <input type="hidden" name="a" value="timesheet-flags">


    <table class="formTBL" width="100%" style="background:#ccf255" cellspacing="2px" border="0">
        <tr>
            <td colspan="3" style="background:RGB(150,150,150);color:white;font-weight:bold;font-size:110%">
                Reset Approval Flags
            </td>
        </tr>
        <tr>
            <td style="width: 150px" align="right">Teammate:</td>
            <td style="width: 350px">
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers("-- Select --", 0, $mysqli, 'comboUsers')
                ?>
            </td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td align="right">
                From Date:
            </td>
            <td>
                <input name="dt1" type="date" max="<?= date("Y-m-d") ?>">
            </td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td align="right">
                To Date:
            </td>
            <td>
                <input name="dt2" type="date" max="<?= date("Y-m-d") ?>">
            </td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="submit" name="go" value="Reset Flags" style="width:150px" onclick="javascript:submitFormData();">
            </td>
        </tr>
    </table>

</form>
<div style="text-align: center; padding:10px 0px; font-size:0.9em; color: gray">
    Note: Resetting the approval flags is not reversible.
</div>
 
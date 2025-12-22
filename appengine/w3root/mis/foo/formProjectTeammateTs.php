<?php $dt = date('Y-m-d'); ?>
<table width="100%" cellspacing="4px" cellpadding="4px">
    <form action="<?= $base_url ?>studio/index.cgi" method="POST">
        <input type="hidden" name="a" value="reports-timesheet-projectTeammateTs">
        <tr>
            <td>
                <table width="100%" cellpadding="2" border="0">
                    <tr>
                        <td style="height:45px">Project:</td>
                        <td width="200px">
                            <?php include 'foo/comboProjects.php'; ?>
                        </td>
                        <td width="40px">
                            From:
                        </td>
                        <td width="90px">
                            <input name="fdt" type="date" value="<?= $finStartDate ?>" max="<?= $dt ?>">
                        </td>
                        <td width="25px">
                            To:
                        </td>
                        <td width="90px">
                            <input name="tdt" type="date" value="<?= $dt ?>" max="<?= $dt ?>">
                        </td>
                        <td width="50px">
                            <input type="submit" name="go" value="Get" width="50px">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>
<table width="100%" cellspacing="4px" cellpadding="4px">
    <form action="concert-mis.cgi" method="GET">
        <input type="hidden" name="a" value="projectView">
        <tr>
            <td>
                <table width="100%" cellspacing="" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Project:</td>
                        <td width="250px">
                            <?php include 'foo/comboProjects.php'; ?>
                        </td>
                        <td width="40px" align="right">
                            From:
                        </td>
                        <td width="80px">
                            <input id="fdt1" name="fdt1" type="text" value="-- Select --" style="width:80px">
                        </td>
                        <td width="25px" align="right">
                            To:
                        </td>
                        <td width="80px">
                            <input id="tdt1" name="tdt1" type="text" value="<?php echo date('d-M-y'); ?>" style="width:100%">
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
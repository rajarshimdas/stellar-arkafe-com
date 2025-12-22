<table width="100%" cellspacing="4px" cellpadding="4px">
    <form action="concert-mis.cgi" method="GET">
        <input type="hidden" name="a" value="teammateViewPie">
        <tr>
            <td>
                <table width="100%" cellspacing="" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Teammate:</td>
                        <td width="250px">
                            <?php include 'foo/comboTeammates.php'; ?>
                        </td>
                        <td width="40px" align="right">
                            From:
                        </td>
                        <td width="80px">
                            <input name="fdt2b" type="date" max="<?= date('Y-m-d') ?>">
                        </td>
                        <td width="25px" align="right">
                            To:
                        </td>
                        <td width="80px">
                            <input name="tdt2b" type="date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">

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
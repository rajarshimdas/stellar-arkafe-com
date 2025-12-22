<table width="100%" cellspacing="4px" cellpadding="4px" border="0">
    <form action="concert-mis.cgi" method="GET">
        <input type="hidden" name="a" value="pmProjectCost">
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="2" border="0">
                    <tr>
                        
                        <td align="right" style="height:45px">
                            Project Manager:
                        </td>
                        
                        <td width="200px">
                            <?php include 'foo/comboTeammates.php'; ?>
                        </td>
                        
                        <td width="40px" align="right">
                            From
                        </td>
                        <td width="80px">
                            <input id="fdt4" name="fdt4" type="text" value="-- Select --" style="width:80px">
                        </td>
                        <td width="30px" align="right">
                            To
                        </td>
                        <td width="80px">
                            <input id="tdt4" name="tdt4" type="text" value="-- Select --" style="width:80px">
                        </td>

                        <td width="50px" align="left">
                            <input type="submit" name="go" value="Get">
                        </td>
                        
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>

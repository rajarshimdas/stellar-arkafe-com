<table width="100%" cellspacing="4px" cellpadding="4px">
    <form action="concert-mis.cgi" method="GET">
        <input type="hidden" name="a" value="timeTracker">
        <tr>
            <td width="60%">
                <table width="100%" cellspacing="0" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Time Tracker:</td>
                        <td width="480px">
                            <?php
                            include 'foo/comboProjects.php';
                            ?>
                        </td>                    
                        <td width="50px">
                            <input type="submit" name="go" value="Get">
                        </td>
                    </tr>                    
                </table>
            </td>
        </tr>
    </form>
</table>
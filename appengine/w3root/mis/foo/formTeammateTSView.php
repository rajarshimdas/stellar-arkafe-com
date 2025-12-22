<table width="100%" cellspacing="4px" cellpadding="4px">
    <form id="formTeammateTSView" action="<?= $base_url ?>studio/index.cgi" method="POST">
        <input type="hidden" name="a" value="reports-timesheet-getTimesheets">
        <tr>
            <td>
                <table width="100%" cellspacing="" cellpadding="2" border="0">
                    <tr>
                        <td align="right" style="height:45px">Teammate:</td>
                        <td width="250px">
                            <?php
                            $query = "select id, fullname from users where active = 1 order by fullname";
                            // echo "Q1: $query";
                            ?>
                            <select name="dxUid" style="width:100%">
                                <option value="0">-- Select Teammate --</option>
                                <?php
                                if ($result = $mysqli->query($query)) {
                                    while ($row = $result->fetch_row()) {
                                        echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                                    }
                                    $result->close();
                                }
                                ?>
                            </select>
                        </td>
                        <td width="40px" align="right">
                            From:
                        </td>
                        <td width="80px" align="right">
                            <input name="dxCal1" type="date" value="<?= $finStartDate ?>" max="<?= date('Y-m-d') ?>">
                        </td>
                        <td width="25px" align="right">
                            To:
                        </td>
                        <td width="80px">
                            <input name="dxCal2" type="date" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>">
                        </td>
                        <td width="50px">
                            <input width="50px" type="submit" name="go" value="Get">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>
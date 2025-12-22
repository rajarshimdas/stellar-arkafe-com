<form action="execute.cgi" method="POST" style="background:#E8E9FF;">

    Add Items to the Deliverables List
    <br>&nbsp;<br>
    <input name="a" type="hidden" value="t2xcreate-new">
    <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
    <input type="hidden" name="remark" value="-">
    <input type="hidden" name="revno" value="A">

    <table style="text-align: left; width: 80%; background:#E8E9FF;" border="0" cellpadding="3" cellspacing="0">

        <tr style="color:RGB(120,120,120);font-size: 90%">
            <td width="13%">&nbsp;</td>
            <td align="left" valign="undefined" style="width:13%;">
                &nbsp;Package ID
            </td>
            <td align="left" valign="undefined" style="width:13%;">
                &nbsp;Scope
            </td>
            <td align="left" valign="undefined" style="width:13%;">
                &nbsp;Item No.
            </td>
            <td align="left" valign="undefined" style="width:13%;">
                &nbsp;Part (Optional)
            </td>
            <td style="width:5%">&nbsp;</td>
        </tr>

        <tr>
            <td align="right">
                Itemcode:
            </td>
            <td align="undefined" valign="bottom">
                <select name="identity" style="width:100%">
                    <option value="0">-- Select --</option>
                    <?php
                    $query = "select blockno, blockname from blocks where project_id = $projectid and active = 1";
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_row()) {
                            echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                        }
                        $result->close();
                    }
                    ?>
                </select>
            </td>
            <td align="undefined" valign="bottom">
                <select name="disciplinecode" style="width:100%">
                    <option>-- Select --
                        <?php

                        $d = bdDisciplineArray($mysqli);

                        for ($i = 0; $i < count($d); $i++) {
                            echo '<option value="' . $d[$i]["disciplinecode"] . '">' . $d[$i]["disciplinecode"] . ' - ' . $d[$i]["discipline"] . '</option>';
                        }

                        ?>
                </select>
            </td>
            <td align="undefined" valign="bottom">
                <input type="text" name="unit" style="width:100%">
            </td>
            <td align="undefined" valign="bottom">
                <input type="text" name="part" style="width:100%">
            </td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td align="right">
                Title
            </td>
            <td colspan="4">
                <input type="text" name="title" style="width:100%">
            </td>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td align="right">
                Milestone:
            </td>
            <td colspan="2">
                <select name="stage" style="width: 100%">
                    <option value="0">-- Select --</option>
                    <?php
                    $query = "select stageno, name, sname from projectstage where active = 1 order by stageno";
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_row()) {
                            echo '<option value="' . $row[0] . '">' . $row[2] . ' - ' . $row[1] . '</option>';
                        }
                        $result->close();
                    }
                    ?>
                </select>
            </td>
            <td colspan="2">
                &nbsp;
                <!-- <input id="targetdt" name="targetdt" type="text" value="R0 Target date" style="width: 100%"> -->
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center" colspan="4">
                <input name="submit" type="submit" value="Create New Drawing" style="width:250px">
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align: center; color: green;" colspan="4">
                <?php /* Display query successful message */
                if (isset($_GET['b'])) {
                    if ($sheetno = $_GET['b']) echo "$sheetno added successfully.";
                }
                ?>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>

</form>
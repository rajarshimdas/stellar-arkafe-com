<?php /* Wizard - Step 1
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007			                    |
| Updated On: 14-Feb-2012     				            |
+-------------------------------------------------------+
*/
$mysqli = cn1();

// Check if a session has an active transmittal
$row_cnt = 0;
$query = "select 
            id, 
            wizardstepno
        from 
            tmheader
        where 
            sessionid = '$sessionid' and active = 1
        order by id desc limit 1";

if ($result = $mysqli->query($query)) {

    $row_cnt = $result->num_rows;
    // printf("Result set has %d rows.\n", $row_cnt);
    $row            = $result->fetch_row();
    $tmid           = $row[0];
    $wizardstepno   = $row[1];
    $result->close();
}

if ($row_cnt > 0) {
    // Show the Transmital Step no (2|3)
    if ($wizardstepno > 2) {
        include 'create-3.cgi';
    } else {
        include 'create-2.cgi';
    }
} else {
    // Show the Create Transmittal Wizard Step 1
?>
    <form action="execute.cgi" method="POST">
        <input type="hidden" name="a" value="t3xcreate-1a">
        <input type="hidden" name="sx" value="<?php echo $sessionid; ?>">
        <table class="inputForm" width="100%" border="0">
            <tr align="center" valign="top">
                <td width="20%" align="center">
                    Create Transmittal Wizard<br>Step 1
                    <table border="0">
                        <tr>
                            <td height="10px"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="right" style="width:50%;">Jobcode:</td>
                            <td>
                                <input type="text" name="sn" value="<?= $jobcode; ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Date:</td>
                            <td>
                                <input type="text" name="sn" value="<?= date('d-M-y'); ?>" readonly>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="65%" align="center">
                    <table width="100%" border="0">
                        <tr>
                            <td width="30%" align="right">To:</td>
                            <td width="70%">
                                <select name="to" style="width:100%">
                                    <option>-- Select Name --
                                        <?php
                                        $sql = "select contact from transname where project_id = $projectid and active = 1 order by contact";
                                        if ($result = $mysqli->query($sql)) {
                                            while ($row = $result->fetch_row()) {
                                                echo "<option>$row[0]</option>";
                                            }
                                            $result->close();
                                        } else echo "Error: $mysqli->error";
                                        $mysqli->close();
                                        ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">By:</td>
                            <td>
                                <select name="by" style="width:100%">
                                    <option>-- Sent by --
                                    <option>Courier
                                    <option>Hand Delivery
                                    <option>Fax
                                    <option>Email
                                    <option>Others
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                We are sending you the following for:
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <table width="100%" style="font-size:85%;" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="22%">
                                            <input name="ck7" type="checkbox">Originals
                                        </td>
                                        <td width="44%">
                                            <input name="ck1" type="checkbox">For Approval/Comments
                                        </td>
                                        <td width="34%">
                                            <input name="ck10" type="checkbox">Tenders
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="ck8" type="checkbox">Prints
                                        </td>
                                        <td>
                                            <input name="ck3" type="checkbox">For Information
                                        </td>
                                        <td>
                                            <input name="ck11" type="checkbox">Sanction Drawings
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="ck9" type="checkbox">Soft Copy
                                        </td>
                                        <td>
                                            <input name="ck6" type="checkbox">Good For Construction
                                        </td>
                                        <td>
                                            <input name="ck12" type="checkbox">Shop Drawings
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Remark:</td>
                            <td>
                                <textarea name="remark" style="width:100%; height:50px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Starting Item Serial No:</td>
                            <td>
                                <input type="text" name="srno" style="width:100%;" value="1">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">&nbsp;</td>
                            <td align="center">
                                <input type="submit" name='submit' value="Next >>" style="width:100px;">
                            </td>

                        </tr>
                    </table>

                </td>
                <!-- add a new user -->
                <td align="left" style="padding-top: 6px">
                    <a href="execute.cgi?a=t3xcontact-new&sx=<?php echo $sessionid; ?>" class="button">Add New Contact</a>
                </td>
            </tr>
        </table>
    </form>
<?php

}
?>
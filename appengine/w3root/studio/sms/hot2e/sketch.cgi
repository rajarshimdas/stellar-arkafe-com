<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 14-Jan-08					                |
+-------------------------------------------------------+
| sketch tracking register				                |
+-------------------------------------------------------+
*/

$mysqli = cn1();

// Get the next sketch no
$sql = "select
            sketchno
        from
            sketches
        where
            project_id = $projectid
        order by
            id DESC
        limit 1";
//echo "$sql;";

if ($result = $mysqli->query($sql)) {

    $row = $result->fetch_row();
    $sketchno = $row[0];

    $result->close();
} else {
    echo "Error: $mysqli->error";
}

if (!$sketchno) $sketchno = 1; else $sketchno++;

?>
<table style="text-align: left; width: 100%;" border="0"  cellpadding="2" cellspacing="0">
    <tr>

    <form action="execute.cgi" method="POST" onsubmit="return formValidate();">
        <input type="hidden" name="a" value="t2xsketch-action">
        <input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?> readonly>
        <input type="hidden" name="sketchno" value="<?php echo $sketchno; ?>">
        <td align="center" valign="top" width="70%" style="background:#E8E9FF;">
            <table style="text-align: left; width: 100%;" border="0"  cellpadding="2" cellspacing="0">
                <tr>
                    <td style="text-align:right;width:30%;">
                        Sketch No:
                    </td>

                    <td style='width:70%;'>
                        <input class="inputReadonly" type="text" name="sn" value="<?php echo "SK - $sketchno"; ?>" readonly>
                    </td>
                    <td width="10%">&nbsp;</td>
                </tr>

                <tr>
                    <td style="text-align:right;">
                        Sketch Title:</td>
                    <td>
                        <input id="title" name='title' type='text' style='width:100%;'>
                    </td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td style="text-align:right;">Package:</td>
                    <td>
                        <select id="bno" name="bno" style="width:100%;">
                            <option value="-- Select Package --">-- Select Package --</option>
                            <?php /* Blockno down box */

                            $sql = "select
                                        blockno,
                                        blockname
                                    from
                                        blocks
                                    where
                                        project_id = $projectid and
                                        active = 1";

                            if ($result = $mysqli->query($sql)) {

                                while ($row = $result->fetch_row()) {
                                    echo "<option value='$row[0]'>$row[1]</option>";
                                }
                                $result->close();

                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">Disciplinecode:</td>
                    <td>
                        <select id="dc" name="dc"  style="width:100%;">
                            <option value="-- Select Discipline --">-- Select Discipline --</option>
                            <?php /* Disciplinecode drop down box */

                            $sql = "select
                                        disciplinecode,
                                        discipline
                                    from
                                        discipline
                                    order by
                                        id";

                            if ($result = $mysqli->query($sql)) {

                                while ($row = $result->fetch_row()) {
                                    echo "<option value='$row[0]'>$row[0] - $row[1]</option>";
                                }

                                $result->close();

                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right;">
                        Sent To:</td>
                    <td>
                        <!--<input name='sentto' type='text' style='width:70%;'>-->
                        <select id="sentto" name="sentto" style="width:70%">
                            <option value="-- Select Name --">-- Select Name --</option>
                            <?php
                            $sql = "select
                                        contact
                                    from
                                        transname
                                    where
                                        project_id = $projectid and
                                        active = 1";

                            if ($result = $mysqli->query($sql)) {

                                while ($row = $result->fetch_row()) {
                                    echo "<option>$row[0]</option>";
                                }
                                $result->close();

                            }
                            ?>
                        </select>
                        <select id="sentby" name='sentby' style='width:29%;'>
                            <option>-- Sent by --</option>
                            <option>Courier</option>
                            <option>Hand Delivery</option>
                            <option>Fax</option>
                            <option>Email</option>
                            <option>Others</option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align:right;vertical-align:top;">
                        Remark</td>
                    <td style='text-align:center;'>
                        <textarea id="remark" name='remark' rows="3"  style="width:100%;"></textarea></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align:right;">
                        &nbsp;</td>
                    <td style='text-align:center;'>
                        <input type='submit' name='go' value='Save Sketch Details'></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </form>
</tr>
</table>

<?php
/* Show the list of Sketches */
$bno 	= "-- All / Select Block --";
$dc 	= "-- All / Select Discipline --";
include("sketch-display-list.cgi");
?>
<script type="text/javascript">

    $().ready(function(){
        $('#title').focus();
    });

    // Form Validation
    function formValidate(){

        if ($('#title').val() === ""){
            alert("Enter Title.");
            $('#title').focus();
            return false;
        }

        if ($('#bno').val() === "-- Select Package --"){
            alert("Select Package.");            
            return false;
        }

        if ($('#dc').val() === "-- Select Discipline --"){
            alert("Select Discipline.");
            return false;
        }

        if ($('#sentto').val() === "-- Select Name --"){
            alert("Select Sent To.");            
            return false;
        }

        if ($('#sentby').val() === "-- Sent by --"){
            alert("Select Sent by.");            
            return false;
        }

        // All done, send to server for login...
        return true;

    };

</script>

<?php /* Wizard - Step 2
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Jan-2007				|
| Updated On: 10-Oct-2012     				|
+-------------------------------------------------------+
| Reads the $tmTotalItems from global configuration     |
| file 'LocalSettings.php'                              |
+-------------------------------------------------------+
*/

// Get the tmheader table id
$tmid = $_GET['tmid'];


// Still no tmid? Die.
if (!$tmid) {
    die("Error: tmid is not available...");
}

// Get the transmittal items
$query = "select
            itemcode,
            item,
            nos,
            description,
            srno
        from
            tmlist
        where
            tmheader_id = $tmid and
            active=1
        order by
            srno";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {
        $tX[] = array(
            'itemcode'      => $row[0],
            'item'          => $row[1],
            'nos'           => $row[2],
            'description'   => $row[3],
            'srno'          => $row[4]
        );
    }
    $result->close();
}

$co_tX = isset($tX) ? count($tX) : 0;

?>
<style>
    #ui-id-1 {
        font-size: 0.7em;
    }
</style>
<table class="inputForm" width="100%" border="0">
    <form action="execute.cgi" method="POST">
        <input type="hidden" name="a" value="t3xcreate-2a">
        <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
        <input type="hidden" name="tmid" value="<?php echo $tmid; ?>">
        <input type="hidden" name="pid" id="pid" value="<?php echo $project_id; ?>">
        <tr align="center" valign="top">
            <td width="35%" align="center">
                <?php
                $query = "select contact, startingsrno from tmheader where id = $tmid";
                if ($result = $mysqli->query($query)) {
                    $row = $result->fetch_row();
                    $contact        = $row[0];
                    $startingSrNo   = $row[1];
                    $result->close();
                } else {
                    echo "Error: $mysqli->error";
                }
                echo "Transmittal To: $contact<br>&nbsp;<br>";
                ?>


                <!-- Add Drawings -->
                <?php if ($co_tX < $tmTotalItems) { ?>
                    <table border="0" width="95%" style="border:solid cadetblue;border-width:1px;" cellspacing="2">
                        <tr>
                            <td colspan="3" align="center">Add Drawings/Documents:</td>
                        </tr>
                        <tr style="font-size:85%;vertical-align:bottom;">
                            <td width="50%" align="left">SheetNo:</td>
                            <td width="25%" align="left">Rev No:</td>
                            <td width="25%" align="left">Nos:</td>
                        </tr>
                        <tr style="vertical-align:top;">
                            <td>
                                <input id="sheetno" type="text" name="sheetno" style="width:100%">
                            </td>
                            <td><input type="text" name="revno" value="R0" style="width:100%"></td>
                            <td><input type="text" name="nos1" value="1" style="width:100%"></td>
                        </tr>
                        <tr style="vertical-align:top">
                            <td colspan="3" align="center">
                                <input type="submit" name="submit" value="Add Drawing to List" style="width:170px">
                            </td>
                        </tr>
                    </table>
                    <br>


                    <!-- Add Other items -->
                    <table border="0" width="95%" style="border:1px solid cadetblue;border-width:1px;" cellspacing="2">
                        <tr>
                            <td colspan="4" height="25px" align="center">Add Other Items:</td>
                        </tr>
                        <tr style="font-size:85%;vertical-align:bottom;">
                            <td width="10%" align="left" height="25px">Item:</td>
                            <td width="50%" align="left">

                                <select name='item' style="width:100%">
                                    <option>-- Select --</option>
                                    <?php /* Select Item Combo Box */
                                    $sql = "select item from transitems where id > 10";
                                    if ($result = $mysqli->query($sql)) {
                                        while ($row = $result->fetch_row()) {
                                            echo "<option>$row[0]</option>";
                                        }
                                        $result->close();
                                    }
                                    ?>
                                </select>

                            </td>
                            <td width="10%" align="left">Nos:</td>
                            <td width="30%"><input type="text" name="nos2" value="1" style="width:100%"></td>
                        </tr>
                        <tr style="font-size:85%;vertical-align:bottom;">
                            <td height="25px">Description:</td>
                            <td colspan="3">
                                <input type="text" name="desc" style="width:100%">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="center" height="25px">
                                <input type="submit" name="submit" value="Add Item to List" style="width:170px">
                            </td>
                        </tr>
                    </table>
                    <br>
                <?php
                } else {
                    echo '&nbsp;<br>
                            <div style="width:95%;border:1px solid cadetblue;height:50px;">
                                Transmittal List is Full.
                            </div>
                            <br>&nbsp;';
                }
                ?>


                <!-- Remove items -->
                <table border="0" width="95%" style="border:solid cadetblue;border-width:1px;">
                    <tr>
                        <td colspan="3" align="center">Remove Items:</td>
                    </tr>
                    <tr style="font-size:85%;vertical-align:bottom;">
                        <td width="20%" align="left">Item No:</td>
                        <td width="80%" align="left" colspan="2">
                            <select name="removeno" style="width:100%">
                                <option value="0">-- Select --</option>
                                <?php
                                $no = $startingSrNo;
                                for ($i = 0; $i < $co_tX; $i++) {

                                    $optionValue    = $tX[$i]["srno"];
                                    $optionName     = $tX[$i]["item"];
                                    echo '<option value="' . $optionValue . '">' . $no . '. ' . $optionName . '</option>';
                                    $no++;
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" colspan="3" style="text-align:center;">
                            <input type="submit" name="submit" value="Remove from List" style="width:170px">
                        </td>
                    </tr>
                </table>
                <br>

                <!-- Cancel and Next buttons -->
                <input type="submit" name="submit" value="Cancel" style="width:100px;">
                <input type="submit" name="submit" value="Next >>" style="width:100px;">
                <br>&nbsp;

            </td>

            <td width="60%" align="center">

                <!-- Display the transmittal list -->
                <table class="tmTable" width="98%" border="0" cellspacing="0">

                    <tr class="tmHeader">
                        <td width="8%" class="tmRowC1" style="background: #FFF6F4;">No</td>
                        <td width="25%" class="tmRowC2" style="background: #FFF6F4;">Item</td>
                        <td width="8%" class="tmRowC2" style="background: #FFF6F4;">Nos</td>
                        <td class="tmRowC2" style="background: #FFF6F4;">Description</td>
                    </tr>
                    <?php /* Display the rows of the transmittal */

                    $no = $startingSrNo;

                    for ($i = 0; $i < $co_tX; $i++) {

                        $item = $tX[$i]["item"];
                        if (!$item) $item = "&nbsp;";
                        $nosX = $tX[$i]["nos"];
                        if (!$nosX) $nosX = "&nbsp;";
                        $desc = $tX[$i]["description"];
                        if (!$desc) $desc = "&nbsp;";
                        $srno = $tX[$i]["srno"];
                        /* The next two rows are needed for removing from tmlist */
                        echo "<input type='hidden' name='srno$i' value='$srno'>
                                    <input type='hidden' name='item$i' value='$item'>";
                        /* Display the transmittal row */
                        echo "<tr>
                                <td class='tmRowC1' align='center' valign='center'>$no</td>
                                <td class='tmRowC2' align='left' valign='center'>$item</td>
                                <td class='tmRowC2' align='center' valign='center'>$nosX</td>
                                <td class='tmRowC2' align='left' valign='center'>$desc</td>
                            </tr>";

                        $no++;
                    }
                    // Increment $i by one.
                    $i++;

                    for ($i; $i <= $tmTotalItems; $i++) {
                        echo
                        "<tr>
                            <td class='tmRowC1' align='center' valign='center'>&nbsp;</td>
                            <td class='tmRowC2' align='left' valign='center'>&nbsp;</td>
                            <td class='tmRowC2' align='center' valign='center'>&nbsp;</td>
                            <td class='tmRowC2' align='left' valign='center'>&nbsp;</td>
                        </tr>";
                    } ?>
                </table>
            </td>
        </tr>
    </form>
</table>

<?php $mysqli->close(); ?>

<script type="text/javascript">
    $().ready(function() {
        $('#sheetno').focus();
    })

    $(function() {
        var availableTags = [

            <?php
            $mysqli = cn1();

            $query = 'select sheetno, title from view_drawing_list where project_id = ' . $project_id . ' order by sheetno';
            if ($result = $mysqli->query($query)) {

                while ($row = $result->fetch_row()) {
                    echo '
            "' . $row[0] . ' :: ' . $row[1] . '",';
                }

                $result->close();
            }

            ?>

        ];
        $("#sheetno").autocomplete({
            source: availableTags,
            minLength: 1,
            delay: 500
        });
    });
</script>
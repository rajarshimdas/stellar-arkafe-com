<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 27-Nov-2007				|
| Updated On: 05-Feb-2008				|
+-------------------------------------------------------+
| Class: GFCHistory					|
| Displays GFC Release history				|
+-------------------------------------------------------+
| Update 05-Feb-2008					|
| Bug:	The drawing list was unsorted. Now the drawing	|
|		list is sorted by the sheetno.		| 
+-------------------------------------------------------+
*/

class GFCHistory
{
    /* Properties */


    /* Constructor */
    function __construct($projid)
    {
        $this->ProjID = $projid;
    }

    /* Dispaly form for Block and Discipline code selection */
    function formGFCHistory()
    {

        //echo "ProjID: $this->ProjID";
?>
        <table style='text-align: left; width: 60%;' border='0'>
            <tr>
                <td>Package:</td>
                <td><select id="bn" name="bn" style="width:100%">
                        <option>-- Select --
                        
                        <?php
                        $mysqli = cn1();
                        if (!$mysqli) echo "Error: No MySQLi connection";

                        /* Discipline code dropdown combo box */
                        $sql = "SELECT blockno,blockname FROM blocks where project_id='$this->ProjID' and active=1 ORDER BY blockno";
                        $no = 1;
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_row()) {
                                if ($row[0] !== 'MP') {
                                    echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                                }
                            };
                            $result->close();
                        } else {
                            printf("Error: %s\n", $mysqli->error);
                        }
                        ?>

                    </select></td>
            </tr>
            <tr>
                <td>Disciplinecode:</td>
                <td><select id="dc" name="dc" style="width:100%">
                        <?php echo "<option>-- Select --";
                        /* Discipline code dropdown combo box */
                        $sql = "SELECT disciplinecode, discipline FROM discipline ORDER BY disciplinecode";
                        $no = 1;
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_row()) {
                                echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                            };
                            $result->close();
                        } else {
                            printf("Error: %s\n", $mysqli->error);
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style='text-align:center'>
                    <input type="submit" name="submit" value="Display GFC History">
                </td>
            </tr>

        </table>


    <?php
        $mysqli->close();
    } /* Close function formGFCHistory */


    /* Generate and Display the GFC History */
    function displayGFCHistory()
    {

        $blockno        = $_GET['bn'];
        $disciplinecode = $_GET['dc'];
        $no                = 1;

        if ($blockno === "-- Select --" || $disciplinecode === "-- Select --") {
            header("Location:rajarshi.cgi?a=t2xview&selectoption=GFC+Release+History&submit=Go");
            die;
        }

        include 'arnav/angels.cgi';
        if (!$mysqli) echo "Error: No MySQLi connection";

        $sql = "select blockname from blocks where blockno = '$blockno' and project_id = $this->ProjID and active = 1";

        if ($result = $mysqli->query($sql)) {
            $row = $result->fetch_row();
            $blockname = $row[0];
            $result->close();
        } else {
            printf("Error: %s\n", $mysqli->error);
        }

        echo "<br>$blockno - $blockname";

        /* Generate the Header Row */ ?>

        <table class="tabulation" border="0" cellspacing="0" style='text-align:center; font-size:13px; font-weight:normal; width:875px;'>

            <tr class="headerRow">
                <td class="headerRowCell1" style="width:25;">No</td>
                <td class="headerRowCell2" style="width:80;">Sheet No</td>
                <td class="headerRowCell2" style="width:110;">Title</td>
                <td class="headerRowCell2" style="width:60">R0</td>
                <td class="headerRowCell2" style="width:60">R1</td>
                <td class="headerRowCell2" style="width:60">R2</td>
                <td class="headerRowCell2" style="width:60">R3</td>
                <td class="headerRowCell2" style="width:60">R4</td>
                <td class="headerRowCell2" style="width:60">R5</td>
                <td class="headerRowCell2" style="width:60">R6</td>
                <td class="headerRowCell2" style="width:60">R7</td>
                <td class="headerRowCell2" style="width:60">R8</td>
                <td class="headerRowCell2" style="width:60">R9</td>
                <td class="headerRowCell2" style="width:60">R10</td>
            </tr>

            <tr class="dataRow">
                <?php

                $sql = "select
                            id,
                            concat(dwgidentity,'-',disciplinecode,'-',unit,part) as sheetno,
                            currentrevno,
                            title
                        from
                            dwglist
                        where
                            project_id = $this->ProjID and
                            dwgidentity = '$blockno' and
                            disciplinecode = '$disciplinecode' and
                            r0issuedflag = 1 and
                            active = 1
                        order by
                            sheetno";

                // echo "SQL: $sql";

                if ($result = $mysqli->query($sql)) {

                    // $x = 0;
                    while ($row = $result->fetch_row()) {

                        // Display No
                        echo "<td class='dataRowCell1'>" . $no++ . "</td>";
                        // Display Drawing Number
                        echo "<td class='dataRowCell2'>" . $row[1] . "</td>";
                        // Display Title
                        echo "<td class='dataRowCell2' style='font-size:10;text-align:left;'>" . $row[3] . "</td>";


                        /* Generate Row for this drawing */
                        for ($i = 0; $i <= 10; $i++) {

                            $sql5 = "select
                                         dtime
                                    from
                                        dwghistory
                                    where
                                        newrevno = 1 and
                                        revno = 'R$i' and
                                        dwglist_id = $row[0]";

                            //echo "$sql5";

                            if ($result5 = $mysqli->query($sql5)) {
                                $row5 = $result5->fetch_row();
                                $dateX = $row5[0];

                                $dx = $this->dateformat($dateX);

                                if ($dateX) {
                                    // echo "<br>$row[0] - $row[1]-R$i <$dx>";
                                    echo "<td class='dataRowCell2'>$dx</td>";
                                } else {
                                    echo "<td class='dataRowCell2'>&nbsp;</td>";
                                }


                                $result5->close();
                            } else {
                                printf("Error: %s\n", $mysqli->error);
                            }
                        }

                        echo "</tr>";
                    };
                    $result->close();
                } else {
                    printf("Error: %s\n", $mysqli->error);
                }
                ?>
        </table>
        <br>
<?php
        $mysqli->close();
    } /* Close function displayGFCHistory */


    function dateformat($str)
    {

        if (($timestamp = strtotime($str)) === false) {

            return false;
        } else {

            return date('d-M-y', $timestamp);
        }
    }/* Close function dateformat */
} /* Close Class GFCHistory */

?>
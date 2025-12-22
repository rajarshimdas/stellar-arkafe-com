<?php /*

Rajarshi Das
Created On: 	10-Dec-2007
Last Updated:	20-Dec-2007 

+-----------------------------------------------------------------------+
| Summary - Display the drawing history					|
+-----------------------------------------------------------------------+
*/

$dwglist_id = $_GET["id"];

include 'foo/arnav/angels.cgi';
include 'foo/t2DWGid.php';

$dx = new DWGid($projectid, $roleid);
$a = $dx->GetDWGDetails($dwglist_id, $mysqli);

echo "Displaying Drawing Summary for: " . $a["sheetno"] . "<br>&nbsp;";

/* Display the Drawing Defination */
echo "<span style='font-weight:bold;'><br>Details<br></span>";
$dx->DWGListTableHeader(1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 850);
$dx->DWGListTableRow(1, $a);
echo "</table><br>";

/* Display the Transmittal Release History */
echo "<span style='font-weight:bold;'><br>Transmittals</span>";

$sql = "select 
            remark,
            revno,
            DATE_FORMAT(dtime,'%d-%b-%y'),
            loginname
        from
            dwghistory
        where
            dwglist_id = $dwglist_id and
            scaleina1 = 'Transmittal'
        order by dtime DESC";

if ($result = $mysqli->query($sql)) {

    $numrows = $result->num_rows;

    if ($numrows < 1) {

        /* No Transmittals Created */
        echo "<br>+++ No Transmittals +++";
    } else {

        /* Display Transmittal Details */
?>
        <!-- PopUp window displaying printable format transmittals -->
        <script language="JavaScript">
            function POPUp(URL) {
                day = new Date();
                id = day.getTime();
                eval("page" + id + " = window.open(URL,'" + id + "','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600,left=112,top=90');");
            }
        </script>
        <table class="tabulation" style="text-align: center;" border="0" cellpadding="2" cellspacing="0">
            <tr class="headerRow" height=20px; style="background:#FFF6F4;">
                <td class="headerRowCell1" width="45px">
                    No
                </td>
                <td class="headerRowCell2" width="100px">
                    Transmittal<br>Number
                </td>
                <td class="headerRowCell2" width="150px" align="left">
                    &nbsp;Sent To
                </td>
                <td class="headerRowCell2" width="80px">
                    RevNo
                </td>
                <td class="headerRowCell2" width="100px">
                    Date
                </td>
                <td class="headerRowCell2" width="60px;">
                    &nbsp;
                </td>
            </tr>

            <?php
            $no = 1;

            while ($row = $result->fetch_row()) {

                /* Transmittal No */
                $x         = explode('TmNO:', $row[0]);
                $y         = explode('+', $x[1]);
                $TransNo   = $y[0];

                /* Sent To */
                $x         = explode('SentTo:', $row[0]);
                $SentTo        = $x[1];

                $RevNo        = $row[1];
                $dtmie        = $row[2];
                $SentBy        = $row[3];
                //echo "<br>TransNo: ".$TransNo." SentTo: $SentTo SentBy: $row[3] RevNo: $RevNo Date: $row[2]";

                echo "<tr class='dataRow'>";
                echo "<td class='dataRowCell1'>" . $no++ . "</td>";
                echo "<td class='dataRowCell2'>&nbsp;$TransNo&nbsp;</td>";
                echo "<td class='dataRowCell2' align='left'>&nbsp;$SentTo&nbsp;</td>";
                echo "<td class='dataRowCell2'>&nbsp;$RevNo&nbsp;</td>";
                echo "<td class='dataRowCell2'>&nbsp;$dtmie&nbsp;</td>";
            ?>
                <td class='dataRowCell2'>
                    <input type='button'
                        value='show'
                        onclick="javascript:POPUp('hot3e/PrintTM.cgi?<?php echo "a=$projectid&b=$TransNo"; ?>')"
                        style="width:100%">
                </td>
    <?php
                echo "</tr>";
            }

            $result->close();
        }
    } else {
        echo "Error: $mysqli->error";
    }

    /* Display the Editing History
    echo "<span style='font-weight:bold;'><br><br>Editing History<br></span>";
    */

    $mysqli->close();

    ?>

        </table>
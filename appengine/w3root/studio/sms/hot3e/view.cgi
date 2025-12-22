<?php  /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 2007					                    |
| Updated On: 12-Oct-2012				                |
+-------------------------------------------------------+
*/
$no_of_transmittals = isset($_GET["no"])?$_GET["no"]:1;
?>
<div style="background-color: #e8e9ff;padding: 10px 0 10px 0;">
<?php
if (!$no_of_transmittals) {
    echo 'Transmittals [ <span style="font-weight:bold;">Last 10</span> | <a href="project.cgi?a=t3xview&no=all" style="text-decoration:none;color:black;">All</a> ]<br>&nbsp;<br>';
} else {
    echo 'Transmittals [ <a href="project.cgi?a=t3xview" style="text-decoration:none;color:black;">Last 10</a> | <span style="font-weight:bold;">All</span> ]<br>&nbsp;<br>';
}
?>
</div>

<table class="tabulation" border="0" cellpadding="2" width="100%">
    <tr class="headerRow">
        <td class="headerRowCell1" style="text-align: center;">&nbsp;No</td>
        <td class="headerRowCell2">&nbsp;Sent To</td>
        <td class="headerRowCell2">&nbsp;Created By</td>
        <td class="headerRowCell2">&nbsp;Date</td>
        <td class="headerRowCell2">&nbsp;</td>
        <!-- <td>&nbsp;</td> -->
    </tr>

    <?php /* Display the Transmittal list */

    if (!$no_of_transmittals) {
        $limit = "limit 10";
    } else {
        $limit = "";
    }

    $sql = "select
                id,
                transno,
                contact,
                loginname,
                dtime,
                active
            from
                transmittals
            where
                project_id = $projectid
                order by
                transno desc
            $limit";

    include 'foo/arnav/angels.cgi';

    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_row()) {

            $tmid 	= $row[0];
            $transno 	= $row[1];
            $contact 	= $row[2];
            $sentby	= $row[3];
            $dt		= dateformat($row[4]);
            $active	= $row[5];

            if($active < 1) {
                $color = '#B7B7B7';
                $delky = 'deleted';
            } else {
                $color = 'black';
                $delky = "<input type='submit' name='submit' value='delete'>";
            }

            echo "<tr class='dataRow' style='text-align:center;color:$color;'>
                    <td class='dataRowCell1' width='10%'>$transno</td>
                    <td class='dataRowCell2' width='25%' align='left'>&nbsp;$contact</td>
                    <td class='dataRowCell2' width='25%' align='left'>&nbsp;$sentby</td>
                    <td class='dataRowCell2' width='20%' align='left'>$dt</td>
                    <td class='dataRowCell2' width='10%'>";
            ?>
    <!--
    <input type="button"
           value="Show"
           onclick="javascript:showTransmittal('hot3e/PrintTM.cgi?<?php echo "tmid=$tmid"; ?>')"
           >
    -->
    <img src="/da/icons/32/print.png" alt="Show" title="Show / Print"
         onclick="javascript:showTransmittal('hot3e/PrintTM.cgi?<?php echo "tmid=$tmid"; ?>')"
         style="cursor: pointer">

               <?php
                   echo '</tr>';
               }

               $result->close();
           }

           $mysqli->close();
           ?>
</table>

<!-- PopUp window displaying printable format transmittals -->
<script type='text/javascript' src='/matchbox/mbox/rajarshi/PopUp.js'></script>
<script type="text/javascript">
    function showTransmittal(URL) {
        PopUp( URL, 800, 500 );
    }
</script>

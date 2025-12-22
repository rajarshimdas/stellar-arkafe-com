<?php
//echo "sketch-view.cgi";
include 'foo/arnav/angels.cgi';
?>
<form action="project.cgi" method="GET">
    <input type="hidden" name="a" value="t2xsketch-display-list">
    <table style="text-align: left; width: 100%;" border="0"  cellpadding="2" cellspacing="0">
        <tr style="background:#E8E9FF;">
            <td width="35%" style="text-align:right;">
			Display Sketches for:
            </td>

            <td width="45%" style="text-align:center;"> <!-- Select Block and Disciplinecode combo box -->


                <select name="bno" style="width:48%;"><option>-- All / Select Block --
                        <?php /* Blockno down box */

                        $sql = "select blockno from blocks where project_id = $projectid and active = 1";
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_row()) {
                                echo "<option>$row[0]";
                            }
                            $result->close();
                        } else echo "Error: $mysqli->error";
                        ?>
                </select>

                <select name="dc"  style="width:48%;"><option>-- All / Select Discipline --
                        <?php /* Disciplinecode drop down box */

                        $sql = "select disciplinecode from discipline";
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_row()) {
                                echo "<option>$row[0]";
                            }
                            $result->close();
                        } else echo "Error: $mysqli->error";
                        ?>
                </select>
            </td>

            <td width="20%" style="text-align:left;">
                <input type="submit" name="Go" value="Go" style="width:50px;">
            </td>

        </tr>
    </table>
</form>	
<?php	
$mysqli->close();
?>

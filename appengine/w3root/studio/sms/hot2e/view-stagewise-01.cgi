<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-Feb-08					                |
| Updated On:						                    |
+-------------------------------------------------------+
| Stagewise Drawing List selection Form			        |
+-------------------------------------------------------+
*/
include('foo/arnav/angels.cgi');
?>
<table>
    <form action="project.cgi" method="GET" onsubmit="return stagewiseDataValidate();">
        <input name="a" type="hidden" value="t2xview-stagewise-02">
        <tr>
            <td align="right">Stage:</td>
            <td>
                <select id="stageid" name="stageid" style="width:300px;">
                    <option value="0">-- Select Stage --</option>
                    <?php
                    $query  = "select id, stageno, name from projectstage where active = 1 order by stageno";
                    if ($result = $mysqli->query($query)) {

                        while ($row = $result->fetch_row()) {
                            echo '<option value="' . $row[0] . '">' . $row[1] . '. ' . $row[2] . '</option>';
                        }

                        $result->close();
                    }
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td align="right">Discipline:</td>
            <td>
                <select id="dc" name="dc" style="width:300px;">
                    <option>- Select/All -
                        <?php /* Disciplinewise */
                        $query = "select disciplinecode, discipline from discipline order by id";
                        if ($result = $mysqli->query($query)) {

                            while ($row = $result->fetch_row()) {
                                //echo "<option>$row[0] - $row[1]";
                                echo '<option value="' . $row[0] . '">' . $row[0] . ' - ' . $row[1] . '</option>';
                            }

                            $result->close();
                        }
                        $mysqli->close();
                        ?>
                </select>

            </td>
        </tr>

        <tr>
            <td align="right">&nbsp;</td>
            <td align="center"><input type="submit" name="x" value="Show Drawing List" style="width:80px;"></td>
        </tr>
    </form>
</table>
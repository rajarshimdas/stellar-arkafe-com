<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 07-Jan-09         			|
| Updated On: 20-Oct-10                       		|
+-------------------------------------------------------+
*/

function comboProjects($visibleOptionText,$visibleOptionValue,$mysqli) {

    $query = "select id, projectname, jobcode from projects where active = 1 order by jobcode";
    ?>

    <select id="comboProjects" name="pid">
        <?php
        /* Visible Option */
        echo '<option value="'.$visibleOptionValue.'">'.$visibleOptionText.'</option>';

        /* Hidden Options */
        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {
                echo "<option value='".$row[0]."'>".$row[2].' - '.$row[1]."</option>";
            }
            $result->close();

        }
        ?>

    </select>

    <?php
}
?>
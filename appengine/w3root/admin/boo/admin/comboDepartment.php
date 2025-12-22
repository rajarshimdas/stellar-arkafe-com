<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 20-Oct-09         			|
| Updated On:                           		|
+-------------------------------------------------------+
*/
function comboDepartment($visibleOptionText, $visibleOptionValue, $mysqli) {

    $query = "select id, name from department where active = 1 order by name";
    ?>

    <select id="comboDepartment" name="did">
        <?php
        /* Visible Option */
        echo '<option value="'.$visibleOptionValue.'">'.$visibleOptionText.'</option>';

        /* Hidden Options */
        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                if ($row[0] !== $visibleOptionValue) {
                    echo "<option value='".$row[0]."'>".$row[1]."</option>";
                }
            }
            $result->close();
        }
        ?>

    </select>
    <?php
}
?>
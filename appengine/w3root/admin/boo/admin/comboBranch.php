<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 19-Oct-09         			            |
| Updated On: 20-Oct-10                        		    |
+-------------------------------------------------------+
*/
function comboBranch($visibleOptionText, $visibleOptionValue, $mysqli) {

    $query = "select
                id,
                branchname
            from
                branch
            where
                active = 1
            order by
                branchname";
    ?>

    <select id="comboBranch" name="bid">
        <?php
        /* Visible Option */
        echo '<option value="'.$visibleOptionValue.'">'.$visibleOptionText.'</option>';

        /* Hidden Options */
        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                if ($row[0] !== $visibleOptionValue && $row[0] !== 1) {
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
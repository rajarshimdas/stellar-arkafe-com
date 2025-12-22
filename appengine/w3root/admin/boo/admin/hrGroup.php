<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 07-Jan-09					                |
| Updated On: 10-Jan-24					                |
+-------------------------------------------------------+
*/
function hrGroup($visibleOptionText,$visibleOptionValue,$mysqli) {

    $domain_id = 2; // Fixed Temporarily

    $query = "select `id`, `name` from userhrgroup where active = 1 order by displayorder asc";

    ?>

<select name="hid">

        <?php

        /* Visible Option */
        echo "<option value='$visibleOptionValue'>$visibleOptionText</option>";

        /* Hidden Options */
        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                echo "<option value='".$row[0]."'>".$row[1]."</option>";

            }
            $result->close();
            ?>
</select>

        <?php
    }

}

?>
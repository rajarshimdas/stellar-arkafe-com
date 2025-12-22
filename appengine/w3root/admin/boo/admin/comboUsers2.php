<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 07-Jan-09					|
| Updated On: 20-Oct-10					|
+-------------------------------------------------------+
| Same as comboUsers except returns deleted users as well
*/
function comboUsers($visibleOptionText,$visibleOptionValue,$mysqli) {

    $domain_id = 2; // Fixed Temporarily

    $query = "select
                t1.id,
                t1.loginname ,
                t2.domainname
            from
                users as t1,
                domain as t2
            where                
                t1.domain_id = t2.id and
                t1.domain_id = $domain_id
            order by
                t1.fullname";

    ?>

    <select name="uid">

        <?php

        /* Visible Option */
        echo "<option value='$visibleOptionValue'>$visibleOptionText</option>";

        /* Hidden Options */
        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                if ($row[2] !== 'sysadmin' && $row[0] !== $visibleOptionValue) {

                    echo "<option value='".$row[0]."'>".$row[1]." (".$row[1]."@".$row[2].")</option>";
                }

            }
            $result->close();
            ?>
    </select>

        <?php
    }

}

?>
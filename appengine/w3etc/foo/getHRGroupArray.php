<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 29-Jun-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/
function getHRGroupArray ($mysqli) {

    $query = 'select `id`,`name` from `userhrgroup` where `active` = 1 order by `displayorder`';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $rX[] = array("id" => $row[0], "hrgroup" => $row[1]);
        }

        $result->close();
    }

    return $rX;

}

?>

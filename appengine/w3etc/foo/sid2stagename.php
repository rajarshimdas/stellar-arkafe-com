<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Feb-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/
function sid2stagename($sid,$mysqli) {

    $query = 'select `name` from projectstage where id = '.$sid;

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $sname = $row[0];

        $result->close();
    }

    return $sname;
}

?>
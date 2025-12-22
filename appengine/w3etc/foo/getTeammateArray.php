<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 02-Feb-2012				|
| Updated On:           				|
+-------------------------------------------------------+
*/
function getTeammateArray($mysqli) {

    // $query = 'select id,loginname,fullname from users where active = 1 order by fullname';


    $query = 'select id, loginname, fullname from users order by fullname';
    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            // printf ("%s (%s)\n", $row[0], $row[1]);
            $x[] = array("id" => $row[0], "ln" => $row[1], "fn" => $row[2]);
        }

        $result->close();
    }

    return $x;

}

?>

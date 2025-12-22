<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
function uid2displayname($uid,$mysqli) {

    $query = 'select fullname from users where id = '.$uid;
    // echo '<br>Q: '.$query;
    
    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $x = $row[0];

        $result->close();
    }

    return $x;
}

?>
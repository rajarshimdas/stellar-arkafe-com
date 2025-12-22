<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 05-Jan-2013				|
| Updated On: 22-Feb-2013     				|
+-------------------------------------------------------+
*/
function getProjectManager($pid,$mysqli) {

    $query = "select user_id from roleinproject where project_id = $pid and roles_id = 10";

    // Get the Project Manager's UID
    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $pm_uid = $row[0];

    }

    // Get details for this user
    $query = "select fullname, emailid from users where id = $pm_uid";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $ProjPMX = array ("uid" => $pm_uid, "fname" => $row[0], "email" => $row[1]);

    }

    return $ProjPMX;

}
?>

<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Jan-09					|
| Updated On: 19-Mar-12                               	|
+-------------------------------------------------------+
*/   $mysqli = cn2();  /*                               |
+-------------------------------------------------------+
*/

$user_id = $_GET["uid"];
$timeUid = $_GET["timeUid"];




$foundFlag = 0;
$query = "select 1 from iouidmap where user_id = $user_id";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {
        $foundFlag++;
    }
    $result->close();
}



if ($foundFlag < 1){
    $query = "insert into iouidmap (user_id, timedesk_uid) value (".$user_id.", ".$timeUid.")";
} else {
    $query = "update iouidmap set timedesk_uid = $timeUid where user_id = $user_id";
}

if ($mysqli->query($query) !== TRUE) {
    echo 'Error in saving File.';
} else {
    echo 'Saved';
}


?>

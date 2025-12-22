<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Jan-09					|
| Updated On: 						|
+-------------------------------------------------------+
*/
$module_id  = $_POST["module"];
$user_id    = $_POST["uid"];

$mysqli     = cn2();

if ($module_id < 1 || $user_id < 1) {
    $m3 = "Input error, try again...";
} else {

    // Check if its already added
    $errorFlag = 0;
    $query = "select 1 from moduletick where modules_id = '$module_id' and user_id = '$user_id'";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $errorFlag = $errorFlag + $row[0];

        $result->close();
    }

    // Add to database
    $query = "insert into moduletick (`modules_id`,`user_id`) values ('" . $module_id . "','" . $user_id . "')";
    if ($errorFlag < 1) {
        $mysqli->query($query);
    }
}

$mysqli->close();

// Page Re-load required
header("Location:" . $base_url . "admin/sysadmin.cgi?a=Module%20Access");
die;

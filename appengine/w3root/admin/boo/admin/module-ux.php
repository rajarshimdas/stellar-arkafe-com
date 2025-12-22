<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 09-Jan-09					|
| Updated On: 						|
+-------------------------------------------------------+
*/
$modules_id     = $_GET["mid"];
$user_id        = $_GET["uid"];
$mysqli         = cn2();

if ($modules_id < 1 || $user_id < 1) {

    die('Error :: Input error, try again...');

} else {

    $query = "delete from moduletick where modules_id = $modules_id and user_id = $user_id";
    $mysqli->query($query);
    $mysqli->close();

    die('Done.') ;

}

?>
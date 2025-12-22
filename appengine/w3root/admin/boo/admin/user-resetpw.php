<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 11-May-09					                |
| Updated On: 						                    |
+-------------------------------------------------------+
*/   $mysqli = cn2(); /*                                |
+-------------------------------------------------------+
*/

require 'boo/randomPasswd.php';
$randompasswd = randomPasswd(8);

$user_id = $_POST["uid"];
//echo "Name: $user_id";

$query = "update users set passwd = '$randompasswd', active = 1 where id = $user_id";
//echo "Q: $query";

if (!$mysqli->query($query)) {
   // printf("Error: %s\n", $mysqli->error); 
   $flag = 2;
   $message = "Password reset failed. Try again...";
}

$flag = 1;
$message = "New password is: $randompasswd";

$mysqli->close();

?>
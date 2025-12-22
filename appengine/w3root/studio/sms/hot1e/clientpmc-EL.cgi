<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 25-Jul-08									|
| Updated On:											|
+-------------------------------------------------------+
| Disable external user's login							|
+-------------------------------------------------------+
*/

$contact_id = $_GET["id"];

include('foo/arnav/dblogin.cgi');
$query = "update user_projects set active = 1 where user_profiles_id = $contact_id";
if (!$mysqli->query($query)) echo "MySQL: $mysqli->error";
$mysqli->close();

header("Location: rajarshi.cgi?a=t1xclientpmc");

?>
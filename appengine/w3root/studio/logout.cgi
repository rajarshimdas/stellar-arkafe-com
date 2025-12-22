<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 10-Sep-08					                |
| Updated On: 						                    |
+-------------------------------------------------------+
| SMS :: Logout						                    |
+-------------------------------------------------------+
*/

require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

// Remove session
session_unset();
session_destroy();

$mysqli = cn2();

// Mark this session closed
$query = "update `sessi0ns` set `active` = 0 where `sessionid` = '$sessionid'";
if (!$mysqli->query($query)) printf("Error: %s\n", $mysqli->error);

$mysqli->close();

// Redirect
header("Location:" . BASE_URL . "login/");

<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Jul-2012       			            |
| Updated On:                                           |
+-------------------------------------------------------+
| FC to run ajax functions                              |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once BD . '/Controller/Common.php';

// Database connection
$mysqli = cn2();

// run the Action Program
include __DIR__ . '/ajax/' . $_GET["a"];

// Close Database connection
$mysqli->close();

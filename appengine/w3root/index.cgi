<?php /* BadDragon1 :: Front Controller
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Bootstrap
if (is_file($_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
} else {
    die("BadDragon1 :: Paths not defined.");
}

// Invoke BadDragon
require_once BD . 'Controller/Bootstrap.php';

// Clean up
if (isset($mysqli)) $mysqli->close();

// All done. Die in peace...
die();

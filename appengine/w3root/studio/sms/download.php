<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 24-Jun-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
| Do not pass any data. Else the file will get corrupt. |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientPaths.cgi';

// Setup paths required by download.php
$baseDir = $w3etc . "/templates/";
$logFile = $w3etc . "/templates/log/downloads.log"; // Not used. Variable required.

require_once $w3etc . '/foo/downloadFile.php';

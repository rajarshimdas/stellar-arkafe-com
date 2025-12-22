<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Aug-09	(Dad's Birthday)		        |
| Updated On: 						                    |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';
require_once 'foo/ci3_form_validation.php';

/* Parse URL */
if (!$a = $_POST["a"]) $a = $_GET["a"];

// include the action file
require 'hot7e/'.$a.'.cgi';

?>
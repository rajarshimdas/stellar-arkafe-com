<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 06-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

$mysqli = cn1();
$tsid = $_POST["tsid"];

// Get the timesheet data
require 'functions.cgi';
$rx = id2data($tsid, $mysqli);
// $rx = [ "fullname" => 'arnav' ];

$mysqli->close();

rdReturnJsonHttpResponse('200',$rx);


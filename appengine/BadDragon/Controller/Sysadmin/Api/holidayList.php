<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$dt = $_POST["dtYear"];

$x = bdGetHolidayList($dt, $mysqli);
$tr = isset($x) ? bdtabulateHolidays($x) : "<!-- No row -->";


rdReturnJsonHttpResponse("200", ['T', $tr]);

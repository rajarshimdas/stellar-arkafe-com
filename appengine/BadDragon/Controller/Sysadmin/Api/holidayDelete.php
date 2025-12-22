<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$hid = $_POST["hid"];
$dt = $_POST["dtYear"];


$query = "delete from `holidays` where `id` = '$hid'";
// rdReturnJsonHttpResponse('200',["F", $query]);

$mysqli = cn2();
if ($mysqli->query($query)) {

    $x = bdGetHolidayList($dt, $mysqli);
    $tr = isset($x) ? bdtabulateHolidays($x) : "<!-- No row -->";

    rdReturnJsonHttpResponse("200", ['T', $tr]);
} else {
    rdReturnJsonHttpResponse("200", ['F', 'Failed to delete holiday. Error: ' . $mysqli->error]);
}

<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$dt         = $_POST["dt"];
$sat        = $_POST["sat"];
$holiday    = ($sat == 1) ? 'Saturday' : $_POST["holiday"];

// Validation
if (!$dt) {
    rdReturnJsonHttpResponse('200', ['F', 'Wrong date. Please try again.']);
}

if (strlen($holiday) < 5){
    rdReturnJsonHttpResponse('200', ['F', 'Holiday name must be 5 characters or more.']);
}

// Holiday is already set
$flag = 0;
$query = "select 1 as `flag`, `holiday` from `holidays` where `dt` = '$dt' and `active` > 0";

$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
  $flag = $row['flag'];
  $h = $row['holiday'];
}

if ($flag > 0){
    rdReturnJsonHttpResponse('200', ['F', 'This date is already listed as a Holiday - ' . $h]);
}

$query = "INSERT INTO `holidays` (`dt`, `holiday`, `branch_id`, `saturday`) VALUES ('$dt', '$holiday', '1', '$sat')";

$mysqli = cn2();
if ($mysqli->query($query)) {

    $x = bdGetHolidayList($dt, $mysqli);
    $tr = isset($x) ? bdtabulateHolidays($x) : "<!-- No row -->";

    rdReturnJsonHttpResponse("200", ['T', $tr]);
} else {
    rdReturnJsonHttpResponse("200", ['F', 'Failed to save holiday. Error: ' . $mysqli->error]);
}

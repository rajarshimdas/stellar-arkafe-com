<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 27-Dec-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once BD . 'Controller/Concert/Concert.php';

$user_id = (int)$_POST['uid'];
$date = $_POST['date'];

$dayManhours = bdGetDayManhours($date, $user_id, $mysqli);
$dayMinutes = bdManhours2Minutes($dayManhours);
$timeOffMin = $tsMaxMinutesPerDay - $dayMinutes;

if ($timeOffMin <= ((1 * 60) + 30)) {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Working time on " . bdDateMysql2Cal($date) . " is 8:30 or more. No Time off required."]
    );
} elseif ($timeOffMin >= ((5 * 60) + 30)) {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Maximum time of 3:30 exceeded for Time off on " . bdDateMysql2Cal($date) . ". Require Full/Half day leave."]
    );
}

$x = explode(":", bdMinutes2Manhours($timeOffMin));
$h = $x[0];
$m = $x[1];

rdReturnJsonHttpResponse(
    '200',
    ["T", $h, $m]
);

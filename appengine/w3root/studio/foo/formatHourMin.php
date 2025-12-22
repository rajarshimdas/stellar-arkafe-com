<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 21-Feb-09                 		|
| Updated On: 						|
+-------------------------------------------------------+
| Format Hours and Minutes                              |
+-------------------------------------------------------+
*/

function formatHourMin($hours, $minutes) {

    // Convert minutes to hours and minutes
    $hour_x = ($minutes / 60);
    $hour_x = explode(".", $hour_x);
    $hour_x = $hour_x[0];

    // Add the hours to the total hours
    $total_hours = $hours + $hour_x;

    // Subtract hours from minutes
    $minutes = ($minutes - ($hour_x * 60));

    // Formated time
    $time = $total_hours.":".$minutes;

    return $time;

}

?>

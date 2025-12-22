<?php
/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 12-Aug-2011                               |
| Updated On:                                           |
+-------------------------------------------------------+
*/
// Format Date
function dateMysql2Cal(string $date): string
{

    $monthX = array(
        "01" => "Jan",
        "02" => "Feb",
        "03" => "Mar",
        "04" => "Apr",
        "05" => "May",
        "06" => "Jun",
        "07" => "Jul",
        "08" => "Aug",
        "09" => "Sep",
        "10" => "Oct",
        "11" => "Nov",
        "12" => "Dec"
    );

    $a = explode("-", $date);

    $year   = $a[0];
    $month  = $monthX[$a[1]];
    $day    = $a[2];

    // Parse Year into 2 digits
    $x = str_split($year);
    $year = $x[2] . $x[3];

    // Result
    $mysqldt = $day . "-" . $month . "-" . $year;

    return $mysqldt;
}

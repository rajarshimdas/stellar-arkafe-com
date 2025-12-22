<?php

/*
  +-------------------------------------------------------+
  | Rajarshi Das                                          |
  +-------------------------------------------------------+
  | Created On: 08-May-2013                               |
  | Updated On:                                           |
  +-------------------------------------------------------+
 */

// Format Date
function dateCal2UnixTS(string $date): string
{

    $monthX = array(
        "Jan" => "01",
        "Feb" => "02",
        "Mar" => "03",
        "Apr" => "04",
        "May" => "05",
        "Jun" => "06",
        "Jul" => "07",
        "Aug" => "08",
        "Sep" => "09",
        "Oct" => "10",
        "Nov" => "11",
        "Dec" => "12"
    );

    $a = explode("-", $date);

    $year = "20" . $a[2];
    $month = $monthX[$a[1]];
    $day = $a[0];

    // To do - Validate if its a true date
    // Get Unix timestamp
    $unixTimestamp = mktime(0, 0, 0, $month, $day, $year);

    return $unixTimestamp;
}

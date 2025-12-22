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
function dateMysql2UnixTS(string $date): string {

    $a = explode("-", $date);

    $year = $a[0];
    $month = $a[1];
    $day = $a[2];

    // To do - Validate if its a true date
    //
    //
    // Get Unix timestamp
    $unixTimestamp = mktime(0, 0, 0, $month, $day, $year);


    return $unixTimestamp;
}

?>
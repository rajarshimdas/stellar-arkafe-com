<?php
/* Checkdt function */
function checkdt($targetdt)
{

    /* Check fromat validity [dd-mm-yy] */
    $x = explode("/", $targetdt, 3);
    $date = $x[0];
    $month = $x[1];
    $year = $x[2];

    if (!$date || !$month || !$year) return "Invalid";
    //echo "<br>Date: $date, Month: $month, Year: $year";

    /* Check date validity */
    /* checkdate ( int month, int day, int year ) */
    $validdt = checkdate($month, $date, $year);
    //echo "<br> checkdate status: ";

    if ($validdt) {
        return "Valid";
    } else {
        return "Invalid";
    }
}


function CompaireDates($d1, $d2)
{
    /* Use checkdate to verify date [  checkdate(mm, dd, yyyy)  ] */
    $t1 = 0;
    $date1 = explode('-', $d1);
    $date2 = explode('-', $d2);
    if (checkdate($date1[1], $date1[2], $date1[0]) === false) $t1 = 1;
    if (checkdate($date2[1], $date2[2], $date2[0]) === false) $t1 = 1;
    // echo "Test: $t1";

    /* Use strtotime to calculate the difference */
    if ($t1 < 1) {
        if (($dx = (int)((strtotime($d2) - strtotime($d1)) / 86400)) === false)
            echo "<br>Error in compairing dates";
    } else {
        echo "<br>Input date error";
    }

    /* echo "<br>Difference between 2 dates = $dx"; */
    if ($dx > 0) {
        echo "<br>True";
    } else {
        echo "<br>false";
    }
}

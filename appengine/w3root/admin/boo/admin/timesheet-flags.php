<?php

$thisUid    = $_POST['comboUsers'];
$dt1        = $_POST['dt1'];
$dt2        = $_POST['dt2'];

if ($thisUid < 1 || is_int($thisUid)) {
    header("Location:sysadmin.cgi?a=Timesheet%20Flags&rx=e&m=e1");
    die;
}

if (strlen($dt1) < 10 || strtotime($dt1) === false) {
    header("Location:sysadmin.cgi?a=Timesheet%20Flags&rx=e&m=e2a");
    die;
}

if (strlen($dt2) < 10 || strtotime($dt2) === false) {
    header("Location:sysadmin.cgi?a=Timesheet%20Flags&rx=e&m=e2b");
    die;
}

// Which date is earlier?
if ($dt1 < $dt2) {
    // echo "dt1 is earlier";

    $fdt = $dt1;
    $tdt = $dt2;
} else {
    // echo "dt2 is earlier";

    $fdt = $dt2;
    $tdt = $dt1;
}

/*
+-------------------------------------------------------+
| Save in database                                      |
+-------------------------------------------------------+
*/
$mysqli = cn2();

$query = "update 
            timesheet 
        set 
            approved = 0, 
            quality = 0,
            pm_review_flag = 0
        where 
            user_id = $thisUid and
            dt >= '$fdt' and
            dt <= '$tdt'";

// echo "<br>".$query;

if ($mysqli->query($query)) {
    // Success
    header("Location:sysadmin.cgi?a=Timesheet%20Flags&rx=s&uid=$thisUid&fdt=$fdt&tdt=$tdt");
} else {
    // Failed
    header("Location:sysadmin.cgi?a=Timesheet%20Flags&rx=e&m=e3");
}


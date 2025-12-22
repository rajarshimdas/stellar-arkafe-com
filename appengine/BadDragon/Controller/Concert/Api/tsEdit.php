<?php

$tsId       = (int)$_POST['tsId'];     // timesheet id
$percent    = (int)$_POST["p"];
$noh        = (int)$_POST["h"];
$nom        = (int)$_POST["m"];

// Validation 


# Max 10 hours | $tsMaxMinutesPerDay
$query = "select `dt` from `timesheet` where `id` = '$tsId'";

$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {

    $dt = $row['dt'];
} else {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Edit timesheet failed. Server Error..."]
    );
}

$dayMh = bdGetDayTimesheet($dt, $uid, $mysqli);

$h = 0;
$m = 0;

// Total time in other day entries
foreach ($dayMh as $t):
 
    if ($t['timesheet_id'] != $tsId) {
        if ($t['quality'] < 1) {
            $h += $t['no_of_hours'];
            $m += $t['no_of_min'];
        }
    }

endforeach;

// Add this time
$h = $h + $noh;
$m = $m + $nom;

$totalMin = ($h * 60) + $m;

if ($totalMin > $tsMaxMinutesPerDay) {
    rdReturnJsonHttpResponse(
        '200',
        ["F", "Total manhours cannot exceed 10 hours."]
    );
}

// Update database
$query = "UPDATE 
            `timesheet` 
        SET 
            `percent` = '$percent',
            `no_of_hours` = '$noh',
            `no_of_min` = '$nom' 
        WHERE 
            `id` = '$tsId' and 
            `user_id` = '$user_id'";

$mysqli = cn2();

if ($mysqli->query($query)) {

    rdReturnJsonHttpResponse(
        '200',
        ["T"]
    );
} else {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Edit timesheet failed"]
    );
}

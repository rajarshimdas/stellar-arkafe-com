<?php

$tsId = $_POST['tsId'];
$thisUId = $_POST['thisUId'];
$thisDt = $_POST['thisDt'];

$mysqli = cn2();
$query = "update 
            timesheet 
        set 
            approved = 1, 
            quality = 1,
            pm_review_flag = 1
        where 
            id = $tsId";

$rx = $mysqli->query($query) ? "T" : "F";

$query = "select
            dt,
            sum(no_of_hours) as h,
            sum(no_of_min) as m
        from
            timesheet
        where
            user_id = '$thisUId' and
            active > 0 and
            quality < 1 and
            dt = '$thisDt'
        group by
            dt";

if ($result = $mysqli->query($query)) {

    if ($row = $result->fetch_assoc()) {
        $mh = $row;
    }
    $result->close();
}
$dayMH = empty($mh) ? '0:0' : bdAddHourMin($mh['h'], $mh['m']);


rdReturnJsonHttpResponse(
    '200',
    [$rx, $thisDt, $dayMH]
);

<?php

function getUserDayTimesheetSum(
    int $uid,           // user id
    string $dt,         // Date YYYY-MM-DD
    object $mysqli      // DB 
): ?array {

    $query = "SELECT 
                sum(`no_of_hours`) as `hour`,
                sum(`no_of_min`) as `min`,
                ((sum(`no_of_hours`) * 60) + sum(`no_of_min`)) as `total_min`
            FROM 
                `timesheet`
            where
                `user_id` = '$uid' and
                `dt` = '$dt' and
                `active` > 0 and
                `quality` < 1";

    // echo $query;

    $result = $mysqli->query($query);
    if ($row = $result->fetch_assoc()) {
        $ts = $row;
    }

    // if (empty($ts))return []; else return $ts;
    // return $ts ?: [];
    return !empty($ts) ? $ts : [];
}


function getUserDayRangeTimesheetSum(
    int $uid,           // user id
    string $dt_from,    // From date YYYY-MM-DD
    string $dt_upto,    // Upto date YYYY-MM-DD
    object $mysqli      // DB 
): ?array {

    $query = "SELECT 
                `dt`,
                DATE_FORMAT(`dt`, '%d-%b-%y') AS `date`,
                sum(`no_of_hours`) as `hour`,
                sum(`no_of_min`) as `min`,
                ((sum(`no_of_hours`) * 60) + sum(`no_of_min`)) as `total_min`
            FROM 
                `timesheet`
            where
                `user_id` = '$uid' and
                `dt` >= '$dt_from' and
                `dt` <= '$dt_upto' and
                `active` > 0 and
                `quality` < 1
            group by
                `user_id`,
                `dt`";

    //echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $ts[$row['dt']] = $row;
    }

    // return $ts;
    // return $ts ?: []; 
    return !empty($ts) ? $ts : [];
}

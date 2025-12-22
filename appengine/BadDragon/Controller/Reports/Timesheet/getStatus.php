<?php

// die("getStatus");

// No of days
$nod = 12;

require_once BD . "Controller/User/User.php";

$users = bdGetUsersArray($mysqli);
// var_dump($users);

// Days
for ($i = 0; $i < $nod; $i++) {
    $dateX[] = date("D", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
    $dateH[] = date("d-M-y", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
    $dateM[] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
}


// Fetch all timesheet for this date range
// $query = "select * from timesheet where dt >= '" . $dateM[($nod - 1)] . "' and active > 0";
$query = "select 
            project_id,
            user_id, 
            dt, 
            no_of_hours, 
            no_of_min 
        from 
            timesheet 
        where 
            dt >= '" . $dateM[($nod - 1)] . "' and 
            quality < 1 and
            active > 0";
// echo $query;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {
        $ts[$row["user_id"]][$row["dt"]][] = [$row["no_of_hours"], $row["no_of_min"], $row["project_id"]];
    };

    $result->close();
}

// var_dump($ts);
// echo bdAddHourMin(4, 135);

/* Loop for active users */
$no = 1;
for ($e = 0; $e < count($users); $e++) {

    // if ($users[$e]["active"] > 0) {

    $uid    = $users[$e]["user_id"];
    $name   = $users[$e]["displayname"];
    $has_timesheet = 0;

    // echo '<br>' . $no . ". " . $name . ' | ' . $uid;
    $no++;
    $dayMH = null;

    for ($n = 0; $n < $nod; $n++) {

        // Set default values
        $h = 0;
        $m = 0;

        $cnt = (isset($ts[$uid][$dateM[$n]])) ? count($ts[$uid][$dateM[$n]]) : 0;

        // Check for Leave
        $flag = "";
        for ($x = 0; $x < $cnt; $x++) {

            if ($ts[$uid][$dateM[$n]][$x][2] == 2) {
                $flag = 'LF';
            } elseif ($ts[$uid][$dateM[$n]][$x][2] == 3) {
                $flag = 'LH & ';
            } else {
                $h += $ts[$uid][$dateM[$n]][$x][0];
                $m += $ts[$uid][$dateM[$n]][$x][1];
            }
        }

        // echo " | " . bdAddHourMin($h, $m);
        if ($m > 0 || $h > 0) $has_timesheet = 1;

        $dayMH[] = ($flag == 'LF') ? $flag : $flag . bdAddHourMin($h, $m);
    }
    if ($has_timesheet > 0) {
        $status[] = [
            "name"      => $name,
            "dayMH"     => $dayMH,
        ];
    }
    //}
}

//var_dump($status);
//die;

// Generate Page
$title = "MIS | Timesheet Status";
$moduleName = "MIS";
$returnURL = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view = 'timesheetFilledStatus';

require BD . 'View/Template/generatePage.php';

<?php
// https://beta.arkafe.com/reports/timesheet/getStatusMonth/2024-07/csv

//$format = $route->parts[4];
//$mo = $route->parts[3];

$format = $_POST["go"];
$mo = $_POST["mo"];


$x = explode('-', $mo);
$m = $x[1];
$Y = $x[0];

$start_dt = $mo . '-01';
$end_dt = date("Y-m-t", strtotime($start_dt));

require_once BD . "Controller/User/User.php";

$users = bdGetUsersArray($mysqli);
// var_dump($users);

$nod = date("t", strtotime($start_dt));

// Days
for ($i = 0; $i < $nod; $i++) {

    $tx = mktime(0, 0, 0, $m, ($i + 1), $Y);

    $dateX[] = date("D", $tx);
    $dateH[] = date("d-M-y", $tx);
    $dateM[] = date("Y-m-d", $tx);
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
            dt >= '" . $start_dt . "' and 
            dt <= '" . $end_dt . "' and 
            quality < 1 and
            active > 0";
// die($query);

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {
        $ts[$row["user_id"]][$row["dt"]][] = [$row["no_of_hours"], $row["no_of_min"], $row["project_id"]];
    };

    $result->close();
}

// var_dump($ts);
// echo bdAddHourMin(4, 135);

/* Loop for all users */
$no = 1;
for ($e = 0; $e < count($users); $e++) {

    //if ($users[$e]["active"] > 0) {

    $uid    = $users[$e]["user_id"];
    $name   = $users[$e]["displayname"];

    // echo '<br>' . $no . ". " . $name . ' | ' . $uid;
    $no++;
    $dayMH = null;
    $has_timesheet = 0;

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
            } elseif ($ts[$uid][$dateM[$n]][$x][2] == 4) {
                $flag = 'AT & ';
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
            "name" => $name,
            "dayMH" => $dayMH
        ];
    }
    // }
}

// var_dump($status);
// die;

if ($format != 'Export CSV') {
    // Generate Page
    $title = "MIS | Timesheet Status";
    $moduleName = "MIS";
    $returnURL = ["MIS", BASE_URL . 'mis/concert.cgi'];
    $view = 'Module/mis/timesheetFilledStatusMo';

    require BD . 'View/Module/mis/Template/generatePage.php';
} else {

    // Export CSV

    $filename = "timesheet-month-summary-" . $mo . ".csv";

    $delimiter = ",";

    $f = fopen('php://memory', 'w');


    $fields = ["No", "Name"];

    for ($i = 0; $i < count($dateH); $i++) {
        array_push($fields, $dateX[$i] . ' ' . $dateH[$i]);
    }

    // var_dump($fields);
    fputcsv($f, $fields, $delimiter);


    $no = 1;
    for ($e = 0; $e < count($status); $e++) {

        $dline = [$no++, $status[$e]["name"]];
        $co = isset($status[$e]["dayMH"]) ? count($status[$e]["dayMH"]) : 0;

        for ($i = 0; $i < $co; $i++) {

            array_push($dline,  $status[$e]['dayMH'][$i]);
        }

        // var_dump($dline);
        fputcsv($f, $dline, $delimiter);
    }


    fseek($f, 0);

    header('Content-Type: text/csv');

    header('Content-Disposition: attachment; filename="' . $filename . '";');

    fpassthru($f);

    exit();
}

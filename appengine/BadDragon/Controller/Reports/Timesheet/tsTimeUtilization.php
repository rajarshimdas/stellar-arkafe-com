<?php

$mo = $_POST['month1'];
// echo $mo;

// Get Projects List
$query = "select 
            `id` as `pid`, 
            `jobcode`, 
            `projectname` 
        from 
            `projects` 
        where
            `id` > 15
        order by 
            `jobcode`";
//echo "<br>" . $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    };
    $result->close();
}




// Get timesheet data
$x = explode("-", $mo);

$query = "select 
            `project_id` as `pid`,
            `no_of_hours` as `h`,
            `no_of_min` as `m`
        from 
            `timesheet` 
        where 
            month(`dt`) = '$x[1]' and 
            year(`dt`) = '$x[0]' and 
            active > 0 and 
            quality < 1";

//echo "<br>" . $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $ts[$row["pid"]][] = [$row["h"], $row["m"]];
    };
    $result->close();
}

//echo '<pre>' , var_dump($ts) , '</pre>';

// Totals
$th = 0;
$tm = 0;

// Total MH for each project
for ($i = 0; $i < count($projects); $i++) {

    $pid = $projects[$i]["pid"];
    $pnm = $projects[$i]["projectname"];

    $h = 0;
    $m = 0;

    $co = (isset($ts[$pid])) ? count($ts[$pid]) : 0;

    for ($k = 0; $k < $co; $k++) {

        $h += $ts[$pid][$k][0];
        $m += $ts[$pid][$k][1];
    }
    // echo "<br>$pid) $pnm: $h:$m | " . bdTimeH($h, $m);

    if ($h > 0 || $m > 0) {
        $chart[] = [
            "project" => $pnm,
            "manhours" => bdTimeH($h, $m)
        ];

        $th += $h;
        $tm += $m;
    }
}

$total_mh = bdTimeH($th, $tm);

// echo "<pre>", var_dump($chart), "</pre>";

// Generate Page
$title          = "MIS | Time Utilization";
$moduleName     = "MIS";
$returnURL      = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view           = 'Module/mis/timeUtilizationPie';

require BD . 'View/Module/mis/Template/generatePage.php';

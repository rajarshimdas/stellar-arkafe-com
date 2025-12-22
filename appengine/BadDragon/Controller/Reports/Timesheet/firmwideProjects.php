<?php
$mo = $_POST['mo'];
//echo 'mo: ' . $mo;

$x = explode('-', $mo);
$month = date('M y', mktime(0, 15, 0, $x[1], 01, $x[0]));

// Projects 
// $pX = bdProjectList($mysqli);

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
        $pX[] = $row;
    };
    $result->close();
}

// var_dump($pX);

$query = "select 
            * 
        from 
            `view_project_timesheet_sum`
        where
            `month` = '$mo' and
            `quality` < 1";

// echo $query;

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $ts[$row["pid"]] = $row;
        // var_dump($row);
    };
    $result->close();
}

//var_dump($ts[516]);




// Generate Page
$title          = "MIS | Firmwide Projects Timesheet Summary";
$moduleName     = "MIS";
$returnURL      = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view           = 'Module/mis/firmwideProjects';

require BD . 'View/Module/mis/Template/generatePage.php';

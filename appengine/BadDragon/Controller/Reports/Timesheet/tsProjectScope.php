<?php

// bdLoadFn(['Projects', 'User']);

$pid = $_POST["pid"];
$fdt = $_POST["fdt"];
$tdt = $_POST["tdt"];

# echo "$pid | $fdt | $tdt";

// Users Array
$usersX = bdGetUsersArray($mysqli);
// var_dump($usersX);

// Users indexed by id
$usersId = bdGetUsersArrayX($mysqli);

// Get
$pname  = bdProjectId2Name($pid, $mysqli);
$pscope = bdGetProjectScopeArray($mysqli);
$pstage = bdGetProjectStageArray($mysqli);

// var_dump($pscope);
// var_dump($pstage);

// Get Timesheet data
$query = "select 
                `projectscope_id` as `scope_id`,
                `projectstage_id` as `stage_id`,
                `user_id` as `uid`,
                sum(`no_of_hours`) as `h`, 
                sum(`no_of_min`) as `m`
            from 
                `timesheet` 
            where 
                `project_id` = '$pid' and 
                `quality` < 1 and
                `dt` >= '$fdt' and
                `dt` <= '$tdt' and
                `active` > 0
            group by
                `scope_id`,
                `stage_id`,
                `uid`";
// die($query);

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {

        // Tabulation
        $tsX[$row['scope_id']][$row['stage_id']][$row['uid']] = [
            /* 'uid'   => $row['uid'], */
            "mh"    => bdAddHourMin($row['h'], $row['m']),
            'h'     => $row['h'],
            'm'     => $row['m'],
        ];

        // Scope Total
        if (isset($scopeTo[$row['scope_id']]['h'])) {
            $scopeTo[$row['scope_id']]['h'] += $row['h'];
        } else {
            $scopeTo[$row['scope_id']]['h'] = $row['h'];
        }

        if (isset($scopeTo[$row['scope_id']]['m'])) {
            $scopeTo[$row['scope_id']]['m'] += $row['m'];
        } else {
            $scopeTo[$row['scope_id']]['m'] = $row['m'];
        }

        // Stage Total
        if (isset($stageTo[$row['scope_id']][$row['stage_id']]['h'])) {
            $stageTo[$row['scope_id']][$row['stage_id']]['h'] += $row['h'];
        } else {
            $stageTo[$row['scope_id']][$row['stage_id']]['h'] = $row['h'];
        }

        if (isset($stageTo[$row['scope_id']][$row['stage_id']]['m'])) {
            $stageTo[$row['scope_id']][$row['stage_id']]['m'] += $row['m'];
        } else {
            $stageTo[$row['scope_id']][$row['stage_id']]['m'] = $row['m'];
        }
    };
    $result->close();
}

/*
echo '<pre>', var_dump($tsX) , '</pre>';
die;
*/

// Generate Page
$title          = "MIS | Timesheet Report";
$moduleName     = "MIS";
$returnURL      = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view           = 'Module/mis/timesheetReportbyScope';

require BD . 'View/Module/mis/Template/generatePage.php';

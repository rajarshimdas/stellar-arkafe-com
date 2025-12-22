<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 07-Oct-2024       			            |
| Updated On: 26-Oct-2025                               |
+-------------------------------------------------------+
*/

if (isset($_SESSION["activePID"])) {
    $pid = $_SESSION["activePID"];
} else {
    die("Project info missing...");
}

/*
+-------------------------------------------------------+
| Scope and Milestone                                   |
+-------------------------------------------------------+
*/
$scope = bdGetProjectScopeArray($mysqli);
$co_scope = count($scope);
// var_dump($scope);

$stage = bdGetProjectStageArray($mysqli);
$co_stage = count($stage);
// vsar_dump($stage);
/*
+-------------------------------------------------------+
| Tasks                                                 |
+-------------------------------------------------------+
*/

// $tasks = bdGetProjectTasks($pid, $mysqli);
// echo "Active";
$fx = "`active` > 0 and `status_last_month` < 100 and `onhold` < 1";

$query = "SELECT * FROM `view_tasks` where `project_id` = '$pid' and $fx order by `task_id` desc";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $tk[$row['scope_id']][$row['stage_id']][] = $row;
    $taskById[$row['task_id']] = $row;
}

// rx ($tk);
$tasks = [];
foreach ($scope as $sc) {
    $scId = $sc['id'];
    foreach ($stage as $st) {
        $stId = $st['id'];

        $co = empty($tk[$scId][$stId]) ? 0 : count($tk[$scId][$stId]);

        if ($co > 0) {
            $tx = $tk[$scId][$stId];
            foreach ($tx as $t) {
                $tasks[] = $t;
            }
        }
    }
}
// rx($tasks);
// die();
/*
+-------------------------------------------------------+
| Export Header Row                                     |
+-------------------------------------------------------+
*/
$filename = "Tasks - " . bdProjectId2Name($pid, $mysqli) . ".csv";

$delimiter = ",";

$f = fopen('php://memory', 'w');

$fields = ["Flag", "TaskId", "Scope", "Milestone", "Work Description", "Completed%", "Target%", "AH", "AM"];

// var_dump($fields);
fputcsv($f, $fields, $delimiter);

/*
+-------------------------------------------------------+
| Export Data Row                                       |
+-------------------------------------------------------+
*/

foreach ($tasks as $t) {

    if ($t['active'] > 0 && $t['status_last_month'] < 100) {

        $fields = [
            "#",
            $t['task_id'],
            $t['scope_sn'],
            $t['stage_sn'],
            $t['work'],
            $t['status_last_month'],
            $t['status_this_month_target'],
            0,  // $t['manhours'],
            0   // $t['manminutes'],
        ];

        fputcsv($f, $fields, $delimiter);
    }
}

/*
+-------------------------------------------------------+
| Response                                              |
+-------------------------------------------------------+
*/
fseek($f, 0);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');
fpassthru($f);

exit();

<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 07-Oct-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

if (isset($_SESSION["activePID"])) {
    $pid = $_SESSION["activePID"];
} else {
    die("Project info missing...");
}

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
$tasks = bdGetProjectTasks($pid, $mysqli);
// var_dump($t); die;

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
            $t['manhours'],
            $t['manminutes'],
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

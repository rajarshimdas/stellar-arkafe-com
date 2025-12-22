<?php /* Timesheet export
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   14-Apr-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
| Bootstrap & Module Authentication                     |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

$moduleId = 3;
if (userModuleAuth($moduleId, $user_id, $loginname, $sessionid, $mysqli) !== TRUE) {
    die('<div align="center" style="color:Red;">Module Access Denied.</div>');
}

/*
+-------------------------------------------------------+
| Export csv                                            |
+-------------------------------------------------------+
*/
$month = $_GET["mo"];

$filename = "timesheet-export-" . date('Y-m-d') . ".csv";

$delimiter = ",";

$f = fopen('php://memory', 'w');

$fields = ["Date", "Name", "Project", "Scope", "Milestone", "Work", "Hours", "Minutes", "Percent"];
fputcsv($f, $fields, $delimiter);

$query = "SELECT * FROM `csv_timesheets` where `month` = '$month'";

$result = $mysqli->query($query);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $lineData = [$row["date"], $row["fullname"], $row["projectname"], $row["scope"], $row["milestone"], $row["work"], $row["hours"], $row["minutes"], $row["percent"]];
        fputcsv($f, $lineData, $delimiter);
    }
}

fseek($f, 0);

header('Content-Type: text/csv');

header('Content-Disposition: attachment; filename="' . $filename . '";');

fpassthru($f);

exit();

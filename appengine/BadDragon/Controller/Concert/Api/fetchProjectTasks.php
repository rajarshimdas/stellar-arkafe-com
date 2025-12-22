<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 08-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once BD . "Controller/Projects/Projects.php";

$pid = isset($_POST["pid"]) ? ($_POST["pid"] + 0) : 0;

if ($pid < 1 || !is_int($pid)) {
    $data[] = ["rx" => 'F'];
    rdReturnJsonHttpResponse('200', $data);
}

$_SESSION["addTaskPID"] = $pid;

// Send Metadata
$data[] = [
    "rx" => "T",
    "pid" => $pid,
    "pname" => bdProjectId2Name($pid, $mysqli),
];

// Project tasks rows
$data[] = bdGetProjectTasks($pid, $mysqli);
rdReturnJsonHttpResponse('200', $data);

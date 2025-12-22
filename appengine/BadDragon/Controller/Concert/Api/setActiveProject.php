<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 19-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$pid = isset($_POST["pid"]) ? ($_POST["pid"] + 0) : 0;

if ($pid < 1 || !is_int($pid)) {
    $data = ["rx" => 'F'];
    rdReturnJsonHttpResponse('200', $data);
}

$_SESSION["activePID"] = $pid;

// Send Metadata
$data = [
    "rx" => "T",
    "pid" => $pid,
];

rdReturnJsonHttpResponse('200', $data);

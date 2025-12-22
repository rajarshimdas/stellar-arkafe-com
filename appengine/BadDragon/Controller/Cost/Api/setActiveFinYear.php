<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 19-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$finStartYear = isset($_POST["finStartYear"]) ? $_POST["finStartYear"] . "-04-01" : "X";

if ($finStartYear == "X") {
    $data = ['F', 'No finStartYear value at input.'];
    rdReturnJsonHttpResponse('200', $data);
}

$fy = json_encode(bdFinancialYear($finStartYear));
$_SESSION["activeFinancialYear"] = $fy;


// Send Metadata
$data = ['T', $fy];
rdReturnJsonHttpResponse('200', $data);

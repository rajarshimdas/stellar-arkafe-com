<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 10-Dec-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$fdt = (strlen($_POST["fdt"]) > 9) ? $_POST["fdt"] : 0;
$tdt = (strlen($_POST["tdt"]) > 9) ? $_POST["tdt"] : 0;

if ($fdt == 0 || $tdt == 0) {
    $data = ['F', 'Error in from/to date.'];
} else {
    $_SESSION["dateRangeFrom"] = $fdt;
    $_SESSION["dateRangeTo"] = $tdt;
    $data = ["T", "ok"];
}

rdReturnJsonHttpResponse('200', $data);

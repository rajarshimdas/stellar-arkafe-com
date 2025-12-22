<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$activeYear = $_POST["activeYear"] + 0;

if (is_int($activeYear)) {
    $_SESSION["activeYear"] = $activeYear;
    rdReturnJsonHttpResponse("200", ["T"]);
}

rdReturnJsonHttpResponse("200", ['F']);

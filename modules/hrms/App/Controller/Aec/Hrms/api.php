<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   26-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Data Validation 
require_once BD . '/Toolbox/Validation.php';
$validate = new bdDataValidation();
//die(bdReturnJSON(['Test', $validate]));


// Fire Action file
// var_dump($route);

$actionFile = __DIR__ . '/api/' . $route->parts[3] . '.php';

if (is_file($actionFile)) {
    require_once $actionFile;
} else {
    die("actionFile not found. " . $actionFile);
}



## Functions
##
function checkNoOfDays(
    string $leaveStartDate,
    int $checkNod
): int {
    # UNIX Timestamp 0h 0m 0s
    $today = strtotime('today');

    $attr_id = 1;   // NA

    $timestamp = strtotime($leaveStartDate);
    $tsThisDate = strtotime('-' . $checkNod . ' days', $timestamp);

    if ($today > $tsThisDate) {
        $attr_id = 20;  // Un-sanctioned
    } else {
        $attr_id = 10;  // Sanctioned
    }

    return $attr_id;
}

function checkNoOfWorkDays(
    string $leaveStartDate,
    int $checkNod,
    array $holidays
): int {
    # UNIX Timestamp 0h 0m 0s
    $today = strtotime('today');

    $attr_id = 1;                   // NA
    $noOfWkDays = 0;
    $thisDate = $leaveStartDate;

    while ($noOfWkDays <= $checkNod) {
        $thisDate = getPreviousISODate($thisDate);
        if (!in_array($thisDate, $holidays)) $noOfWkDays++;
    }

    $tsThisDate = strtotime($thisDate);

    if ($today > $tsThisDate) {
        $attr_id = 20;  // Un-sanctioned
    } else {
        $attr_id = 10;  // Sanctioned
    }

    return $attr_id;
}


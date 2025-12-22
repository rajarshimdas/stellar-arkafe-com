<?php /* PTO | Paid Time Off
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Aug-2025                               |
| Updated On: 20-Dec-2025   Remove dates that do not    |
|                           fall in sandwich condition  |
+-------------------------------------------------------+
*/


require_once __DIR__ . '/pto-fx.php';

## Override month for testing
// $leaveMISMonth = 'Jul';
// echo 'leaveMISMonth: '. $leaveMISMonth;
$year = $leaveMISYear;

$users = bdGetUsers($mysqli);
// var_dump($users);
// var_dump($noTabulationForUsers);
// $users = bdRemoveFromArray($users, $noTabulationForUsers);

$leaveTypes = bdGetLeaveTypes($mysqli);
// var_dump($leaveTypes);

$leaveTypesById = bdGetLeaveTypesById($mysqli);

// $monthNo = array_search($leaveMISMonth, $months);
$monthNo = (float)$leaveMISMonth;
$sdt = getFirstDateOfMonth($year, $monthNo);
$edt = getLastDateOfMonth($year, $monthNo);
// echo "<div>Date Range: $sdt | $edt</div>";

// $holidays = bdGetHolidayISODates($year, $mysqli);
$holidays = bdGetHolidayListInRange($sdt, $edt, $mysqli);
// rx($holidays);

$sundays = getSundaysInRange($sdt, $edt);
// rx($sundays);

// Combine holidays and sundays
$hDates = [];
foreach ($holidays as $h1) {
    $hDates[] = $h1['dt'];
}
foreach ($sundays as $h2) {
    $hDates[] = $h2;
}
// rx($hDates);

## Leave Records
##
$leaveRec = bdGetAllUserLeaveRecords($mysqli, $year);
// rx($leaveRec);

## Leave Date Range to ISO dates Array
##
foreach ($users as $u) {
    $thisUId = $u['user_id'];

    if (!empty($leaveRec[$thisUId])) {
        // echo '<div>' . $no++ . '. ' . $u['displayname'] . " | uid: $thisUId " . '</div>';
        $pto[$thisUId] = getLeaveDates($leaveRec[$thisUId], $sdt, $edt, $hDates);
        // rx($pto);
    }
}


// Starting Balance
$allUserStartBal = bdGetAllUserLeaveStartMonthAndBal($year,$mysqli);

// Leave Availed 
$lxAvailed = bdGetAllUserLeaveAvailedMonthly($year, $mysqli);
// die(rx($lxAvailed[$thisUId]));



require_once W3APP . '/View/Widgets/fxMonthSelect.php';
require_once __DIR__ . '/pto-table.php';

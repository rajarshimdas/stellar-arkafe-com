<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   01-Jan-2025                             |
| Updated On:   25-Nov-2025                             |
+-------------------------------------------------------+
*/
$mysqli     = cn1();

$months     = ['Zero', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$monthNm    = ['Zero', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$months2d   = ['Zero', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

// $year    = date("Y");
// $year    = $activeCalYear;
$year       = $leaveMISYear;        // 25-Nov-25
$month      = date("n");            // Month without trailing zero
$monthNo2d  = $months2d[$month];
/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetLeaveTypes(object $mysqli): array
{
    $rx = [];
    $query = "select * from `rd_leave_type` order by `displayorder`";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[] = $row;
    }

    return $rx;
}
/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetLeaveTypesById(object $mysqli): array
{
    $rx = [];
    $query = "select * from `rd_leave_type` order by `displayorder`";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['id']] = $row;
    }

    return $rx;
}
/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetUserLeaveRecords(int $uid, int $year, object $mysqli): array
{
    $leaves = [];

    // year filter updated | 25 Nov 25
    $query = "select 
                * 
            from 
                `rd_view_leaves` 
            where 
                `user_id` = '$uid' and 
                `year` >= '$year' and
                `active` > 0 
            order by 
                `dt_last_updated` desc";
    // die($query);

    $result = $mysqli->query($query);

    while ($row = $result->fetch_assoc()) {
        $leaves[] = $row;
    }

    return $leaves;
}
/*
+-------------------------------------------------------+
| bdGetAllUserLeaveRecords | 22-Dec-2025                |
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveRecords(object $mysqli, string $year): array
{
    // Note fetch all leave records for defined year + future years | 22-Dec-25
    $query = "select 
                * 
            from 
                `rd_view_leaves` 
            where 
                `year` >= '$year' and
                `active` > 0";
    // echo "<div>$query</div>";

    $result = $mysqli->query($query);
    //rx($result);

    while ($row = $result->fetch_assoc()) {
        $leaves[$row['user_id']][] = $row;
    }
    // rx($leaves);
    return empty($leaves) ? [] : $leaves;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllLeaveRecordsForMonth(int $month, int $year, object $mysqli): array
{
    $query = "select 
                * 
            from 
                `rd_view_leaves` 
            where 
                `year` = '$year' and
                `month` = '$month' and
                `active` > 0";
    // rd($query);

    $result = $mysqli->query($query);
    //rx($result);

    while ($row = $result->fetch_assoc()) {
        $leaves[] = $row;
    }
    // rx($leaves);
    return empty($leaves) ? [] : $leaves;
}


/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetUserLeaveRecById(int $leave_id, object $mysqli): array
{
    $leave = [];
    $query = "select * from `rd_view_leaves` where `id` = '$leave_id'";
    //echo $query;

    $result = $mysqli->query($query);
    //rx($result);

    while ($row = $result->fetch_assoc()) {
        $leave = $row;
    }
    // rx($leaves);
    return $leave;
}
/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetLeaveRequestForUsers(array $uids, object $mysqli): array
{
    if (empty($uids)) return [];
    $leaves = [];

    $ids = "(";
    foreach ($uids as $id) {
        // $id = $u['user_id'];
        $ids = $ids . " '$id',";
    }
    $ids = $ids . " '0')";

    $query = "select * from `rd_view_leaves` where `user_id` in $ids and `active` > 0 order by `dt_last_updated` desc";
    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $leaves[] = $row;
    }

    return $leaves;
}


/*
+-------------------------------------------------------+
| bdGetAllUserLeaveAvailedWithType | 25-Jun-2025        |
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveAvailedWithType(int $year, object $mysqli): array
{
    $query = "select
                *
            from
                `rd_view_leave_availed`
            where
                `year` = '$year'";
    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']][$row['leave_attr_id']] = (float)($row['nod'] / 100);
    }

    return empty($rx) ? [] : $rx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetUserLeaveAvailedWithType(int $uid, int $year, object $mysqli): array
{
    $query = "select
                *
            from
                `rd_view_leave_availed`
            where
                `year` = '$year' and 
                `user_id` = '$uid'";

    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']][$row['leave_attr_id']] = (float)($row['nod'] / 100);
    }

    return empty($rx) ? [] : $rx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetUserLeaveAvailedWithTypeByMonth(int $uid, int $year, object $mysqli): array
{
    $rx = [];

    $query = "select
                *
            from
                `rd_view_leave_availed_monthly`
            where
                `year` = '$year' and 
                `user_id` = '$uid'";
    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']][$row['leave_attr_id']][$row['month']] = (float)($row['nod'] / 100);
    }

    return $rx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveAvailedWithTypeByMonth(int $year, object $mysqli): array
{
    // die('bdGetAllUserLeaveAvailedWithTypeByMonth');
    $rx = [];
    $query = "select
                *
            from
                `rd_view_leave_availed_monthly`
            where
                `year` = '$year'";
    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']][$row['leave_attr_id']][$row['month']] = (float)($row['nod'] / 100);
    }

    return empty($rx) ? [] : $rx;
}
/*
+-------------------------------------------------------+
| bdGetAllUserLeaveAvailedMonthly | 16-Dec-25           |
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveAvailedMonthly(int $year, object $mysqli): array
{
    // die('bdGetAllUserLeaveAvailedMonthly');
    $rx = [];
    $query = "select
                *
            from
                `rd_view_leave_availed_monthly`
            where
                `year` = '$year'";
    // echo $query;

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']][$row['month']][$row['leave_attr_id']] = (float)($row['nod'] / 100);
    }

    return empty($rx) ? [] : $rx;
}


/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
// Special Leave count
function splLeaveCount(string $ids, int $year, object $mysqli): array
{
    $query = "SELECT 
                user_id, 
                leave_type_id,
                sum(nod_units) as nod 
            FROM 
                rd_leave_records 
            where 
                leave_type_id in ($ids) and
                `year_generated` = '$year' and
                active > 0
            group by 
                user_id,
                leave_type_id";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $n = ($row['nod'] / 100);
        $rx[$row['user_id']][$row['leave_type_id']] = (float)$n;
    }
    return empty($rx) ? [] : $rx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
// Special Leave count
function splLeaveCountByMonth(string $ids, int $year, object $mysqli): array
{
    $rx = [];

    $query = "SELECT 
                user_id, 
                leave_type_id,
                month_generated,
                sum(nod_units) as nod 
            FROM 
                rd_leave_records 
            where 
                leave_type_id in ($ids) and
                `year_generated` = '$year' and
                active > 0
            group by 
                user_id,
                leave_type_id,
                month_generated";
    // echo $query;

    $result = $mysqli->query($query);

    while ($row = $result->fetch_assoc()) {
        $n = ($row['nod'] / 100);
        $rx[$row['user_id']][$row['month_generated']][$row['leave_type_id']] = (float)$n;
    }

    return $rx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveApplied(int $year, object $mysqli): array
{
    $la = [];

    $query = "SELECT * FROM rd_view_leave_applied where `year` = '$year';";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $n = ($row['nod'] / 100);
        $la[$row['user_id']][$row['leave_type_id']] = (float)$n;
    }

    return $la;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveAppliedMonthly(int $year, object $mysqli): array
{
    $la = [];

    $query = "SELECT * FROM rd_view_leave_applied_monthly where `year` = '$year';";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $n = ($row['nod'] / 100);
        $la[$row['user_id']][$row['leave_type_id']][$row['month']] = (float)$n;
    }

    return $la;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetUserLeaveStartBal(int $uid, int $year, object $mysqli): array
{
    $query = "SELECT 
                `year`,
                `user_id`,
                `leave_type_id`,                      
                `added_nod` as `nod`
            FROM 
                `rd_leave_add`
            where
                `user_id` = '$uid' and
                `year` = '$year' and 
                `starting_balance_flag` > 0";
    // die($query);

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $leaveNod[$row['leave_type_id']] = (float)($row['nod'] / 100);
    }

    return empty($leaveNod) ? [] : $leaveNod;
}

/*
+-------------------------------------------------------+
| bdGetAllUserLeaveStartMonthAndBal | 16-Dec-2025       |
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveStartMonthAndBal(int $year, object $mysqli): array
{
    $query = "SELECT 
                `year`,
                `user_id`,
                `leave_type_id`,                      
                `added_nod` as `nod`,
                `month`
            FROM 
                `rd_leave_add`
            where
                `year` = '$year' and 
                `starting_balance_flag` > 0";
    // die($query);

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $leaveNod[$row['user_id']][$row['leave_type_id']] = [
            'monthNo' => $row['month'],
            'noOfDays' => (float)($row['nod'] / 100),
        ];
    }

    return empty($leaveNod) ? [] : $leaveNod;
}



/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveStartBal(int $year, object $mysqli): array
{
    $query = "SELECT 
                `year`,
                `user_id`,
                `leave_type_id`,                      
                `added_nod` as `nod`
            FROM 
                `rd_leave_add`
            where
                `year` = '$year' and 
                `starting_balance_flag` > 0";
    // die($query);

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $leaveNod[$row['user_id']][$row['leave_type_id']] = (float)($row['nod'] / 100);
    }

    return empty($leaveNod) ? [] : $leaveNod;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveStartMon(int $year, object $mysqli): array
{
    $query = "SELECT 
                `dt`,
                `user_id`,
                `leave_type_id`
            FROM 
                `rd_leave_add`
            where
                `year` = '$year' and 
                `starting_balance_flag` > 0";
    // die($query);

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $rx[$row['user_id']][$row['leave_type_id']] = [
            'dt' => $row['dt'],
            'date' => date("M y", strtotime($row['dt'])),
            'monthNo' => date("n", strtotime($row['dt']))
        ];
    }

    return empty($rx) ? [] : $rx;
}

/*
+-------------------------------------------------------+
| bdGetAllUserLeaveAvailableTotal                       |
+-------------------------------------------------------+
*/
function bdGetAllUserLeaveAvailableTotal(

    array   $startBal,          // Employee starting leave balance
    array   $startMon,          // Employee doj
    int     $leave_type_id,     // 2 Earned leaves
    int     $year,              // Year
    int     $month,             // Month
    object  $mysqli             // Database

): array {

    // Vars
    $lx = [];
    $today = date('Y-m-d');
    // $currentMoNo = date('n');
    $currentMoNo = $month;

    // All Employees 
    $users = bdGetUsers($mysqli);

    // All Employee joining and termination date
    $usersData = bdGetEmployeeDataById($mysqli);
    // var_dump($usersData);

    // Loop
    foreach ($users as $u) {

        $uid = $u['user_id'];
        $doj = $usersData[$uid]['dt_doj'];
        $doe = $usersData[$uid]['dt_doe'];

        // Skip this employee if starting leave balance not added by HR
        if (empty($startMon[$uid])) {
            continue;
        }

        // Skip this employee who has exited
        if ($doe < "$year-$month-01") {
            continue;
        }

        // Reset Vars
        $nom    = 0; // Number of Working Months

        // $startDt = $startMon[$uid][$leave_type_id]['dt'];
        $startMoNo  = $startMon[$uid][$leave_type_id]['monthNo'];

        // Trainees get 1 day/month
        $daysPerMonth = ($usersData[$uid]['userhrgroup_id'] != 24) ? 1.75 : 1;

        // $totalMo = ($currentMoNo > $month) ? $month : $currentMoNo;
        $nom = ($currentMoNo - $startMoNo);

        // Number of days
        $sbal = empty($startBal[$uid][$leave_type_id]) ? 0 : $startBal[$uid][$leave_type_id];
        $totalNod = ($nom * $daysPerMonth) + $sbal;

        // echo $u['displayname'] . " | doj: $doj | doe: $doe | nom: $nom | startBal: $sbal | totalNod: $totalNod<br>";

        $lx[$uid] = [
            'displayname' => $u['displayname'],
            'doj' => $doj,
            'doe' => $doe,
            'nom' => $nom,
            'startBal' => $sbal,
            'totalNod' => $totalNod
        ];
    }

    return $lx;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdGetEmployeeDataById(object $mysqli): array
{
    $query = "select * from view_users";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $user[$row['user_id']] = $row;
    }

    return empty($user) ? [] : $user;
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function getFirstDateOfMonth(int $year, int $monthNo): string
{
    // $year = date('Y');
    $monthNo = str_pad($monthNo, 2, '0', STR_PAD_LEFT); // ensures 2-digit month
    return "$year-$monthNo-01";
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
// Year required for Feb
function getLastDateOfMonth(int $year, int $monthNo): string
{
    // $year = date('Y');
    $monthNo = str_pad($monthNo, 2, '0', STR_PAD_LEFT);
    $date = DateTime::createFromFormat('Y-m-d', "$year-$monthNo-01");
    return $date->format('Y-m-t'); // 't' gives the last day of the month
}

/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/
function bdShortLeaveNod(int $uid, object $mysqli): int
{

    $thisMonth  = date("n");
    $thisYear   = date("Y");

    $query = "SELECT 
                sum(active) as `nod` 
            FROM 
                rd_leave_records 
            where 
                leave_type_id = 9 and
                user_id = '$uid' and
                year_generated = '$thisYear' and
                month_generated = '$thisMonth'";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $nod = $row['nod'];
    }

    return empty($nod) ? 0 : $nod;
}


/*
+-------------------------------------------------------+
| 
+-------------------------------------------------------+
*/

function bdRemoveFromArray($array1, $array2)
{
    return array_values(array_diff($array1, $array2));
}

/*
+-------------------------------------------------------+
| Thats all                                             |
+-------------------------------------------------------+
*/
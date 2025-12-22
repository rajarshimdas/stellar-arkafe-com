<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   26-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$navtabs = [
    // ['admin',       'Admin',        ['HR', 'Admin']],
    // ['profile',     'Profile',      ['All']],
    ['leaves',      'Leaves',       ['All']],
    ['employees',   'Employees',    ['HR', 'Admin']],
    ['mis',         'MIS',          ['HR', 'Admin']],
];

# Leave Calendar
$dateRangeStart     = '2025-01-01';
$dateRangeEnd       = '2026-12-31';

/*
+-------------------------------------------------------+
| Functions                                             |
+-------------------------------------------------------+
*/

function getSundaysInRange(string $startDate, string $endDate): array
{
    $sundays = [];
    $current = strtotime($startDate);

    // Move to the first Sunday on or after the start date
    while (date('w', $current) != 0) {
        $current = strtotime('+1 day', $current);
    }

    $end = strtotime($endDate);

    while ($current <= $end) {
        $sundays[] = date('Y-m-d', $current);
        $current = strtotime('+7 days', $current); // Next Sunday
    }

    return $sundays;
}


function getAllDatesInRange(string $startDate, string $endDate): array
{
    $dates = [];

    $current = strtotime($startDate);
    $end = strtotime($endDate);

    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime('+1 day', $current);
    }

    return $dates;
}

// Example usage:
// $allDates = getAllDatesInRange('2025-06-01', '2025-06-05');
// print_r($allDates);



function bdGetHolidayListInRange(string $startdt, string $enddt, object $mysqli): array
{
    $holidays = [];

    $query = "select 
                    * 
                from 
                    `holidays` 
                where 
                    `dt` >= '$startdt' and 
                    `dt` <= '$enddt' and 
                    `active` > 0 
                order by 
                    `dt` ASC";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $holidays[] = $row;
    }

    return $holidays;
}

function bd2GetHolidays(string $sdt, string $edt, object $mysqli): array
{
    $query = "SELECT 
            * 
        FROM 
            holidays 
        where 
            active > 0 and 
            dt >= '$sdt' and
            dt <= '$edt'";


    $holidays = [];
    $result = $mysqli->query($query);

    while ($row = $result->fetch_assoc()) {

        $holidays[] = [
            'date'  => $row['dt'],
            'title' => $row['holiday'],
        ];
    }
    return $holidays;
}



function bdLeaveMonthyStatsForUser(
    array $user,                        // Users data
    array $startBalance,                // Starting Leave balance for the Year
    array $leaveAvailedByMonth,         // Leave Availed (approved by manager)
    array $leaveAppliedByMonth,         // Leave Applications not approved by manager
    int $forYear,                       // Year
    int $forMonth = 12                  // For leave encashment calculation
): array {

    ## Static Vars
    ##
    $leave_type_id = 2;
    
    /* 
    Refer db table rd_leave_attr
    $leave_attr_id = [
        10 => ['sl', 'Sanctioned'],
        30 => ['il', 'Informed'],
        31 => ['im', 'Informed Medical'],
        20 => ['un', 'Unsanctioned'],
    ];
    */

    ## Return array
    ##
    $rx = [
        'fx' => 'Rajarshi Das | bdLeaveMonthyStatsForUser() | 16-Dec-25',
        'employee' => $user['displayname'],
        'year' => $forYear,
        'month' => $forMonth,
        'user' => [
            'doj' => ($user['dt_doj'] > '1900-01-01') ? $user['dt_doj'] : 'NA',
            'doe' => ($user['dt_doe'] < '2050-12-31') ? $user['dt_doe'] : 'NA',
            'startBalMoNo' => empty($startBalance[$leave_type_id]['monthNo']) ? 'NA' : $startBalance[$leave_type_id]['monthNo'],
            'startBalNOD' => empty($startBalance[$leave_type_id]['noOfDays']) ? 0 : $startBalance[$leave_type_id]['noOfDays'],
            'daysPerMonth' => ($user['userhrgroup_id'] != 24) ? 1.75 : 1,
            'designation' => $user['hrgroup'],
        ],
    ];

    ## leaveAvailedByMonth
    ## <$rx[$row['user_id']][$row['leave_type_id']]> [$row['month']][$row['leave_attr_id']] = (float)($row['nod'] / 100);
    $lxAvailed = $leaveAvailedByMonth[$leave_type_id];
    // die(rx($lxAvailed));

    ## leaveAppliedByMonth
    ## <$la[$row['user_id']][$row['leave_type_id']]> [$row['month']] = (float)$n;
    $lxApplied = $leaveAppliedByMonth[$leave_type_id];
    // die(rx($lxApplied));

    ## No go if starting balance not set
    ##
    $startMonthNo = $rx['user']['startBalMoNo'];

    if ($startMonthNo == 'NA') {
        $rx['status'] = [
            'flag' => 'F',
            'message' => 'Starting balance not set.',
        ];
        return $rx;
    } else {
        $rx['status'] = [
            'flag' => 'T',
            'message' => 'Ok.',
        ];
    }

    $startBalNOD = $rx['user']['startBalNOD'];
    $daysPerMonth = $rx['user']['daysPerMonth'];

    $noOfWorkingMonths = 0;
    $totalLeaveAccrued = 0;
    $m = [];

    // Leave Availed
    $gtSL = 0;
    $gtIL = 0;
    $gtIM = 0;
    $gtUS = 0;
    $gtAvailed = 0;

    // Leave Applied
    $gtApplied = 0;

    // Paid | Unpaid
    $gtLeavePaid = 0;
    $gtLeaveUnpaid = 0;

    ## Month Loop
    ##
    for ($i = 0; $i < 12; $i++) {

        // Month
        $thisMoNo = $i + 1;
        $lastMoNo = $i;

        // Working Months 
        if ($thisMoNo > $startMonthNo) {
            $noOfWorkingMonths++;
        }

        // Total Leave Accrued
        $totalLeaveAccrued = ($thisMoNo < $startMonthNo) ? 0 : ($startBalNOD + ($noOfWorkingMonths * $daysPerMonth));
        $m[$thisMoNo]['totalLeaveAccrued'] = $totalLeaveAccrued;

        // Leave Availed
        $lxA = $lxAvailed[$thisMoNo];

        $availedSL = empty($lxA[10]) ? 0 : $lxA[10];
        $availedIL = empty($lxA[30]) ? 0 : $lxA[30];
        $availedIM = empty($lxA[31]) ? 0 : $lxA[31];
        $availedUS = empty($lxA[20]) ? 0 : $lxA[20];

        $m[$thisMoNo]['availedSL'] = $availedSL;
        $m[$thisMoNo]['availedIL'] = $availedIL;
        $m[$thisMoNo]['availedIM'] = $availedIM;
        $m[$thisMoNo]['availedUS'] = $availedUS;

        $availedTotal = $availedSL + $availedIL + $availedIM + $availedUS;
        $m[$thisMoNo]['availedTotal'] = $availedTotal;

        $gtSL += $availedSL;
        $gtIL += $availedIL;
        $gtIM += $availedIM;
        $gtUS += $availedUS;

        $gtAvailed += $availedTotal;

        $m[$thisMoNo]['gtSL'] = $gtSL;
        $m[$thisMoNo]['gtIL'] = $gtIL;
        $m[$thisMoNo]['gtIM'] = $gtIM;
        $m[$thisMoNo]['gtUS'] = $gtUS;
        $m[$thisMoNo]['gtAvailed'] = $gtAvailed;

        // BalSOM and BalEOM
        $gtAvailedLm = empty($m[$lastMoNo]['gtAvailed']) ? 0 : $m[$lastMoNo]['gtAvailed'];

        $moBalSOM = $totalLeaveAccrued - $gtAvailedLm;
        $moBalEOM = $totalLeaveAccrued - $gtAvailed;

        $m[$thisMoNo]['balSOM'] = $moBalSOM;
        $m[$thisMoNo]['balEOM'] = $moBalEOM;

        // Leave Applied
        $appliedLeaves = empty($lxApplied[$thisMoNo]) ? 0 : $lxApplied[$thisMoNo];
        $m[$thisMoNo]['appliedLeaves'] = $appliedLeaves;

        $gtApplied += $appliedLeaves;
        $m[$thisMoNo]['gtApplied'] = $gtApplied;

        // Paid | Unpaid
        if ($availedTotal > 0 && $moBalSOM > 0) {
            if ($moBalSOM >= $availedTotal) {
                $moPL = $availedTotal;
            } else {
                $moPL = $moBalSOM;
            }
        } else {
            $moPL = 0;
        }

        $m[$thisMoNo]['leavePaid'] = $moPL;

        $moUP = $availedTotal - $moPL;
        $m[$thisMoNo]['leaveUnpaid'] = $moUP;

        $gtLeavePaid += $moPL;
        $gtLeaveUnpaid += $moUP;

        $m[$thisMoNo]['gtLeavePaid'] = $gtLeavePaid;
        $m[$thisMoNo]['gtLeaveUnpaid'] = $gtLeaveUnpaid;
    }

    // Add Months data
    $rx['month'] = $m;

    ## Leave Encashment
    ##
    $rx['encashment'] = [
        'enEntitlement' => $m[$forMonth]['totalLeaveAccrued'],
        'enTotalAvailed' => $m[$forMonth]['gtAvailed'],
        'enPaidLeave' => $m[$forMonth]['gtLeavePaid'],
        'enMaxEntitlement' => ($m[$forMonth]['totalLeaveAccrued'] - $m[$forMonth]['gtLeavePaid']),
    ];

    // All Done. Dispatch the array.
    return $rx;
}

function bdReplaceZerosWithBlank($n)
{
    return ($n == 0) ? '&nbsp;' : $n;
}

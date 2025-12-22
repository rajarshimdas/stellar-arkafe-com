<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   18-Feb-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| bdGetDayManhours                                      |
+-------------------------------------------------------+
*/
function bdGetDayManhours(string $timesheetDate, int $user_id, object $mysqli): string
{
    $total_h = 0;
    $total_m = 0;

    $query = "SELECT 
                * 
            FROM 
                `view_timesheets` 
            where 
                `dtmysql` = '$timesheetDate' and 
                `user_id` = '$user_id' and
                `quality` < 1";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {

            $total_h = $total_h + $row["no_of_hours"];
            $total_m = $total_m + $row["no_of_min"];
        };
        $result->close();
    }

    if ($total_h > 0 || $total_m > 0) {
        $dayManhours = bdAddHourMin($total_h, $total_m);
        return $dayManhours;
    }

    return "00:00";
}

/*
+-------------------------------------------------------+
| bdGetDayManhoursWithoutLeave                                      |
+-------------------------------------------------------+
*/
function bdGetDayManhoursWithoutLeave(string $timesheetDate, int $user_id, object $mysqli): string
{
    $total_h = 0;
    $total_m = 0;

    $query = "SELECT 
                * 
            FROM 
                `view_timesheets` 
            where 
                `dtmysql` = '$timesheetDate' and 
                `user_id` = '$user_id' and
                `quality` < 1 and
                `project_id` > 10";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {

            $total_h = $total_h + $row["no_of_hours"];
            $total_m = $total_m + $row["no_of_min"];
        };
        $result->close();
    }

    if ($total_h > 0 || $total_m > 0) {
        $dayManhours = bdAddHourMin($total_h, $total_m);
        return $dayManhours;
    }

    return "00:00";
}

/*
+-------------------------------------------------------+
| Get total manhours                                    |
+-------------------------------------------------------+
*/
function bdGetDayTimesheet(string $timesheetDate, int $user_id, object $mysqli): ?array
{

    $query = "SELECT 
            * 
        FROM 
            `view_timesheets` 
        where 
            `dtmysql` = '$timesheetDate' and 
            `user_id` = '$user_id'";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $timesheet[] = $row;
        };
        $result->close();
    }

    if (isset($timesheet)) return $timesheet;

    return NULL;
}


function checkApiAccess($user_id, $mysqli)
{

    $pid = (isset($_SESSION["activePID"])) ? $_SESSION["activePID"] : 1;
    $role = bdUserRoleInProject($user_id, $pid, $mysqli);
    $rid = isset($role["role_id"]) ? $role["role_id"] : 50;

    if ($rid > 13) rdReturnJsonHttpResponse('200', ['F', "ApiAccess Forbidden. rid: " . $rid]);
}


function bdTaskStats(int $task_id, int $month_flag, int $uid, object $mysqli): array
{
    $h = 0;             // Hour
    $m = 0;             // Minutes
    $p = 0;             // Percent

    $mh = "00:00";

    // Month flag
    # 1 is Current month
    # 0 is Last month
    // Non-sargeable (MySQL will have to calculate date for each row to match where clause)
    #$month = ($month_flag > 0) ? " /* Current Month */ " : " and `dtmysql` < DATE_FORMAT(CURDATE(), '%Y-%m-01')";
    // Sargeable (MySQL receives a date for where clause)
    $firstOfThisMonth = date("Y-m") . '-01';
    $month = ($month_flag > 0) ? " /* Current Month */ " : " and `dtmysql` < '$firstOfThisMonth'";

    // uid flag
    # 0-100 is all users
    # 101 onwards actual users
    $user = ($uid > 100) ? " and `user_id` = '$uid'" : " /* all users */ ";


    $query = "SELECT 
                sum(`no_of_hours`) as h,
                sum(`no_of_min`) as m, 
                max(`percent`) as p
            FROM 
                `view_timesheets`
            WHERE 
                `task_id` = '$task_id' 
                $month
                $user";

    // echo $query;

    if ($result = $mysqli->query($query)) {
        if ($row = $result->fetch_assoc()) {
            $h = $h + $row["h"];
            $m = $m + $row["m"];
            $p = $p + $row["p"];
        };
        $result->close();
    }

    if ($h > 0 || $m > 0) {
        $mh = bdAddHourMin($h, $m);
    }

    return [
        'manhours'  => $mh,
        'percent'   => $p,
        'totalmin'  => ($h * 60) + $m,
    ];
}



function bdTaskAllocation(int $task_id, object $mysqli): string
{

    $query = "SELECT * FROM `view_task_allocation` where `task_id` = '$task_id'";
    $mx = '<div style="color:grey;">';

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            // $mx[] = $row;   // Allocated members array
            $mx = $mx . $row['fullname'] . '<br>';
        };
        $result->close();
    }

    $mx = $mx . "</div>";

    return $mx;
}


function bdTaskById(int $task_id, object $mysqli): array
{
    $task = [];
    $query = "select * from `view_tasks` where `task_id` = '$task_id'";

    if ($result = $mysqli->query($query)) {
        $task = $result->fetch_assoc();
        $result->close();
    }

    return $task;
}


/*
+-------------------------------------------------------+
| Time data - Current Month Or All                      |
+-------------------------------------------------------+
*/
function bdTaskUtilizedMH(
    $pid,
    $mysqli,
    $flag // CM | LM | A
) {
    $tk[0] = [
        'manhours'  => '00:00',
        'totalmin'  => 0,
        'percent'   => '0',
    ];

    $mo = date("Y-m-01");
    $q = " /* All months */ ";

    if ($flag == "CM") $q = " `dt` >= '$mo' and ";
    if ($flag == "LM") $q = " `dt` < '$mo' and ";

    $query = "select            
            `task_id` as `tid`,
            sum(`no_of_hours`) as `h`,
            sum(`no_of_min`) as `m`,
            max(`percent`) as `p`
        from
            `timesheet`
        where
            `project_id` = '$pid' and
            $q
            `active` > 0 and
            `quality` < 1
        group by
            `task_id`";

    # echo $query;

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            
            $tk[$row['tid']] = [
                'manhours'  => bdAddHourMin($row['h'], $row['m']),
                'totalmin'  => (($row['h'] * 60) + $row['m']),
                'percent'   => $row['p'],
            ];
        }
    }

    return $tk;
}

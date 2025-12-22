helloworld

<?php

$leaveId = 394;
// $leaveId = 198;

/*
timesheet
+---------------------+-----------------------+------+-----+---------------------+----------------+
| Field               | Type                  | Null | Key | Default             | Extra          |
+---------------------+-----------------------+------+-----+---------------------+----------------+
| id                  | bigint(20) unsigned   | NO   | PRI | NULL                | auto_increment |
| dt                  | date                  | NO   | MUL | NULL                |                |
| user_id             | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| project_id          | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| projectscope_id     | smallint(6)           | NO   |     | 1                   |                |
| projectstage_id     | tinyint(3) unsigned   | NO   |     | 1                   |                |
| department_id       | tinyint(3) unsigned   | NO   |     | 3                   |                |
| task_id             | int(10) unsigned      | NO   |     | 1                   |                |
| rd_leave_records_id | int(10) unsigned      | NO   |     | 0                   |                |
| subtask             | varchar(15)           | NO   |     | -                   |                |
| no_of_hours         | tinyint(4)            | NO   |     | NULL                |                |
| no_of_min           | tinyint(4)            | NO   |     | NULL                |                |
| work                | text                  | NO   |     | NULL                |                |
| worked_from         | tinyint(3) unsigned   | NO   |     | 10                  |                |
| approved            | tinyint(1)            | NO   |     | 0                   |                |
| quality             | tinyint(1)            | NO   |     | 0                   |                |
| tmstamp             | timestamp             | NO   |     | current_timestamp() |                |
| percent             | tinyint(4)            | NO   |     | 0                   |                |
| pm_review_flag      | tinyint(4)            | NO   |     | 0                   |                |
| active              | tinyint(1)            | NO   |     | 1                   |                |
+---------------------+-----------------------+------+-----+---------------------+----------------+
*/

$leaveUnits = [
    // Unit => [hour, minutes, inTotalMin, project_id]
    'F'     => [10, 0, 600, 2],
    'SH'    => [5, 0, 300, 3],
    'FH'    => [5, 0, 300, 3],
    'LC'    => [3, 30, 210, 4],
    'EG'    => [3, 30, 210, 4],
];

/*
+-------------------------------------------------------+
| Leave details                                         |
+-------------------------------------------------------+
*/
$leaveRec = bdGetUserLeaveRecById($leaveId, $mysqli);
// rx($leaveRec);

$sdt = $leaveRec['from_dt'];
$edt = $leaveRec['end_dt'];

$sx = $leaveRec['from_dt_units'];
$ex = $leaveRec['end_dt_units'];

$dtRange = getAllDatesInRange($sdt, $edt);
// rx($dtRange);


// Holidays
$holidays = bd2GetHolidays($sdt, $edt, $mysqli);
// rx($holidays);

// Flatten holidays
$h = [];
foreach ($holidays as $hx) {
    $h[] = $hx['date'];
}
// rx($h);

// days array
$leaveDates = [];
foreach ($dtRange as $dx) {

    if (!in_array($dx, $h) && (date("w", strtotime($dx)) != 0)) {
        switch ($dx) {
            case $sdt:
                $leaveDates[] = [$dx, $sx];
                break;
            case $edt:
                $leaveDates[] = [$dx, $ex];
                break;
            default:
                $leaveDates[] = [$dx, 'F'];
                break;
        }
    }
}
// rx($leaveDates);

/* timesheet data columns required
+---------------------+-----------------------+------+-----+---------------------+----------------+
| Field               | Type                  | Null | Key | Default             | Extra          |
+---------------------+-----------------------+------+-----+---------------------+----------------+
| dt                  | date                  | NO   | MUL | NULL                |                |
| user_id             | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| project_id          | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| rd_leave_records_id | int(10) unsigned      | NO   |     | 0                   |                |
| no_of_hours         | tinyint(4)            | NO   |     | NULL                |                |
| no_of_min           | tinyint(4)            | NO   |     | NULL                |                |
| work                | text                  | NO   |     | NULL                |                |
+---------------------+-----------------------+------+-----+---------------------+----------------+
*/
function addLeaveToTimesheet($leaveRec, $leaveDates, $leaveUnits, $mysqli)
{
    // rx($leaveRec);

    $leaveId    = $leaveRec['id'];
    $leaveUId   = $leaveRec['user_id'];

    foreach ($leaveDates as $d) {

        $lxDate     = $d[0];
        $lxUnit     = $d[1];
        $lxUnitH    = $leaveUnits[$d[1]][0];
        $lxUnitM    = $leaveUnits[$d[1]][1];
        $lxUnitTM   = $leaveUnits[$d[1]][2];
        $lxUnitPId  = $leaveUnits[$d[1]][3];


        // $query = "<div>$leaveId, $lxDate, $lxUnit, $lxUnitH:$lxUnitM hours, $lxUnitTM</div>";

        $query = "insert into timesheet (
                        dt, 
                        user_id,
                        project_id,
                        rd_leave_records_id, 
                        no_of_hours,
                        no_of_min,
                        work 
                    ) values (
                        '$lxDate',
                        '$leaveUId',
                        '$lxUnitPId',
                        '$leaveId',
                        '$lxUnitH',
                        '$lxUnitM',
                        '-'
                    )";
        // die($query);
        echo $query;

        // if (!$mysqli->query($query)) return false;
    }
    return true;
}




addLeaveToTimesheet($leaveRec, $leaveDates, $leaveUnits, $mysqli);
?>
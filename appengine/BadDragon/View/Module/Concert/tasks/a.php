<div>Helloworld! Bootstrap cm_allotted_mh column data</div>
<?php

$query = "select 
            id,
            manhours,
            manminutes
        from 
            task 
        where 
            id > 10 and active > 0";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $task[] = [
            'taskId' => $row['id'],
            'totalMin' => (($row['manhours'] * 60) + $row['manminutes']),
        ];
    };
    $result->close();
}
// rx($task);



$mo = date("Y-m-01");
$query = "select            
            `task_id` as `tid`,
            sum(`no_of_hours`) as `h`,
            sum(`no_of_min`) as `m`,
            max(`percent`) as `p`
        from
            `timesheet`
        where
            `dt` < '$mo' and 
            `active` > 0 and
            `quality` < 1
        group by
            `task_id`";

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $timesheet[$row['tid']] = (($row['h'] * 60) + $row['m']);
    }
}

// rx($timesheet);

foreach ($task as $t) {

    $taskId         = $t['taskId'];
    $totalMin       = $t['totalMin'];           // Total Allotted Min
    $utilizedMin    = $timesheet[$taskId];      // Utilized till end of last month

    $addedMin       = $totalMin - $utilizedMin; // Deduced Additional time allotted in current month

    $query = "UPDATE `task` SET `cm_added_mh` = '$addedMin' WHERE (`id` = '$taskId')";

    rd($query);
}

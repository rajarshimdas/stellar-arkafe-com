<div>Helloworld! Bootstrap cm_allotted_mh column data for all new tasks</div>
<?php
$mo = date("Y-m-01");

$query = "select 
            id,
            manhours,
            manminutes
        from 
            task 
        where 
            dt >= '$mo' and
            id > 10 and 
            active > 0";

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
echo count($task);

// $mysqli = cn2();

foreach ($task as $t) {

    $taskId         = $t['taskId'];
    $totalMin       = $t['totalMin'];                                           // Total Allotted Min

    $query = "UPDATE `task` SET `cm_added_mh` = '$totalMin' WHERE (`id` = '$taskId')";

    // rd($query);
    if (!$mysqli->query($query)) die('Error');
}

echo 'Done';

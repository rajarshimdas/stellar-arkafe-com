<?php /* 20 Sep | This was used to Bootstrap */ ?>
<div>Migration | Read from log and update task</div>
<?php
// task alloted mh
$query = "select id, manhours, manminutes, cm_date_flag from task";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        $row['id'],
        (($row['manhours'] * 60) + $row['manminutes']),
        $row['cm_date_flag']
    ];
}
// rx($tasks);

// Get log
$query = "select * from `taskallotmhlog` where `month` = '2025-08-01'";
$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $log[$row['task_id']] = $row['allottedmin'];
}
// rx($log);

$no = 0;
$mysqli = cn2();

foreach ($tasks as $t) {

    $tid = $t[0];
    $mh = (empty($log[$tid])) ? $t[1] : $log[$tid];
    $dt = date("Y-m-01");

    $query = "UPDATE 
                `task` 
            SET 
                `cm_date_flag` = '$dt', 
                `cm_allotted_mh` = '$t[1]', 
                `lm_allotted_mh` = '$mh' 
            WHERE 
                (`id` = '$tid')";
    
    // echo $query . '<br>';
    if (!$mysqli->query($query)) die('Error.' . $no . ' | ' . $query);
    
    $no++;
}

echo 'done';

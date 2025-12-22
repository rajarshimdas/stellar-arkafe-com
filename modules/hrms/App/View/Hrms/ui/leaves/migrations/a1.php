<?php /* 20 Sep | This was used to Bootstrap */ ?>
<div>Migration</div>
<?php
die("Not in use");

// task alloted mh
$query = "select id, manhours, manminutes from task";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        $row['id'],
        (($row['manhours'] * 60) + $row['manminutes'])
    ];
}
// var_dump($tasks);

$mysqli = cn2();

foreach ($tasks as $t) {

    $query = "insert into `taskallotmhlog`    
                (task_id, `month`, `allottedmin`)
            values
                ($t[0], '2025-08-01', $t[1])";

    if (!$mysqli->query($query)) {
        die('Error');
    }
}

echo 'Data saved.';

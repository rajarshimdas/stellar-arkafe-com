<?php

$pid = $_POST['pid'];
$sid = $_POST['sid'];
$h = isset($_POST['h']) ? (int)$_POST['h'] : 0;
$m = isset($_POST['m']) ? (int)$_POST['m'] : 0;
$cost = isset($_POST['cost']) ? (float)$_POST['cost'] : 0;

// Data Validation
if (is_integer($h) && is_integer($m)) {
    if ($h < 0 && $m < 0) {
        rdReturnJsonHttpResponse(
            '200',
            ['F', "Hours and/or Minutes are less than Zero."]
        );
    } else {
        if($m > 59 || $m < 0){
            rdReturnJsonHttpResponse(
                '200',
                ['F', "Minutes must be in the range of 0 to 59."]
            );
        }
        $tm = ($h * 60) + $m;
    }
} else {
    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Non-integer values for Hours (' . $h . ') and/or Minutes (' . $m . ') are invalid.']
    );
}

if (is_float($cost)) {
    $cost = floor($cost * 100);
} else {
    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Non-numeric value entered for cost is invalid.']
    );
}

/*
projecthistoric
+-------------+-----------------------+------+-----+---------------------+----------------+
| Field       | Type                  | Null | Key | Default             | Extra          |
+-------------+-----------------------+------+-----+---------------------+----------------+
| id          | int(10) unsigned      | NO   | PRI | NULL                | auto_increment |
| project_id  | mediumint(8) unsigned | NO   | MUL | NULL                |                |
| stage_id    | smallint(5) unsigned  | NO   |     | NULL                |                |
| manminutes  | int(10) unsigned      | NO   |     | NULL                |                |
| costinpaise | int(10) unsigned      | NO   |     | NULL                |                |
| dtime       | datetime              | NO   |     | current_timestamp() |                |
| active      | tinyint(4)            | NO   |     | 1                   |                |
+-------------+-----------------------+------+-----+---------------------+----------------+
*/

// Check if data already set
$flag = 0;
$query = "select 1 as flag from projecthistoric where project_id = '$pid' and stage_id = '$sid'";
$result = $mysqli->query($query);
if ($row = $result->fetch_assoc()) {
    $flag = $row['flag'];
}

if ($flag > 0) {
    $query = "update
                projecthistoric
            set
                manminutes = $tm,
                costinpaise = $cost   
            where
                project_id = $pid and
                stage_id = $sid";
} else {
    $query = "insert into 
                projecthistoric
            (project_id, stage_id, manminutes, costinpaise)
            values
            ($pid, $sid, $tm, $cost)";
}

$mysqli = cn2();

if ($mysqli->query($query)) {

    rdReturnJsonHttpResponse(
        '200',
        ["T", $query]
    );
} else {

    rdReturnJsonHttpResponse(
        '200',
        ["F", $query]
    );
}

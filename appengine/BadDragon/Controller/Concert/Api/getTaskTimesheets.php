<?php
$tid = $_POST["tid"];

$team = bdGetProjectTeam($pid, $mysqli);
/* 
Colorcode mindmap
+-----------+-----------+-------------------+-------------------+-------+
| approved  | quality   | pm_review_flag    | Type              | Color |
+-----------+-----------+-------------------+-------------------+-------+
| 1         | 0         | X (1)             | Approved          | Green |
+-----------+-----------+-------------------+-------------------+-------+
| 0         | 1         | 1                 | Remarked by PM    | Red   |
+-----------+-----------+-------------------+-------------------+-------+
| 1         | 1         | X (1)             | Rejected by PM    | Red   |
+-----------+-----------+-------------------+-------------------+-------+
| 0         | 1         | 0                 | Re-submitted      | Blue  |
+-----------+-----------+-------------------+-------------------+-------+
*/

$query = "select * from `view_timesheets` where `task_id` = '$tid' and `quality` < 1";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $ts[] = $row;
    $tu[$row["user_id"]][] = $row;
}

// Total Manhours of each team member
for ($n=0; $n < count($team); $n++) { 
    
    $u = $team[$n];
    $uid = $u["user_id"];

    $h = 0;
    $m = 0;

    $co = isset($tu[$uid]) ? count($tu[$uid]) : 0;
    
    for ($e = 0; $e < $co; $e++) {

        $tx = $tu[$uid][$e];

        $h = $h + $tx["no_of_hours"];
        $m = $m + $tx["no_of_min"];
    }

    $mh[$uid] = bdAddHourMin($h,$m);
}

// Table rows
$tr = "";
$no = 1;

// For each team member
for ($i = 0; $i < count($team); $i++) {

    $u = $team[$i];
    $uid = $u["user_id"];

    $co = isset($tu[$uid]) ? count($tu[$uid]) : 0;
    $flag1r = 0;

    for ($e = 0; $e < $co; $e++) {

        $tx = $tu[$uid][$e];

        $hr = $tx["no_of_hours"];
        $mn = $tx["no_of_min"];

        if ($flag1r < 1) {
            $tr = $tr . "<tr class='dataRow'>
                            <td rowspan='$co'>$no</td>
                            <td rowspan='$co' style='text-align: left;'>" . $u["fullname"] . "</td>";
            $no++;
        } else {
            $tr = $tr . "<tr class='dataRow'>";
        }

        $tr = $tr . "<td>" . $tx["date"] . "</td>
                    <td>" . $tx["percent"] . "</td>
                    <td>" . $hr . ":" . $mn . "</td>";

        if ($flag1r < 1) {
            $tr = $tr . "<td rowspan='$co'>" . $mh[$uid] . "</td></tr>";
            $flag1r++;
        } else {
            $tr = $tr . '</tr>';
        }
    }
}

// Response
rdReturnJsonHttpResponse(
    '200',
    ["T", $tr]
);

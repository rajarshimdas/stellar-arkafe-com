<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

$pid = $_POST['pid'];
$fdt = $_POST['fdt'];
$tdt = $_POST['tdt'];

$pname = bdProjectId2Name($pid, $mysqli);
$users = bdGetUsersArray($mysqli);
$mhRate = bdManhourCost($mysqli);

$finYear = bdFinancialYear($fdt);
$fy = $finYear['finStartYear'];


$query = "select 
            user_id, 
            sum(no_of_hours) as h, 
            sum(no_of_min) as m 
        from 
            timesheet 
        where 
            project_id = '$pid' and
            dt >= '$fdt' and 
            dt <= '$tdt' and
            quality < 1 and 
            active > 0
        group by 
            user_id";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $mh[$row['user_id']] = $row;
}

$co = empty($mh) ? 0 : count($mh);
$tbl = "";
$firstRow = "<td rowspan='$co'>$pname</td>";
$totalCost = 0;
$th = 0;
$tm = 0;

foreach ($users as $u) {

    $thisUId = $u['user_id'];

    if (!empty($mh[$thisUId])) {

        $name = $u['displayname'];
        $manhours = bdAddHourMin($mh[$thisUId]['h'], $mh[$thisUId]['m']);

        $th = $th + $mh[$thisUId]['h'];
        $tm = $tm + $mh[$thisUId]['m'];

        if (empty($mhRate[$fy][$thisUId])) {
            $ratef = 0;
            $tcost = 0;
        } else {
            $rate = $mhRate[$fy][$thisUId]['costRs'];
            $ratef = number_format($rate, 2, '.', ',');

            $tcostFloat = (((($mh[$thisUId]['h'] * 60) + $mh[$thisUId]['m']) / 60) * $rate);
            $tcost = number_format($tcostFloat, 2, '.', ',');

            $totalCost = $totalCost + $tcostFloat;
        }

        $tbl = $tbl . "<tr>
                    $firstRow
                    <td>$name</td>
                    <td style='text-align:right;'>$manhours</td>
                    <td style='text-align:right;'>$ratef</td>
                    <td style='text-align:right;'>$tcost</td>
                    </tr>";

        $firstRow = "";
    }
}


rdReturnJsonHttpResponse(
    '200',
    // ['F', $query]
    //['F', $fy]
    ['T', $tbl, number_format($totalCost, 2, '.', ','), bdAddHourMin($th, $tm)]
);

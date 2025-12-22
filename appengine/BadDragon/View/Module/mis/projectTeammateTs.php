<?php
include __DIR__ . "style.php";
// echo "pid: $pid | fdt: $fdt | tdt: $tdt";

// Get Users Array
$user = bdGetUsersArray($mysqli);
//var_dump($user);

// Get total manhours
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

// echo $query;
$th = 0;
$tm = 0;

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $t[$row["user_id"]] = bdAddHourMin($row["h"], $row['m']);

    $th = $th + $row['h'];
    $tm = $tm + $row['m'];
}

//var_dump($t);

?>

<div style="width: 600px; margin:auto;">
    <table class="tabulation">
        <tr class="bannerRow">
            <td colspan="3" style="text-align: center;">
                <div><?= bdProjectId2Name($pid, $mysqli) ?></div>
                <span><?= 'Date from ' . bdDateMysql2Cal($fdt) . " to " . bdDateMysql2Cal($tdt) ?></span>
            </td>
        </tr>
        <tr class="headerRow">
            <td style="width: 80px; text-align: center;">No</td>
            <td>Teammate</td>
            <td style="width: 100px; text-align: center;">Manhours</td>
        </tr>
        <?php


        /* */
        $no = 1;
        foreach ($user as $u):

            $uid = $u["user_id"];
            //echo "<br>" . $u["displayname"];

            if (isset($t[$uid])) {
                echo "<tr><td style='text-align: center;'>$no</td><td>" . $u["displayname"] . "</td><td style='text-align: center;'>" . $t[$uid] . "</td></tr>";
                $no++;
            }

        endforeach;

        ?>
        <tr class="footerRow">
            <td colspan="2" style="text-align: right;">Total</td>
            <td style="text-align: center;">
                <?= bdAddHourMin($th, $tm) ?>
            </td>
        </tr>
    </table>
</div>
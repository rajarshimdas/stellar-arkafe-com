<?php

$months = [
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
    'Jan',
    'Feb',
    'Mar',
];

$monthX = [
    "Jan" => "01",
    "Feb" => "02",
    "Mar" => "03",
    "Apr" => "04",
    "May" => "05",
    "Jun" => "06",
    "Jul" => "07",
    "Aug" => "08",
    "Sep" => "09",
    "Oct" => "10",
    "Nov" => "11",
    "Dec" => "12",
];

## Financial Year
#
require_once BD . 'View/Module/setFinancialYear.php';
//var_dump($finYear);

$finStartDt = $finYear['finStartDt'];
$finEndDt = $finYear['finEndDt'];

## Manhour Rates
#
$mhRateAll = bdManhourCost($mysqli);
//rx($mhRateAll);
$mhRateFY = empty($mhRateAll[$finYear['finStartYear']]) ? null : $mhRateAll[$finYear['finStartYear']];
//rx($mhRateFY);

## Projects
#
$projects = bdProjectList($mysqli);
// rx($projects);

## Users
#
$userById = bdGetUsersArrayX($mysqli);

## Timesheet Data
#
$query = "select
            project_id as pid,
            date_format(dt,\"%m\") as mo,
            user_id as uid,
            sum(no_of_hours) as h,
            sum(no_of_min) as m
        from
            timesheet
        where
            project_id > 15 and
            active > 0 and
            quality < 1 and
            dt >= '$finStartDt' and
            dt <= '$finEndDt'
        group by
            pid,
            mo,
            uid";

//echo $query;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {

        $uid = $row['uid'];
        $h = $row['h'];
        $m = $row['m'];

        $costPsPerHour = (empty($mhRateFY[$uid]['costPsPerHour'])) ? 0 : $mhRateFY[$uid]['costPsPerHour'];

        ## Log users with no mhRate
        #
        if (empty($mhRateFY[$uid]['costPsPerHour'])) {
            $noMh4User[] = $uid;
        }

        ## Result set
        #
        $tm[$row['pid']][$row['mo']][$row['uid']] = [
            'h' => $h,
            'm' => $m,
            'mh' => bdAddHourMin($h, $m),
            'rateInPs' => $costPsPerHour,
            'costInPs' => costInPs($costPsPerHour, $h, $m),
        ];
    };

    $result->close();
}

// rx($tm);

## Calculate cost in paise
#
function costInPs(float $rate, int $h, int $m): int
{
    $ps = round((($rate * $h) + ($rate * ($m / 60))), 0);
    return (int)$ps;
}
?>
<style>
    .tabulation tr {
        height: 35px;
    }

    .tabulation tr td {
        text-align: center;
        padding: 4px 0px;
    }

    .tabulation tbody {
        font-size: 0.8em;
    }

    div.tm {
        color: green;
    }

    div.cx {
        color: blue;
    }
</style>
<table class="tabulation">
    <thead>
        <tr class="headerRow">
            <td rowspan="2" style="width:50px;">No</td>
            <td rowspan="2">Project</td>
            <td colspan="7" style="text-align:right;border-right:0px;padding-right:10px;"><?= 'Financial Year: ' . $finYear['name'] ?>
            <td colspan="5" style="text-align:left;border-left:0px;padding-left:4px;"><?php dxSetActiveFinYear() ?></td>
            <td colspan="2">Total</td>
        </tr>
        <tr class="headerRow">
            <?php
            foreach ($months as $m) {
                echo "<td style='width:70px;'>$m</td>";
            }
            ?>
            <td style="width:80px;">MH</td>
            <td style="width:80px;">Cost</td>
        </tr>
    </thead>
    <?php
    if (empty($tm)) {
        echo ("</table><div class='messagebarError'>No data for " . $finYear['name'] . "</div>");
    }
    ?>
    <tbody>

        <?php
        $no = 1;
        $gth = 0;
        $gtm = 0;
        $gtc = 0;

        foreach ($projects as $p):
            $pid = $p['pid'];

            if (empty($tm[$pid])) {
                continue;
            } else {
                $t = $tm[$pid];
            }

        ?>
            <tr>
                <td style="font-size:1.1em;"><?= $no++ ?></td>
                <td style="text-align:left;padding:4px;font-size:1.1em;">
                    <?= ($pid < 500) ? $p['projectname'] : $p['jobcode'] . ' - ' . $p['projectname'] ?>
                </td>
                <?php
                $thr = 0;
                $tmn = 0;
                $tct = 0;

                foreach ($months as $mo) {

                    $mx = $monthX[$mo];
                    $tx = empty($tm[$pid][$mx]) ? [] : $tm[$pid][$mx];

                    $hr = 0;
                    $mn = 0;
                    $ct = 0;

                    foreach ($tx as $t) {
                        $hr += $t['h'];
                        $mn += $t['m'];
                        $ct += $t['costInPs'];
                    }

                    $ctInRs = number_format(($ct / 100), 2, '.', ',');

                    echo '<td><div class="tm">' . bdAddHourMin($hr, $mn) . "</div><div class='cx'>$ctInRs</div></td>";

                    $thr += $hr;
                    $tmn += $mn;
                    $tct += $ct;

                    if (empty($toH[$mx])) {
                        $toH[$mx] = $hr;
                    } else {
                        $toH[$mx] += $hr;
                    }

                    if (empty($toM[$mx])) {
                        $toM[$mx] = $mn;
                    } else {
                        $toM[$mx] += $mn;
                    }

                    if (empty($toC[$mx])) {
                        $toC[$mx] = $ct;
                    } else {
                        $toC[$mx] += $ct;
                    }
                }
                ?>
                <td><?= bdAddHourMin($thr, $tmn) ?></td>
                <td style="text-align: right; padding: 4px;">
                    <?= number_format(($tct / 100), 2, '.', ',') ?>
                </td>
            </tr>
        <?php
            $gth += $thr;
            $gtm += $tmn;
            $gtc += $tct;
        endforeach;

        if (!empty($tm)) :
        ?>
            <tr>
                <td colspan="2" style="text-align:left;padding:4px;font-size:1.1em;">Total Manhours</td>
                <?php
                foreach ($months as $mo) {
                    $mx = $monthX[$mo];
                    $thisMh = bdAddHourMin($toH[$mx], $toM[$mx]);
                    echo "<td>$thisMh</td>";
                }
                ?>
                <td><?= bdAddHourMin($gth, $gtm) ?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:left;padding:4px;font-size:1.1em;">Total Cost (Rs)</td>
                <?php
                foreach ($months as $mo) {
                    $mx = $monthX[$mo];
                    $thisCx = number_format($toC[$mx] / 100, 2, '.', ',');
                    echo "<td>$thisCx</td>";
                }
                ?>
                <td></td>
                <td style="padding: 4px;">
                    <?= number_format(($gtc / 100), 2, '.', ',') ?>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
## Warning
#
if (!empty($noMh4User)):
    $noMh4User = array_unique($noMh4User);
?>
    <div style="padding: 50px 0px 10px 15px; color:red;">Note: Manhour rate not available for following Members</div>
    <table class='tabulation'>
        <tr>
            <td style="width:50px;">No</td>
            <td style="text-align: left; padding: 0px 4px;">User</td>
        </tr>
        <?php
        $no = 1;
        foreach ($noMh4User as $u):
            $userNm = $userById[$u]['displayname'];
        ?>

            <tr>
                <td><?= $no++ ?></td>
                <td style="text-align: left; padding: 0px 4px;"><?= $userNm ?></td>
            </tr>

        <?php endforeach; ?>
    </table>
<?php
endif;

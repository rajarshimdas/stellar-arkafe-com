<?php


// Load class timesheetLockDt
require_once $w3etc . '/foo/timesheets/timesheetLockDt.php';
// require_once $w3root . '/studio/tms/hot7e/getHolidayList.php';
require_once BD . 'Controller/User/Api/getUserDayTimesheet.php';
require_once BD . 'Controller/User/User.php';
require_once BD . 'Controller/Common.php';

# Holidays
$holiday = getHolidayList(0, $mysqli);


# Calendar day Today
$dt_today = date("Y-m-d");

$d = date("d");
$m = date("m");
$Y = date("Y");
$dayname = date("D");


# lockdt
// Instantiate
$ts = new timesheetLockDt($uid, $holidayListFile, cn2());

// GetTimesheetLockDt
$lockdt = $ts->getTimesheetLockDt();

// $lockdtCal = dateMysql2Cal($lockdt);
// $lockdt = "2024-07-25";
//echo "<br>lockdt: $lockdt";

# 45 days ago from today
// Previous day
$ts = mktime(1, 15, 0, $m, ($d - 45), $Y);
$dt_from = date("Y-m-d", $ts);

# One day before lockdt
$ts_lockdt = strtotime($lockdt);
$dt_upto = date("Y-m-d", ($ts_lockdt - (24 * 60 * 60)));

// Day work hours
$dayTotalMin = (8 * 60) + 30;   // 8hrs 30min



function getUserData($uid, $dt_from, $mysqli)
{
    // Factor in DOJ and DOE
    $uX = bdGetUsersArrayByUID($uid, $mysqli);
    $doj = $uX['dt_doj'];
    // $doe = $uX['dt_doe'];
    // echo $doj . ' | ' . $doe;
    // $dt_mark = '2005-01-01';

    $dt_from = ($doj > $dt_from) ? $doj : $dt_from;
    // echo 'Today: ' . $dt_today . ' | lockdt: ' . $lockdt . ' | From: ' . $dt_from . ' | Upto: ' . $dt_upto;

    return [$uX, $dt_from];
}



function statusbox(
    array $tsX,
    string $lockdt,
    string $dt_today,
    int $d,
    int $m,
    int $Y,
    string $dayname,
    int $dayTotalMin,
    array $holiday,
    string $dt_from
): array {

    $noOfDays = 1;

    do {

        $date = (isset($dt)) ? $dt : $dt_today;
        $dx = (isset($dx)) ? $dx : $d;

        // echo '<br>Loop date: ' . $date;

        if ($dayname == 'Sun')
            $isHoliday = 1;
        else
            $isHoliday = isset($holiday[$date][0]) ? 1 : 0;

        $color = ($isHoliday > 0) ? 'grey' : 'orange';

        // Working Day Stats
        if ($isHoliday < 1) {

            $x = isset($tsX[$date]) ? $tsX[$date] : 'F';
            //$x = getUserDayTimesheetSum($uid, $dt_today, $mysqli);
            //var_dump($x);

            $total_min = ($x != 'F') ? (int)$x["total_min"] : 0;
            // echo "<br>total_min: " . $total_min;

            if ($total_min >= $dayTotalMin) {
                $color = 'green';
            } elseif ($color == "orange" && $date == $lockdt) {
                $color = 'red';
            }

            # For new users 
            if ($date < $dt_from){
                $color = 'gray';
            }
        }

        $dayX[$date] = [
            'd' => $dx,
            'isHoliday' => $isHoliday,
            'color' => $color,
        ];

        // Previous day
        $ts = mktime(1, 15, 0, $m, ($d - $noOfDays), $Y);
        $dt = date("Y-m-d", $ts);
        $dayname = date("D", $ts);
        $dx = date("d", $ts);

        $noOfDays++;
    } while ($date > $lockdt && $noOfDays < 15);

    return $dayX;
}


function status45d(
    $uid,
    $tsX,
    $dt_from,
    $dt_upto,
    $dayTotalMin,
    $ts_lockdt,
    $holiday
) {

    $status45d = 'gray';

    //var_dump($tsX);
    $thisDay = $dt_upto;

    $ts = strtotime($dt_upto);
    $thisDayName = date("D", $ts);
    $thisDayCal = date("d-M-y", $ts);

    $flag = 0;
    $i = 1;
    $tsMsg = '<table id="dxTbl" style="width:100%;"><tr><td style="width:50px;">No</td><td>Date</td><td style="width:100px;">Hours</td></tr>';
    $srno = 1;

    do {
        // Reset
        $status45d = 'gray';
        $t = isset($tsX[$thisDay]) ? $tsX[$thisDay] : ["total_min" => 0];
        $mh = 0;

        if ($thisDayName == 'Sun' || isset($holiday[$thisDay])) {
            $status45d = 'orange';
        } else {

            //var_dump($t); die;

            if ((int)$t['total_min'] < $dayTotalMin) {
                $status45d = 'red';
                $flag++;
                $mh = bdMinutes2Manhours((int)$t['total_min']);

                $tsMsg = $tsMsg . "<tr><td>" . $srno . "</td><td>" . $thisDayCal . "</td><td>$mh</td></tr>";
                $srno++;
            } else {
                $status45d = 'green';
            }
        }

        // echo $thisDay . ' | ' . $status45d . ' | ' . $thisDayCal . ' | '. $mh .'<br>';

        # Previous Day
        $i++;
        $ts5 = ($ts_lockdt - (24 * 60 * 60 * $i));
        $thisDay = date("Y-m-d", $ts5);
        $thisDayName = date("D", $ts5);
        $thisDayCal = date("d-M-y", $ts5);
    } while ($i < 100 && $dt_from <= $thisDay /* && $flag < 1 */);

    $tsMsg = $tsMsg . "</tr>";

    # For new users
    if ($dt_from > $dt_upto) {
        $status45d = 'gray';
        $dxTsInValid = "";
    } else {
        $status45d = ($flag > 0) ? 'red' : 'green';
        $dxTsInValid = ($flag > 0) ? 'id="switch" onclick="dxTsInValid(\'' . $uid . '\')"' : "";
        // var_dump($tsDtInValid);
    }

    return [
        'color' => $status45d,
        'link' => $dxTsInValid,
        'table' => $tsMsg,
    ];
}


function tsWidget($uid, $dayX, $x)
{
    //var_dump($dayX);
    //var_dump($x);
?>
    <!-- Swatch -->
    <table id="statusbox">
        <tr id="swatch">
            <!-- Boxes -->
            <?php foreach ($dayX as $dt): ?>
                <td style="background-color: <?= $dt["color"] ?>;"></td>
            <?php endforeach ?>
            <!-- Circle -->
            <td <?= $x['link'] ?> style="border-radius: 25px; background-color: <?= $x['color'] ?>;"></td>
        </tr>
        <tr>
            <?php foreach ($dayX as $dt): ?>
                <td><?= $dt["d"] ?></td>
            <?php endforeach ?>
            <td>45d</td>
        </tr>
    </table>
    <script>
        missedDates['<?= $uid ?>'] = '<?= $x['table'] ?>'
    </script>
<?php
}
?>


<script>
    const missedDates = []

    function dxTsInValid(uid) {
        showAlert("Timesheet Incomplete for Dates", missedDates[uid])
    }
</script>


<style>
    table#statusbox {
        border-collapse: separate;
        border-spacing: 4px;
        /*background-color: var(--rd-home-box1);*/
        background-color: #d7d7d7;
    }

    table#statusbox tr {
        height: 20px;
    }

    table#statusbox tr td {
        border: 0px solid red;
        width: 20px;
        font-size: 0.6em;
        color: var(--rd-dark-gray);
        /*background-color: #959595;*/
    }

    table#statusbox tr#swatch td {
        border: 1px solid black;
        /* border-radius: 20px; */
        background-color: white;
    }

    table#statusbox tr#swatch td#switch:hover {
        cursor: pointer;
    }

    table#dxTbl {
        border-collapse: collapse;
    }

    table#dxTbl tr td {
        text-align: center;
        border: 1px solid gray;
        padding: 4px;
    }

    table#dxTbl tr td:nth-child(2) {
        text-align: left;
    }
</style>
<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   18-Feb-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

require_once BD . 'Controller/Projects/Projects.php';
require_once BD . 'Controller/User/User.php';

// GUI Active Tab
function bdActiveTab(string $tab, string $currentTab): void
{
    if ($tab == $currentTab) echo 'class="activeTab"';
    // return true;
}


# Check session time-out
$now = time();

/* Are session variables available */
if (!empty($_SESSION["loginexp"])) {
    /* Has the session expired */
    $loginexp = $_SESSION["loginexp"];
    if (($loginexp + 5) < ($now + 5)) {
        header("Location:" . BASE_URL . "login/index.cgi?e=session-time-out");
        die;
    } else{
        $_SESSION['loginexp'] = $now + (8 * 3600);
    }
} else {
    // This is a new session. Redirect to login page.
    header("Location:" . BASE_URL . "login/");
    die;
}

/*
+-----------------------------------------------------------+
| Session Vars                                              |
+-----------------------------------------------------------+
| bdMessageFlag     Purpose                                 |
| 0                 Normal state                            |
| 1                 Display success message (bdMessageTxt)  |
| 2                 Display error message (bdMessageTxt)    |
+-----------------------------------------------------------+
*/

// Active ProjectId (0 if none)
$activePID = (isset($_SESSION["activePID"])) ? $_SESSION["activePID"] : 0;
$pid = $activePID;

// Active Date
$activeDate = (isset($_SESSION["activeDate"])) ? $_SESSION["activeDate"] : date("d-M-y");

// Capture Session Vars 
$bdMessageTxt = isset($_SESSION['bdMessageTxt']) ? $_SESSION['bdMessageTxt'] : "-";
$bdMessageFlag = isset($_SESSION['bdMessageFlag']) ? $_SESSION['bdMessageFlag'] : 0;

// Reset Session Vars
$_SESSION['bdMessageTxt'] = "-";
$_SESSION['bdMessageFlag'] = 0;

/*
+-------------------------------------------------------+
| Static Vars                                           |
+-------------------------------------------------------+
*/
$monthCal2Num = [
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
    "Dec" => "12"
];

$monthNum2Cal = [
    "01" => "Jan",
    "02" => "Feb",
    "03" => "Mar",
    "04" => "Apr",
    "05" => "May",
    "06" => "Jun",
    "07" => "Jul",
    "08" => "Aug",
    "09" => "Sep",
    "10" => "Oct",
    "11" => "Nov",
    "12" => "Dec",
];

/*
+-------------------------------------------------------+
| Functions                                             |
+-------------------------------------------------------+
*/
function bdGo2uri(string $uri): bool
{
    header("Location:" . BASE_URL . "$uri");
    die;

    return true;
}

function bdLoadFn(array $fx): bool
{

    for ($i = 0; $i < count($fx); $i++) {

        $fn = $fx[$i];
        $f = BD . "Controller/" . $fn . "/" . $fn . ".php";

        if (file_exists($f)) {
            require_once $f;
        } else {
            die("Error [bdLoadFn]: Could not load " . $fn);
        }
    }

    return true;
}



function bdLoadView(array $fx): bool
{

    for ($i = 0; $i < count($fx); $i++) {

        $fn = $fx[$i];  // Path relative to View folder
        $f = BD . "View/" . $fn . ".php";

        if (file_exists($f)) {
            require_once $f;
        } else {
            die("Error [bdLoadFn]: Could not load " . $fn);
        }
    }

    return true;
}


function bdAddHourMin(int $hour, int $min): string
{
    $min = ($hour * 60) + $min;
    /*
    // Total min in 1 day
    $min1day = (8 * 60) + 30;

    // Days
    $d = 0;
    $totalMin = ($hour * 60) + $min;
    if ($totalMin > $min1day) {
        $d = floor($totalMin / $min1day);
    }

    // Remaining minutes
    $min = $totalMin - ($d * $min1day);
    */

    // Convert min into hours and min
    $h = floor($min / 60);
    $m = $min - ($h * 60);

    // send d hh:mm
    // $time = ($d > 0) ? $d . "d $h:$m" : "$h:$m";

    $time = "$h:$m";
    return $time;
}

function bdManhours2Minutes(string $manhours): int
{
    $min = 0;

    $x = explode(":", $manhours);
    $min = ($x[0] * 60) + $x[1];

    return $min;
}

/**
 * @param date 
 * 
 * @return MySQL format date
 */
function bdDateCal2Mysql(?string $date): string
{
    if (empty($date)) return '';

    $monthX = array(
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
        "Dec" => "12"
    );

    $a = explode("-", $date);

    $year   = "20" . $a[2];
    $month  = $monthX[$a[1]];
    $day    = $a[0];

    $mysqldt = $year . "-" . $month . "-" . $day;

    return $mysqldt;
}


/**
 * @param date Date in MySQL format yyyy-mm-dd
 * 
 * @return date Calendar format date
 */
function bdDateMysql2Cal(?string $date): string
{
    if (empty($date) || $date == '0000-00-00') return '';

    $monthX = array(
        "01" => "Jan",
        "02" => "Feb",
        "03" => "Mar",
        "04" => "Apr",
        "05" => "May",
        "06" => "Jun",
        "07" => "Jul",
        "08" => "Aug",
        "09" => "Sep",
        "10" => "Oct",
        "11" => "Nov",
        "12" => "Dec"
    );

    $a = explode("-", $date);

    $year   = $a[0];
    $month  = $monthX[$a[1]];
    $day    = $a[2];

    // Parse Year into 2 digits
    $x = str_split($year);
    $year = $x[2] . $x[3];

    // Result
    $dt = $day . "-" . $month . "-" . $year;

    return $dt;
}

/**
 * @param date input Date in calendar format
 * 
 * @return date output Date in unix timestamp
 */
function bdDateCal2UnixTS(?string $date): string
{
    if (empty($date)) return '';

    $monthX = array(
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
        "Dec" => "12"
    );

    $a = explode("-", $date);

    $year = "20" . $a[2];
    $month = $monthX[$a[1]];
    $day = $a[0];

    // To do - Validate if its a true date
    // Get Unix timestamp
    $unixTimestamp = mktime(0, 0, 0, $month, $day, $year);

    return $unixTimestamp;
}


function bdTimeH(int $h, int $m): int
{
    return $h + (floor($m / 60));
}


function bdMandays2Manhours(int $mandays): string
{
    $manhours = '0:0';
    $oneMandayMinutes = (8 * 60) + 30; // 8:30 min

    if ($mandays > 0) {

        $totalMin = $mandays * $oneMandayMinutes;
        $manhours = bdTimeH(0, $totalMin);
    }

    return $manhours;
}


function bdNoOfSundaysInMonth(int $m, int $y): int
{

    // Months
    $mx = [
        "00",
        "01",
        "02",
        "03",
        "04",
        "05",
        "06",
        "07",
        "08",
        "09",
        "10",
        "11",
        "12",
    ];

    // First day
    // $dt1 = $y . "-" . $mx[$m] . "-01";

    // Last day
    $days = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    // $dt2 = $y . "-" . $mx[$m] . "-" . $days;

    // echo $dt1 . ' | ' . $dt2 . "<br>";

    $noOfSundays = 0;
    for ($i = 0; $i < $days; $i++) {

        $ts = mktime(1, 10, 15, $m, ($i + 1), $y);
        //echo date("D M j G:i:s T Y", $ts) . "<br>";

        if (date("D", $ts) == "Sun") {

            // echo date("D M j G:i:s T Y", $ts) . "<br>";
            $noOfSundays++;
        }
    }

    return $noOfSundays;
}

function bdMinutes2Manhours($minutes)
{
    $h = 0;
    $m = 0;

    if ($minutes != 0) {
        $min = abs($minutes);
        $h = floor($min / 60);
        $m = $min % 60;
    }

    // return $h . ':' . $m;
    $rx = sprintf("%01d:%02d", $h, $m);

    if ($minutes < 0) $rx = '- ' . $rx;

    return $rx;
}


function bdIsValidDateMySQLFormat(string $date): bool
{
    if (empty($date)) return false;

    $x = explode("-", $date);

    $co = isset($x) ? count($x) : 0;
    if ($co == 3) {
        $dtD = (int)$x[2];
        $dtM = (int)$x[1];
        $dtY = (int)$x[0];
    }

    if (!is_int($dtM) || empty($dtM) || $dtM < 1 || $dtM > 12) return false;
    if (!is_int($dtY) || empty($dtY) || $dtY < 1977 || $dtY > 3000) return false;

    // Check last day of month | Todo
    $noOfDays = date('t', strtotime($dtY . '-' . $dtM . '-01'));
    //echo $noOfDays;
    if (!is_int($dtD) || empty($dtD) || $dtD < 1 || $dtD > $noOfDays) return false;

    // Ok
    return true;
}



function bdFinancialYear(string $date = "X"): array
{
    $date = ($date == "X") ? date("Y-m-d") : $date;

    if (!bdIsValidDateMySQLFormat($date)) {
        return [
            'error' => "Date error",
            'name' => "Error",
            'finStartYear' => "Error"
        ];
    }

    $x = explode('-', $date);
    $dtYear = $x[0];

    // Before or After 31st Mar
    if ($date <= $dtYear . '-03-31') {
        $fy = [
            'name' => ($dtYear - 1) . '-' . ($dtYear - 2000),
            'finStartYear' => ($dtYear - 1),
            'finEndYear' => $dtYear,
        ];
    } else {
        $fy = [
            'name' => $dtYear . '-' . (($dtYear - 2000) + 1),
            'finStartYear' => $dtYear,
            'finEndYear' => ($dtYear + 1),
        ];
    }

    $fy['finStartDt'] = $fy['finStartYear'] . '-04-01';
    $fy['finEndDt'] = $fy['finEndYear'] . '-03-31';

    return $fy;
}



if (!function_exists('rx')) {
    function rx($var)
    {
        echo '<pre>', var_dump($var), '</pre>';
    }
}

if (!function_exists('rd')) {
    function rd($var)
    {
        echo '<div>'.$var.'</div>';
    }
}

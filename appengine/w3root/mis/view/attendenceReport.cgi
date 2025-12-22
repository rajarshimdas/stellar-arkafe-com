<?php /* #!/usr/bin/php
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 28-Jan-2013       			|
| Updated On:                                           |
+-------------------------------------------------------+
*/
// Variable Initialization
$noOfWorkingDays    = 0;
$holidayFlag        = 0;

// Get User Inputs
$mCal2      = $_GET["mCal2"];

$dateX      = explode("20", $mCal2);
$thisMo     = trim($dateX[0]);
$thisYr     = '20'.$dateX[1];

// The monthFlag will be parsed by 'reportMonth.php'
$monthFlag = $thisYr.'-'.$mX[$thisMo];
// echo '<br>dateX0: '.$thisMo.' dateX1: '.$thisYr.'<br>monthFlag: '.$monthFlag.'<br>';

/*
+-------------------------------------------------------+
| Reporting dateRange					|
+-------------------------------------------------------+
*/
include $pathInclude.'/cronjobs/run/studioR/bin/reportMonth.php';

/*
+-------------------------------------------------------+
| Header                                                |
+-------------------------------------------------------+
*/
echo '<style>
        body {
            font: 10pt Arial;
        }
        .tabulation {
            font: 10pt Arial;
        }
        .headerRow {
            background: cyan;
            border: 1px solid black;
            text-align: center
        }
    </style>
    Report Month: '.$reportMonthName.'<br>';


echo '<table class="tabulation" border="1" cellspacing="0">
    <tr class="headerRow"><td rowspan="2" style="width: 80px; padding-left:5px" align="left">Teammate</td>';
/*
+-------------------------------------------------------+
| Dates                                                 |
+-------------------------------------------------------+
*/
for ($i = 1; $i <= $noOfDaysInReportMonth; $i++) {
    echo '<td width="30px">'.$i.'</td>';
}
echo '<td colspan="2" width="80px">Totals</td></tr>';

/*
+-------------------------------------------------------+
| Header                                                |
+-------------------------------------------------------+
*/
echo '<tr class="headerRow">';
/*
+-------------------------------------------------------+
| Day                                                   |
+-------------------------------------------------------+
*/
for ($i = 1; $i <= $noOfDaysInReportMonth; $i++) {
    $day = date("D", mktime(0,0,0,$defaultReportMonth,$i,$defaultReportYear));
    echo '<td>'.$day.'</td>';
    $moDayX[] = array ("dt" => $i, "day" => $day);
}
echo '<td align="center">P</td><td align="center">M</td></tr>';

/*
+-------------------------------------------------------+
| Get Holiday Info                                      |
+-------------------------------------------------------+
*/
// Read the file
//echo $virtualFolderPath.'/w3root/studio/tms/hot7e/getHolidayList.php';
include_once $virtualFolderPath.'/w3root/studio/tms/hot7e/getHolidayList.php';

$pathInclude = $virtualFolderPath.'/w3etc/';
$hoX = getHolidayList('Delhi', $pathInclude);

for ($e = 0; $e < $noOfDaysInReportMonth; $e++) {

    // Check if its a holiday
    $holidayFlag = 0;

    if ($moDayX[$e]["dt"] < 10) {
        $today = $defaultReportYear.'-'.$defaultReportMonth.'-0'.$moDayX[$e]["dt"];
    } else {
        $today = $defaultReportYear.'-'.$defaultReportMonth.'-'.$moDayX[$e]["dt"];
    }
    // echo '<br> ++ '.$moDayX[$e]["dt"].' | '.$moDayX[$e]["day"].' | '.$today;

    if ($moDayX[$e]["day"] == "Sun") {
        // Sunday
        $holidayFlag = 1;
    } else {
        // Check for Saturdays and Holidays
        for ($i = 0; ($i < count($hoX) && $holidayFlag < 1); $i++) {
            if ($hoX[$i]["dt"] === $today) {
                $holidayFlag = 1;
            }
        }
    }

    if ($holidayFlag < 1) {
        $noOfWorkingDays++;
    }

    $moDX[] = array ("dt" => $today, "day" => $moDayX[$e]["day"], "flag" => $holidayFlag);

}
/*
+-------------------------------------------------------+
| Users Array (Including deleted ones)                  |
+-------------------------------------------------------+
*/
$query = "select
            t1.id,
            t1.fullname            
        from
            users as t1
        order by
            t1.fullname
        ASC";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $userX[] = array(
                "uid"   => $row[0],
                "fn"    => $row[1]
        );
    }
    $result->close();

}
/*
+-------------------------------------------------------+
| Data Rows                                             |
+-------------------------------------------------------+
*/
for ($n = 0; $n < count($userX); $n++) {

    $punchFlag  = 0;
    $thisUID    = $userX[$n]["uid"];
    $totalP     = 0;        // Present
    $totalM     = 0;        // Missed Punch



    // Loop for all days of the month
    for ($i = 0; $i < $noOfDaysInReportMonth; $i++) {

        $thisDt = $moDX[$i]["dt"];
        $foundRowFlag   = 0;
        $intimepunch    = 0;
        $outtimepunch   = 0;
        $timeInMin      = 0;
        $attendanceFlag = '&nbsp;';


        // Holiday Background
        if ($moDX[$i]["flag"] > 0) {
            $background = 'style="background:#c8c8c8;"';
        } else {
            $background = 'style="background:white;"';
        }

        // Query
        $query = "select
                    intimepunch,
                    outtimepunch,
                    TIME_TO_SEC(TIMEDIFF(outtime, intime))
                from
                    iostat
                where
                    user_id = $thisUID and
                    dt = '$thisDt'";

        if ($result = $mysqli->query($query)) {

            if ($row = $result->fetch_row()) $foundRowFlag++;

            $intimepunch    = $row[0];
            $outtimepunch   = $row[1];

            $timeInMin      = floor($row[2] / 60);

            $result->close();
        }

        if ($intimepunch > 0 && $outtimepunch > 0) {
            $timeInMin = $timeInMin;
        } else {
            $timeInMin = 0;
        }

        if ($intimepunch > 0) {
            $attendanceFlag = "M";
            if ($outtimepunch > 0) {
                $attendanceFlag = "P";
                $totalP++;
            } else {
                $totalM++;
            }
        }

        // Get Flags
        // echo '<td '.$background.'>'.$attendanceFlag.'</td>';

        $punchFlag = $punchFlag + $foundRowFlag;

        // Put it in an array
        $dataX[] = array ("dt" => $thisDt, "aFlag" => $attendanceFlag, "bg" => $background);

    }

    // display row if there is some data
    if ($punchFlag > 0) {
        echo '<tr><td>&nbsp;'.$userX[$n]["fn"].'</td>';
        for ($x = 0; $x < count($dataX); $x++) {
            echo '<td '.$dataX[$x]["bg"].'>'.$dataX[$x]["aFlag"].'</td>';

        }
        echo '<td align="center">'.$totalP.'</td><td align="center">'.$totalM.'</td></tr>';
    }

    unset ($dataX);
}


/*
+-------------------------------------------------------+
| Read email distribution List and send mail		|
+-------------------------------------------------------+

$mail_subject = "Attendance Report :: ".$reportMonthName;

// EDIT THIS FOR APPROPRIATE CONFIGURATION FILE CONTAINING EMAIL ADDRESSES
$config = $pathInclude."/cronjobs/config/teamAll.txt";
require $pathInclude.'/cronjobs/run/studioR/bin/readConfig.php';
require $pathInclude.'/foo/cronbin/qmail.php';

qmail ($mail_to, $mail_cc, $mail_bcc, $mail_from, $mail_subject, $mail_body, $mail_imageX);
*/

/*
+-------------------------------------------------------+
| Clean-up and End                                      |
+-------------------------------------------------------+
*/
$mysqli->close();


echo '</table>
    &nbsp;<br>Total Working Days: '.$noOfWorkingDays.' <!--Manhours: '.($noOfWorkingDays * 8).'-->
    </body>
</html>';

?>



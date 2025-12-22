<?php
/*
  +---------------------------------------------------------+
  | Rajarshi Das                                            |
  +---------------------------------------------------------+
  | Created On: 22-Mar-2012                                 |
  | Updated On:                                             |
  +---------------------------------------------------------+
 */

if (!$virtualFolderPath) {
    die('virtualFolderPath is not available...');
}

require_once 'foo/timeAdd.php';
require_once 'foo/dateMysql2Cal.php';
require_once 'foo/dateCal2Mysql.php';
require_once $virtualFolderPath . '/w3root/studio/tms/hot7e/getHolidayList.php';
require_once $virtualFolderPath . '/w3root/studio/tms/hot7e/getTimesheetDataForThisTeammate.cgi';

/*
  +---------------------------------------------------------+
  | Class                                                   |
  +---------------------------------------------------------+
 */

class timesheetTabulate {
    /* Properties */

    private $uid;
    private $mysqli;
    private $tableWidth;
    private $hideIOdata;
    private $hideEditButtons;
    private $daysOffX;
    private $daysOffX_co;
    private $tempManhour;
    private $rowBackGround = '#FFFFFF';
    private $workedFromX;

    /* Constructor Class */

    function timesheetTabulate($uid, $tableWidth, $HideIOdata, $HideEditButtons, $daysOffX, $virtualFolderPath, $mysqli) {

        // Set default values for the instance properties
        $this->uid = $uid;
        $this->mysqli = $mysqli;
        $this->tableWidth = $tableWidth;
        $this->hideIOdata = $HideIOdata;
        $this->hideEditButtons = $HideEditButtons;
        $this->daysOffX = $daysOffX;
        $this->daysOffX_co = count($daysOffX);

        // Run function
        timesheetTabulate::getWorkedFromX($virtualFolderPath);
    }

    /* Methods */

    function generateHeaderRow() {

        if ($this->hideIOdata !== "Y") {
            $rowspan = 2;
        } else {
            $rowspan = 1;
        }

        $bgColor = "#eee8a0"; // #FFF6F4
        ?>
        <table width="<?php echo $this->tableWidth; ?>" border="0" cellpadding="2" cellspacing="0" style="font-size: 10pt">
            <tr style = "background:#FFF6F4;font-weight:bold;" align = "center">

                <td class = "cellHeaderLeft" width = "8%" rowspan = "<?php echo $rowspan; ?>">Date</td>
                <td class = "cellHeader" width = "8%" rowspan = "<?php echo $rowspan; ?>">Day</td>
                <td class = "cellHeader" width = "48%" rowspan = "<?php echo $rowspan; ?>" align = "left">Project</td>
                <td class = "cellHeader" width = "8%" rowspan = "<?php echo $rowspan; ?>">Total<br>(H:M)</td>
                    <?php
                    if ($this->hideIOdata !== "Y") {
                        echo '<td class="cellHeader" colspan="3" style="border-bottom:0px;background:' . $bgColor . ';">Time Register</td>';
                    }
                    ?>
            </tr>
            <?php
            if ($this->hideIOdata !== "Y") {
                echo '<tr align="center" style="background:' . $bgColor . ';font-weight:bold">
                        <td class="cellHeader" width="8%">In</td>
                        <td class="cellHeader" width="8%">Out</td>
                        <td class="cellHeader" width="8%">Hours</td>
                    </tr>';
            }
        }

        function generateDataRow($date) {

            // Reset
            $this->tempManhour = '&nbsp';

            // Check Holiday
            $holidayFlag = 0;
            $hday = 'X';
            $bg = '#FFFFFF';
            $holidayBackground = '#DDDDDD';
            $hX = $this->daysOffX;

            for ($i = 0; $i < $this->daysOffX_co; $i++) {
                //echo '<br>dt: '.$hX[$i]["dt"].' | hday: '.$hX[$i]["hday"];
                if ($hX[$i]["dt"] === $date) {
                    $holidayFlag = 1;
                    $bg = $holidayBackground;
                    $hday = $hX[$i]["hday"];
                }
            }

            // Day info
            $x = explode("-", $date);
            $month = $x[1];
            $day = $x[2];
            $year = $x[0];

            $dayName = date("D", mktime(0, 0, 0, $month, $day, $year));
            if ($dayName === 'Sun') {
                $holidayFlag = 1;
                $bg = $holidayBackground;
            }

            $this->rowBackGround = $bg;
            // Work
            // Biometric Time Attendance data
            $ioX = timesheetTabulate::getIOdata($date);
            $intime = $ioX["intime"];
            $outtime = $ioX["outtime"];
            $ioManhour = $ioX["manhours"];            
            
            // Date 
            if ($hday !== 'X'){
                $displayDate = dateMysql2Cal($date).'<br>'.$hday;
            } else {
                $displayDate = dateMysql2Cal($date);
            }
            

            echo '<tr align="center" style="background:' . $bg . ';vertical-align:top;">
                    <td class="cellRowLeft">' . $displayDate . '</td>
                    <td class="cellRow">' . $dayName . '</td>
                    <td class="cellRow">' . timesheetTabulate::workTabulation4Date($date) . '</td>
                    <td class="cellRow">' . $this->tempManhour . '</td>
                    <td class="cellRow">' . $intime . '</td>
                    <td class="cellRow">' . $outtime . '</td>
                    <td class="cellRow">' . $ioManhour . '</td>
                </tr>';
        }

        function getIOdata($date) {

            $uid = $this->uid;
            $mysqli = $this->mysqli;


            // Variable Initialization
            $ioFlag = 0;
            $intimepunch = 0;
            $outtimepunch = 0;
            $intime = '&nbsp;';
            $outtime = '&nbsp;';
            $manhours = '&nbsp;';

            $query = "select
                    intimepunch,
                    DATE_FORMAT(intime, '%H:%i'),
                    outtimepunch,
                    DATE_FORMAT(outtime, '%H:%i'),
                    manhours
                from
                    iostat
                where
                    user_id = $uid and
                    dt = '$date'";

            //echo "<br>Q(getIOdata): ".$query;

            if ($result = $mysqli->query($query)) {

                if ($row = $result->fetch_row()) {

                    $ioFlag = 1;
                    $intimepunch = $row[0];
                    $intime = $row[1];
                    $outtimepunch = $row[2];
                    $outtime = $row[3];
                    $manhours = $row[4];
                }

                $result->close();
            }

            $rX = array(
                "ioFlag" => $ioFlag,
                "dt" => $date,
                "intimepunch" => $intimepunch,
                "intime" => $intime,
                "outtimepunch" => $outtimepunch,
                "outtime" => $outtime,
                "manhours" => $manhours,
                "q" => $query
            );

            return $rX;
        }

        function workTabulation4Date($date) {

            // Get all the timesheets for this day
            $dX = getTimesheetDataForThisTeammate($this->uid, $date, 'singleDay', $this->mysqli);
            $dX_co = count($dX);
            // echo '<br>dX_co: '.count($dX);
            //
            // If empty
            if ($dX_co < 1) {
                return '&nbsp;';
            }

            // Tabulate
            $work = '';
            $totalH = 0;
            $totalM = 0;

            for ($e = 0; $e < $dX_co; $e++) {

                //$work = $work.$dX[$e]["projectname"].'<br>';

                $work = $work . '<table border="0" height="96px" width="100%" cellpadding="0" cellspacing="0" style="font-size:100%">
                            <tr valign="top">
                                <td height="22px">
                                    <span style="font-weight:bold;">' . $dX[$e]["projectname"] . '</span> [ ' . $this->workedFromX[$dX[$e]["worked_from"]] . ' ]
                                </td>
                                <td width="35px" align="right">
                                    ' . $dX[$e]["no_of_hours"] . ':' . $dX[$e]["no_of_min"] . '
                                </td>
                                <td width="75px" align="right">
                                <!-- Buttons -->&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <textarea  style="height:100%;width:100%;border:0px;background:' . $this->rowBackGround . '" readonly>' . $dX[$e]["work"] . '</textarea>
                                </td>
                            </tr>
                            </table>';


                // Total Manhours calculation
                $totalH = $totalH + $dX[$e]["no_of_hours"];
                $totalM = $totalM + $dX[$e]["no_of_min"];
            }

            // Update the total Manhours Property
            $this->tempManhour = timeAdd($totalH, $totalM);

            return $work;
        }


        function getWorkedFromX($virtualFolderPath) {

            $comboWorkedFrom = $virtualFolderPath . '/w3root/studio/tms/hot7e/comboWorkedFrom.txt';

            $file = fopen($comboWorkedFrom, "r");

            if (!$file) {
                die("Fatal :: Could not read Holiday List file.");
            }

            $maxlines = 0; /* maxlines to avoid infinite loop */

            while (!feof($file) && $maxlines < 100) {

                $maxlines++;
                $buffer = fgets($file, 4096);
                //echo $maxlines.". ".$buffer."<br>";
                $tempX = explode(":", $buffer);
                //echo $tempX[0].' | '.$tempX[1].'<br>';
                $wfX[$tempX[0]] = $tempX[1];
            }

            fclose($file);

            $this->workedFromX = $wfX;

        }

    }
    ?>

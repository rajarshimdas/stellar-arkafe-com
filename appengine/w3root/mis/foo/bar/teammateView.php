<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
require_once 'foo/timeAdd.php';
require_once 'foo/dateMysql2Cal.php';
$serialno = 1;

function tabulateProjects($uid, $pid, $pnm, $fdt, $tdt, $serialno, $mysqli)
{
    $query = "select
                *
            from
                view_timesheets
            where
                user_id     = '$uid' and
                project_id  = '$pid' and
                dtmysql     >= '$fdt' and
                dtmysql     <= '$tdt' and
                quality     < 1";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $timeX[] = [
            "dt" => $row["dtmysql"],
            "hr" => $row['no_of_hours'],
            "mn" => $row['no_of_min'],
            "st" => $row['scope'],
            "tk" => $row['sname'],
            "wk" => $row['work']
        ];
    }

    $co_timeX = isset($timeX) ? count($timeX) : 0;
    
    // Generate Row HTML if there is any data
    if ($co_timeX > 0) {

        $GT = generateRow($serialno, $pnm, $fdt, $tdt, $timeX, $co_timeX);
        $serialno++;

        $x = array("srno" => $serialno, "hr" => $GT["hr"], "mn" => $GT["mn"]);

        return $x;
    }
    return null;
}


/*
+-------------------------------------------------------+
| Sub-Function(s)					                    |
+-------------------------------------------------------+
*/

function generateRow($serialno, $pnm, $fdt, $tdt, $timeX, $co_timeX)
{
    if ($co_timeX < 1) {
        return null;
    } else {

        $total_hr = 0;
        $total_mn = 0;

        // Total time
        $hr = 0;
        $mn = 0;
        for ($t = 0; $t < $co_timeX; $t++) {
            $hr = $hr + $timeX[$t]["hr"];
            $mn = $mn + $timeX[$t]["mn"];
        }

        // Display the first row
        $dt = dateMysql2Cal($timeX[0]["dt"]);
        //$wk = tabulateWork($timeX, 0);

        echo '<tr class="dataRow">
            <td class="dataRowCell1" rowspan="' . $co_timeX . '" valign="top">' . $serialno . '</td>
            <td class="dataRowCell2" rowspan="' . $co_timeX . '" valign="top" style="text-align:left;">' . $pnm . '</td>
            <td class="dataRowCell2">' . $timeX[0]["st"] . '</td>
            <td class="dataRowCell2">' . $timeX[0]["tk"] . '</td>
            <td class="dataRowCell2" style="text-align:left;">' . $timeX[0]['wk'] . '</td>
            <td class="dataRowCell2" valign="top">' . $dt . '</td>
            <td class="dataRowCell2" valign="top">' . timeAdd($timeX[0]["hr"], $timeX[0]["mn"])  . '</td>
            <td class="dataRowCell2" rowspan="' . $co_timeX . '" valign="top">' . timeAdd($hr, $mn) . '</td>
        </tr>';

        // First row time
        $total_hr = $total_hr + $timeX[0]["hr"];
        $total_mn = $total_mn + $timeX[0]["mn"];

        for ($e = 1; $e < $co_timeX; $e++) {

            $dt = dateMysql2Cal($timeX[$e]["dt"]);
            $wk = tabulateWork($timeX, $e);
            //echo '<br>+ DT: '.dateMysql2Cal($dt).' | Stage: '.$timeX[$e]["st"].' | Task: '.$timeX[$e]["tk"].' | Work: '.$timeX[$e]["wk"];
            // Tabulate this timesheet
            echo '<tr>
                    <td class="dataRowCell2">' . $timeX[$e]["st"] . '</td>
                    <td class="dataRowCell2">' . $timeX[$e]["tk"] . '</td>
                    <td class="dataRowCell2" style="text-align:left;">' . $timeX[$e]['wk'] . '</td>
                    <td class="dataRowCell2">' . $dt . '</td>
                    <td class="dataRowCell2">' . timeAdd($timeX[$e]["hr"], $timeX[$e]["mn"])  . '</td>
                </tr>';

            $total_hr = $total_hr + $timeX[$e]["hr"];
            $total_mn = $total_mn + $timeX[$e]["mn"];
        }

        $x = array("hr" => $total_hr, "mn" => $total_mn);

        return $x;
    }
}

/*
+-------------------------------------------------------+
| Sub-Function(s)					                    |
+-------------------------------------------------------+
*/
function tabulateWork($timeX, $x)
{

    return '<table class="workTbl" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="45%">' . $timeX[$x]["st"] . '</td>
                    <td width="45%">' . $timeX[$x]["tk"] . '</td>
                    <td width="10%">' . timeAdd($timeX[$x]["hr"], $timeX[$x]["mn"]) . '</td>
                </tr>
                <tr>
                    <td colspan="3">' . $timeX[$x]["wk"] . '</td>
                </tr>
            </table>';
}

<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
require_once 'foo/timeAdd.php';
/*
+-------------------------------------------------------+
| Header						                        |
+-------------------------------------------------------+
*/
function tabulateHeader() {

    echo '<table cellpadding="0" cellspacing="0">
        <tr style="font-weight:bold;font-size: 9pt">
            <td class="headerRowCell1" width="65px">Milestone</td>
            <td class="headerRowCell2" width="250px">Stage</td>
            <td class="headerRowCell2" width="250px">Team Member</td>
            <td class="headerRowCell2" width="80px">Time</td>
            <td class="headerRowCell2" width="80px">Total Time</td>
        </tr>';


}
/*
+-------------------------------------------------------+
| Data Rows						                        |
+-------------------------------------------------------+
*/
function tabulateStage($pid,$stage_id,$stage_nm,$teamX,$fromdt,$todt,$serialno,$mysqli) {

    // Variable Initiation
    $total_hr   = 0;
    $total_mn   = 0;

    // Get timesheet entries for this stage
    $query = "select
                user_id,
                no_of_hours,
                no_of_min
            from
                timesheet
            where
                project_id = $pid and
                projectstage_id = $stage_id and
                dt >= '$fromdt' and
                dt <= '$todt' and
                active = 1 and
                quality = 0";
    //echo "<br>Q: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            //printf ("%s (%s)\n", $row[0], $row[1]);]
            $timeX[] = array("uid" => $row[0], "hr" => $row[1],"mn" => $row[2]);
        }

        $result->close();
    }


    // Loop through the teamX array
    $co_teamX = count($teamX);
    $co_timeX = count($timeX);

    for ($e = 0; $e < $co_teamX; $e++) {

        $teammate_uid   = $teamX[$e]["id"];
        $teammate_name  = $teamX[$e]["fn"];
        $teammate_hr    = 0;
        $teammate_mn    = 0;

        // Loop through the timeX array to sum up this teammate's time
        for ($n = 0; $n < $co_timeX; $n++) {

            if ($teammate_uid === $timeX[$n]["uid"]) {

                $teammate_hr = $teammate_hr + $timeX[$n]["hr"];
                $teammate_mn = $teammate_mn + $timeX[$n]["mn"];

            }

        }

        // Store data if not empty
        if ($teammate_hr > 0 || $teammate_mn > 0) {

            $flag = 1;

            // Add to the total hours
            $total_hr = $total_hr + $teammate_hr;
            $total_mn = $total_mn + $teammate_mn;

            // Store result in tX array
            $tm = timeAdd($teammate_hr,$teammate_mn);
            $tX[] = array("name" => $teammate_name, "tm" => $tm);

        }

    }

    // Generate the Row
    $co_tX = count($tX);

    if ($co_tX > 0) {

        echo '<tr>
                <td class="dataRowCell1" rowspan="'.$co_tX.'">&nbsp;'.$serialno.'</td>
                <td class="dataRowCell2" rowspan="'.$co_tX.'" style="text-align:left">&nbsp;'.$stage_nm.'</td>
                <td class="dataRowCell2" style="height:35px;text-align:left">&nbsp;'.$tX[0]["name"].'</td>
                <td class="dataRowCell2">&nbsp;'.$tX[0]["tm"].'</td>
                <td class="dataRowCell2" rowspan="'.$co_tX.'">&nbsp;'.timeAdd($total_hr,$total_mn).'</td>
            </tr>';

        for ($t = 1; $t < $co_tX; $t++) {
            echo '<tr>
                    <td class="dataRowCell2" style="height:35px;text-align:left">&nbsp;'.$tX[$t]["name"].'</td>
                    <td class="dataRowCell2">&nbsp;'.$tX[$t]["tm"].'</td>
                </tr>';

        }

    }

    // Return Total time
    $x = array("hr" => $total_hr, "mn" => $total_mn);
    return $x;

}

/*
+-------------------------------------------------------+
| Footer						                        |
+-------------------------------------------------------+
*/
function tabulateFooter($GTotal_hr,$GTotal_mn) {
    echo '<tr style="font-size:11pt;font-weight: bold; color: RGB(100,100,100)">
        <td style="height:25px">&nbsp;</td>
        <td colspan="3">&nbsp;Grand Total&nbsp;</td>
        <td style="text-align:center">&nbsp;'.timeAdd($GTotal_hr,$GTotal_mn).'</td>
    </tr>
</table>';
}
?>
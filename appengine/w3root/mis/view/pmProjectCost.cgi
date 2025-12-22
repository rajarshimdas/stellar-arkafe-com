<?php
/*
  +-----------------------------------------------------+
  | Rajarshi Das					|
  +-----------------------------------------------------+
  | Created On: 01-Feb-2012				|
  | Updated On:           				|
  +-----------------------------------------------------+
 */

include 'foo/uid2displayname.php';
include 'foo/dateCal2Mysql.php';
include 'foo/timeAdd.php';

$mysqli = cn1();

// Get User Inputs
$pm_uid = $_GET["tid"];
$fdt = $_GET["fdt4"];
$tdt = $_GET["tdt4"];


// Convert dates to MySQL format
$mysql_fdt = dateCal2Mysql($fdt);
$mysql_tdt = dateCal2Mysql($tdt);

// Title Line
echo 'Project Manager: ' . uid2displayname($pm_uid, $mysqli) . ' (' . $fdt . ' to ' . $tdt . ')';


// Get Project list where this teammate is Project Manager
$query = "select 
            t1.project_id,
            t2.projectname
        from 
            roleinproject as t1,
            projects as t2
        where 
            t1.roles_id = 10 and 
            t1.user_id = $pm_uid and
            t1.project_id = t2.id
        order by
            t2.projectname";
// echo '<br>Q: '.$query;

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $projX[] = array("pid" => $row[0], "pn" => $row[1]);
    }
    $result->close();
}

$projX_co = count($projX);
// echo '<br>Handling No. of Projects: '. $projX_co;
// Table Header
?>
<table class="tabulation" width="800px" border="1">
    <tr class="headerRow">
        <td rowspan="2" width="50px">No</td>
        <td rowspan="2" width="450px">Projectname</td>
        <td colspan="2">Total</td>
    </tr>
    <tr class="headerRow">
        <td width="150px">Manhours</td>
        <td>Accrued Cost</td>
    </tr>

    <?php
// Data Rows
    $srno = 1;

    for ($e = 0; $e < $projX_co; $e++) {

        // Variable Initiation
        $totalH = 0;
        $totalM = 0;
        $totalAccruedCost = 0;

        // Get timesheets for this project
        $query = "select 
                    t1.no_of_hours,
                    t1.no_of_min,
                    t2.userhrgroup_id,
                    t3.defaultrate
                from
                    timesheet as t1,
                    users_a as t2,
                    userhrgroup as t3
                where
                    t1.project_id = " . $projX[$e]["pid"] . " and
                    t1.active = 1 and
                    t1.user_id = t2.user_id and
                    t2.userhrgroup_id = t3.id";

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $thisH = $row[0];
                $thisM = $row[1];
                $hrGroup = $row[2];
                $rate = $row[3];

                $totalH = $totalH + $thisH;
                $totalM = $totalM + $thisM;

                $totalAccruedCost = ($totalAccruedCost + ($thisH * $rate) + (($thisM * $rate) / 60));
            }
            $result->close();
        }


        if ($totalAccruedCost > 0) {
            
            echo '<tr class="dataRow"><td align="center">' . $srno++ . '</td>
                    <td>&nbsp;' . $projX[$e]["pn"] . '</td>
                    <td>&nbsp;' . timeAdd($totalH, $totalM) . '</td>
                    <td>&nbsp;' . $totalAccruedCost . '</td>
            </tr>';
            
        }
    }
    ?>





</table>



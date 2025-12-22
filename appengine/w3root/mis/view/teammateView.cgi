<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
require_once 'foo/bar/teammateView.php';


// Variables
$tid = $_GET['tid'];
$fdt = $_GET['fdt2'];
$tdt = $_GET['tdt2'];

// echo 'tid: ' . $tid . ' fdt: ' . $fdt . ' tdt: ' . $tdt;


// Get Projects array
// require_once 'foo/getProjectArray.php';
// $projX      = getProjectArray($mysqli);
$query = 'select id, projectname, jobcode from projects where id > 15 order by jobcode';

if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_row()) {

        if ($row[0] > 500) {
            // Projects
            $projX[] = array("id" => $row[0], "pn" => $row[2] . " - " . $row[1]);
        } else {
            // Overheads
            $projX[] = array("id" => $row[0], "pn" => $row[1]);
        }
    }
    $result->close();
}
/*
echo "<pre>", var_dump($projX) , "</pre>";
die;
*/
$co_projX = isset($projX) ? count($projX) : 0;
// echo "co_projX: ".$co_projX;




// Data Validation
if (!$tid) {
    die("<br>&nbsp;<br>Error: Teammate was not selected. Click on Home button and try again...");
}
if (!$fdt || $fdt === '-- Select --') {
    die("<br>&nbsp;<br>Error: From Date. Click on Home button and try again...");
}
if (!$tdt) {
    die("<br>&nbsp;<br>Error: To Date. Click on Home button and try again...");
}


// Header
require_once 'foo/uid2displayname.php';
require_once 'foo/dateMysql2Cal.php';

echo '<br>&nbsp;<span style="font-size:125%;font-weight:bold;">' . uid2displayname($tid, $mysqli) . '</span>';
echo '<br>From: ' . dateMysql2Cal($fdt) . ' To: ' . dateMysql2Cal($tdt) . '<br>&nbsp;';

// Table Header
?>
<style>
    table {
        border-collapse: collapse;
    }

    table.tabulation tr td {
        font-size: 1rem;
        line-height: 25px;
    }
    
    table.tabulation tr.dataRow td {
        font-size: 0.9rem;
    }

    table.workTbl tr {
        height: 25px;
    }
    table.workTbl tr td {
        line-height: 20px;
        font-size: 0.9rem;
    }
</style>
<table class="tabulation" cellpadding="0" cellspacing="0" style="width:100%;">
    <tr>
        <td class="headerRowCell" width="35px" style="text-align: center;">No</td>
        <td class="headerRowCell1" width="200px">Project</td>
        <td class="headerRowCell1" width="100px" style="text-align: center;">Scope</td>
        <td class="headerRowCell1" width="100px" style="text-align: center;">Milestone</td>
        <td class="headerRowCell1">Work Description</td>
        <td class="headerRowCell1" width="100px" style="text-align: center;">Date</td>
        <td class="headerRowCell1" width="100px" style="text-align: center;">Manhours</td>
        <td class="headerRowCell1" width="100px" style="text-align: center;">Total MH</td>
    </tr>

    <?php

    // Initiate Variables
    $GTotal_hr  = 0;
    $GTotal_mn  = 0;
    $serialno   = 1;

    // Loop through projX and generate Row HTML
    for ($i = 0; $i < $co_projX; $i++) {

        $pid = $projX[$i]["id"];
        $pnm = $projX[$i]["pn"];

        $GTotalX = tabulateProjects($tid, $pid, $pnm, $fdt, $tdt, $serialno, $mysqli);

        if ($GTotalX) {
            $GTotal_hr  = $GTotal_hr + $GTotalX["hr"];
            $GTotal_mn  = $GTotal_mn + $GTotalX["mn"];
            $serialno   = $GTotalX["srno"];
        }
    }

    // Grand Total
    ?>
    <tr style="height:35px;font-weight: bold">
        <td>&nbsp;</td>
        <td colspan="3">&nbsp;Grand Total</td>
        <td style="text-align: center;"><?= timeAdd($GTotal_hr, $GTotal_mn); ?></td>
    </tr>

</table>
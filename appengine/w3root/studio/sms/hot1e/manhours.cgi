<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 						|
| Updated On: 22-Jun-10					|
+-------------------------------------------------------+
| Manhour Budgetting                                    |
+-------------------------------------------------------+
*/
include 'hot1e/manhoursFn.cgi';
/*
+-------------------------------------------------------+
| Timesheet Summary                                     |
+-------------------------------------------------------+
*/
?>
<table width="1000px" cellspacing="0" cellpadding="0">
    <form action="rajarshi.cgi" method="GET">
        <input type="hidden" name="a" value="t1xmanhours-summary">
        <input type="hidden" name="pid" value="<?php echo $projectid; ?>">
        <tr>
            <td style="background: #e3ffc0; border: 1px solid #969696">
                <table width="100%" cellspacing="0" cellpadding="2" border="0">
                    <tr>
                        <td width="60px" height="80px" align="center">
                            <img src="/da/icons/reports.png">
                        </td>
                        <td width="400px" align="right">
                            Timesheet Summary ::
                        </td>
                        <td width="40px" align="right">
                            From:
                        </td>
                        <td width="125px">
                            <input id="fdt1" name="fdt1" type="text" value="-- Project Starting --" style="width:100%">
                        </td>
                        <td width="20px" align="right">
                            To:
                        </td>
                        <td width="125px">
                            <input id="tdt1" name="tdt1" type="text" value="<?php echo date('d-M-y'); ?>" style="width:100%">
                        </td>
                        <td>
                            <input type="submit" name="go" value="Get" width="50px">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </form>
</table>

<link rel="stylesheet" type="text/css" href="/matchbox/mbox/calendar/styles.css" />
<script type="text/javascript" src="/matchbox/mbox/calendar/classes.js"></script>

<script type="text/javascript">
    var cal1a, cal1b;   // Project Summary
    var cal2a, cal2b;   // Teammate Summary

    window.onload = function () {
        if ($('#fdt1')) {
            cal1a  = new Epoch('epoch_popup','popup',document.getElementById('fdt1'));
            cal1b  = new Epoch('epoch_popup','popup',document.getElementById('tdt1'));
        }
        if ($('#fdt2')) {
            cal2a  = new Epoch('epoch_popup','popup',document.getElementById('fdt2'));
            cal2b  = new Epoch('epoch_popup','popup',document.getElementById('tdt2'));
        }
    };
</script>

<?php
/*
+-------------------------------------------------------+
| Manhour/Fee Estimation Vs. Actual                     |
+-------------------------------------------------------+
*/
?>

<table width="1000px" cellspacing="0" cellpadding="0">
    <form action="rajarshi.cgi" method="GET">
        <input type="hidden" name="a" value="t1xmanhours-show">
        <tr>
            <td style="background: #d7d9c9; border-bottom: 1px solid #969696; border-right: 1px solid #969696; border-left: 1px solid #969696">
                <table width="100%" cellspacing="0" cellpadding="2" border="0">
                    <tr>
                        <td width="60px" height="80px" align="center">
                            <img src="/da/icons/target.png">
                        </td>
                        <td width="550px" align="right">
                            Manhours/Fee Estimate Vs. Actual :: Estimate Version
                        </td>
                        <td width="220px">
                            <?php selectFeeEstimateVer($projectid, $mysqli); ?>
                        </td>
                        <td align="left">
                            <input type="submit" name="go" value="Get">
                        </td>
                    </tr>
                </table>             

            </td>
        </tr>
    </form>

</table>


<?php

function selectFeeEstimateVer($pid, $mysqli) {

    $query = "select version, dtime from timeestimateversion where project_id = $pid order by dtime desc";

    echo '<select name="ver" style="width:100%">';

    if ($result = $mysqli->query($query)) {
        $row_cnt = $result->num_rows;
        while ($row = $result->fetch_row()) {
            $verX[] = array("no" => $row_cnt, "ver" => $row[0], "dtime" => $row[1]);
            $row_cnt--;
        }
        $result->close();
    }

    $co = count($verX);
    if ($co < 1) {
        echo '<option value="X">-- Select --</option>';
    } else {
        for ($i = 0; $i < $co; $i++) {
            echo '<option value="'.$verX[$i]["ver"].'">Ver '.$verX[$i]["no"].' ('.$verX[$i]["dtime"].')</option>';
        }
    }

    echo '</select>';

}
?>

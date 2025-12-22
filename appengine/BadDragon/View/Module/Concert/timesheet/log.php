<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 21-May-2009       			            |
| Updated On: 13-Apr-2011				                |
+-------------------------------------------------------+
| Timesheet :: Form and Table                         	|
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| 13-Apr-11 Saturday On/Off                             |
|           satmode 0 - Holiday                         |
|           satmode 1 - Working 1,3,5th Sats            |
|           satmode n - Nth combination                 |
+-------------------------------------------------------+
$path = $pathStudio.'/da/w3root/'.$rootFolderName;
echo 'Path: '.$path;
*/

if (isset($_GET["no"])) {
    $display_no_of_days = $_GET["no"];
} else {
    $display_no_of_days = 45;
}

$displaySelectProject = 0;
/*
+-------------------------------------------------------+
| Get Cache Data                                        |
+-------------------------------------------------------+
*/

require_once $w3etc . '/foo/pid2pname.php';
require_once $w3etc . '/foo/sid2stagename.php';

// echo $lockdt;

/*
+-------------------------------------------------------+
| Table Header                                          |
+-------------------------------------------------------+
*/
if (!$display_no_of_days) $display_no_of_days = $_GET["no"];

if (!$display_no_of_days) {

    $display_no_of_days = 21;
    $noOfDaysToDate     = 0;    // Compatibility to mgnt/moo/tmReport.cgi
    $noOfDaysFromDate   = 21;   // Compatibility to mgnt/moo/tmReport.cgi
    $no_of_days_flag    = 0;
} else {

    $noOfDaysToDate     = 0;
    $noOfDaysFromDate   = $display_no_of_days;
    $no_of_days_flag    = 1;
}

/*
+-------------------------------------------------------+
| Tabulate Timesheet                                    |
+-------------------------------------------------------+
*/
$HideIOdata = "N";
$this_userid = $userid;

# include $w3root . '/studio/tms/hot7e/tabulate_a.cgi';     // Abhikalpan Format
require_once __DIR__ . '/log-tabulate.cgi';

function showEditButtons(
    $dX,
    $i,
    $this_userid,
    $userid,
    $showAddEditDeleteButtons,
    $display_no_of_days,
    $no_of_days_flag,
    $base_url
) {

    if ($this_userid == $userid && $dX[$i]["approved"] < 1 && $showAddEditDeleteButtons > 0 && $dX[$i]["project_id"] > 10) {

        echo '<table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <img class="fa5button" onclick="javascript:editTs(' . $dX[$i]["timesheet_id"] . ',' . $dX[$i]["no_of_hours"] . ',' . $dX[$i]["no_of_min"] . ',' . $dX[$i]["percent"] . ',' . $dX[$i]["project_id"] . ')" src="' . $base_url . 'da/fa5/edit.png" title="Edit Timesheet">
                    </td>
                    <td>
                        <img class="fa5button" onclick="javascript:deleteTs(' . $dX[$i]["timesheet_id"] . ')" src="' . $base_url . 'da/fa5/delete.png" title="Delete Timesheet">
                    </td>
                </tr>
            </table>';
    }
}
?>
<style>
    table {
        width: 100%;
    }
</style>
<script>
    function deleteTs(tsId) {

        console.log('deleteTs :: tsid: ' + tsId)

        const tc = document.getElementById("tc_" + tsId)
        let m = '<table><tr><td><input type="button" value="Delete" onclick="javascript:deleteTsNow(' + tsId + ')"></td></tr></table>'

        tc.innerHTML = m
    }

    function deleteTsNow(tsId) {
        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-tsDelete")
        formData.append("tsId", tsId)

        bdPostData(apiUrl, formData).then((response) => {
            // console.log(response[0]);
            if (response[0] == "T") {
                window.location.reload()
            } else {
                dxAlertBox("Timesheet Delete Error", response[1])
            }
        });
    }

    function editTs(
        tsId, // Timesheet Id
        h, // Hours
        m, // Min
        p, // Percent
        pid // Project Id
    ) {
        console.log('editTs :: tsid: ' + tsId)

        let percent = 0
        let t1 = '<table><tr><td>'

        if (pid < 100) {
            // Overhead
            t1 += '<input type="hidden" id="ep_' + tsId + '" value="0">'
        } else {
            // Project
            t1 += '<input type="number" id="ep_' + tsId + '" value="' + p + '">'
        }

        t1 += '</td></tr></table>'
        let t2 = '<table><tr><td style="width:50%;"><input type="number" id="eh_' + tsId + '" value="' + h + '"></td><td><input type="number" id="em_' + tsId + '" value="' + m + '"></td></tr></table>'

        let t3 = '<table><tr><td><input type="button" value="Edit" onclick="javascript:editTsNow(' + tsId + ')"></td></tr></table>'

        document.getElementById("tp_" + tsId).innerHTML = t1
        document.getElementById("th_" + tsId).innerHTML = t2
        document.getElementById("tc_" + tsId).innerHTML = t3

    }

    function editTsNow(tsId) {
        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-tsEdit")
        formData.append("tsId", tsId)

        formData.append("p", document.getElementById("ep_"+tsId).value)
        formData.append("h", document.getElementById("eh_"+tsId).value)
        formData.append("m", document.getElementById("em_"+tsId).value)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response[0]);
            if (response[0] == "T") {
                window.location.reload()
            } else {
                dxAlertBox("Timesheet Edit Error", response[1])
            }
        });
    }

</script>
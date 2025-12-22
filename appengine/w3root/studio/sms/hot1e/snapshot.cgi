<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-Jun-2012       			            |
| Updated On: 27-Oct-2023                               |
+-------------------------------------------------------+
| Moved the snapshot.cgi and snapshotFn.cgi to w3include|
| so that I can use the same code to generate the       |
| project report (monthly) through CRON daemon.         |
| This now remains a WRAPPER function only.             |
+-------------------------------------------------------+
*/
require_once BD . 'Controller/Common.php';

// Get functions from w3etc
include $w3etc . '/foo/sms/projectDashboard/snapshotFn.cgi';

$stageX = bdGetProjectStageArray($mysqli);
// var_dump($stageX);

$scopeX = bdGetProjectScopeArray($mysqli);
// var_dump($scopeX);

// Generates the tabulation
include $w3etc . '/foo/sms/projectDashboard/snapshot.cgi';

?>

<script type="text/javascript">
    function contractSignOffFlag() {
        document.getElementById("dxSignOff").showModal()
    }

    function dxSignOffclose() {
        document.getElementById("dxSignOff").close()
    }

    function dxSignOffSave() {

        var actionProg = 't1snapshot/contractSignOff.cgi';
        var sdt = document.getElementById("sdt").value;

        var dataString = 'a=' + actionProg + '&sdt=' + sdt;
        // console.log('function: dxSignOffSave?' + dataString);

        if (sdt) {
            /* Save */
            $.ajax({
                type: "GET",
                url: "gearbox.cgi",
                data: dataString,
                cache: false,
                success: function(rx) {

                    document.getElementById("signOffRow").innerHTML = rx;
                    dxSignOffclose();
                }
            });
        }

    }
</script>
<style>
    dialog table tr:first-child {
        font-weight: bold;
        font-size: 1.2em;
        line-height: 25px;
    }

    dialog {
        border-spacing: 6px;
        border: 0px;
    }

    dialog tr td {
        border: 0px solid red;
    }

    dialog button {
        width: 100%;
        line-height: 20px;
    }
</style>
<dialog id="dxSignOff">
    <table>
        <tr>
            <td>Signoff Date</td>
            <td>
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" alt="close" onclick="dxSignOffclose()">
            </td>
        </tr>
        <tr>
            <td>
                <input type="date" id="sdt" max="<?= date("Y-m-d") ?>">
            </td>
            <td>
                <button onclick="dxSignOffSave()">Save</button>
            </td>
        </tr>
    </table>
</dialog>
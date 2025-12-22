<?php
/*
  +-----------------------------------------------------+
  | Rajarshi Das					                    |
  +-----------------------------------------------------+
  | Created On: 10-May-2013				                |
  | Updated On:                                         |
  +-----------------------------------------------------+
 */

include 'foo/timesheets/timesheetLockDt.php';
include 'foo/dateMysql2Cal.php';

$mysqliSuperUser = cn2();

$tslock = new timesheetLockDt(0, $holidayListFile, $mysqliSuperUser);
$lockdt = $tslock->getTimesheetLockDt();
$lockdtCal = dateMysql2Cal($lockdt);
$activeRowX = $tslock->getActiveOverrideRecords();
?>

<input type="hidden" id="adminUID" value="<?php echo $uid; ?>">
<table class="formTBL" width="100%" style="background:#ccf255" cellspacing="0px" border="0">
    <tr>
        <td colspan="3" style="background:RGB(150,150,150);color:white;font-weight:bold;font-size:110%">
            Timesheet Lock Release
        </td>
    </tr>
    <tr>
        <td style="width: 150px" align="right">Teammate:</td>
        <td style="width: 350px">
            <?php
            include('boo/admin/comboUsers.php');
            comboUsers("-- Select --", 0, $mysqli, 'comboUsers')
            ?>
        </td>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td align="right">
            Allow till date:
        </td>
        <td>
            <input id="calendar" class="calendar" name="dt" type="text" value="<?php echo $lockdtCal; ?>">
        </td>
        <td>&nbsp;</td>
    </tr>
    <!-- Can have a Reason. all coding is done - just unhide this control.
        <tr>
            <td align="right">
                Reason:
            </td>
            <td>                
                <textarea id="reason" type="textarea" style="width: 100%"></textarea>
            </td>
            <td>&nbsp;</td>
        </tr>
    -->
    <input type="hidden" id="reason" value="-">
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="go" value="Allow" style="width:150px" onclick="javascript:submitFormData();"></td>
    </tr>
    <tr>
        <td id="errorLogs" colspan="3" align="center" style="color:red;">&nbsp;</td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td colspan="3" style="background: white;">
            <table id="myTable" class="tablulation" style="width: 100%; font-size: 85%" border="0" cellspacing="0" cellpadding="0">
                <tr class="headerRow">
                    <td class="headerRowCell1" style="width:400px">
                        &nbsp;Active Records :: Teammate
                    </td>
                    <td class="headerRowCell2" style="width: 120px;border-right: 0px; text-align: center">
                        Allow Till Date
                    </td>
                    <td class="headerRowCell2">
                        &nbsp;
                    </td>
                </tr>
                <?php
                for ($i = 0; $i < count($activeRowX); $i++) {

                    echo '<tr class="dataRow" id="tmId' . $activeRowX[$i]["tm_uid"] . '" style="background-color:white;">
                                <td class="dataRowCell1">
                                    &nbsp;' . $activeRowX[$i]["tm"] . '
                                </td>
                                <td class="dataRowCell2" style="border-right: 0px; text-align: center;">
                                    ' . dateMysql2Cal($activeRowX[$i]["lockdt"]) . '
                                </td>
                                <td class="dataRowCell2" style="text-align: right">
                                    <img 
                                        src="/da/icons/delete.png" 
                                        alt="Delete" 
                                        onClick="javascript:deleteRecord(' . $activeRowX[$i]["tm_uid"] . ');" 
                                        style="cursor:pointer"
                                        title="Delete ' . $activeRowX[$i]["tm"] . '\'s Entry."
                                    >
                                </td>
                            </tr>';
                }
                ?>
            </table>
        </td>
    </tr>
</table>


<script type="text/javascript">
    var cal;
    $(document).ready(function() {
        // put all your jQuery goodness in here.
        cal = new Epoch('epoch_popup', 'popup', document.getElementById('calendar'));
    });

    // Add a new record
    function submitFormData() {

        var actionProg = 'timesheet-lock';
        var userId = $('#comboUsers').val();
        var lockdtCal = $('#calendar').val();
        // var reason = $('#reason').val();
        var reason = '<!-- NA -->';

        // console.log('userId: ' + userId + ' | lockdtCal: '+ lockdtCal);

        var request = $.ajax({
            url: "engine.cgi",
            type: "GET",
            data: {
                a: actionProg,
                userId: userId,
                lockdtCal: lockdtCal,
                reason: reason
            },
            dataType: "html"
        });

        request.done(function(msg) {

            console.log(msg);

            //$("#log").html(msg);
            if (msg != 'F') {
                // Successful
                //console.log('ajax sucess: ' + msg);
                $('#myTable').append(msg);

            } else {
                // Failed
                $('#errorLogs').html('Could not save the data. Try again...');
            }

        });

        request.fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });

    }

    // Delete an existing Record
    function deleteRecord(tmId) {
        console.log("delete: " + tmId);

        var actionProg = 'timesheet-lock-del';

        var request = $.ajax({
            url: "engine.cgi",
            type: "GET",
            data: {
                a: actionProg,
                tmId: tmId
            },
            dataType: "html"
        });

        request.done(function(msg) {
            //$("#log").html(msg);
            if (msg != 'T') {
                // Failed
                $('#errorLogs').html('Failed to save data. Try again...');
            } else {
                // Successful
                //console.log('ajax sucess: ' + msg);                        
                $('#tmId' + tmId).remove();
            }
        });

        request.fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
        });

    }
</script>
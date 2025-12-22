<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Jul-2012				                |
| Updated On:                                           |
+-------------------------------------------------------+
| */ include 'hot1e/milestoneFn.cgi'; /*                |
+-------------------------------------------------------+
*/
?>

<style type="text/css">
    .activeStage {
        height: 25px;
        border-bottom: 1px solid #969696;
        border-right: 1px solid #969696;
        border-top:1px solid #969696;
        padding-left: 2px;
        padding-right: 2px;
    }
    .activeStage:hover {
        background: #fecd61;
        cursor: pointer;
    }
</style>

<table class="tabulation" width="100%">
    <tr class="headerRow">
        <td class="headerRowCell1" width="80px" style="text-align: center;height: 40px;">
            No
        </td>
        <td class="headerRowCell2" width="100px" style="text-align: center;">
            Shortcode
        </td>
        <td class="headerRowCell2">
            Milestone
        </td>
        <td class="headerRowCell2" width="200px" colspan="3" style="text-align: center;">
            Target Date
        </td>        
        <td class="headerRowCell2" width="80px" style="text-align: center;">
            Active
        </td>
    </tr>
    <!-- Metadata -->
    <?php

    $stageX     = getStageArray($mysqli);
    $co_stageX  = count($stageX);

    $currentStageX  = getCurrentProjectStage ($project_id, $mysqli);
    $currentStageId = $currentStageX["stageId"];
    $currentStageNo = $currentStageX["stageNo"];

    echo '
        <input type="hidden" id="currentStageId" value="'.$currentStageId.'">
        <input type="hidden" id="currentStageNo" value="'.$currentStageNo.'">
        ';

    tabulateStageRows($project_id, $stageX, $co_stageX, $mysqli);
    ?>
</table>

<!-- Javascript Stuff -->
<link type="text/css" rel="stylesheet"  href="/matchbox/mbox/calendar/styles.css" />
<script type="text/javascript" src="/matchbox/mbox/calendar/classes.js"></script>

<script type="text/javascript">
<?php
for ($e = 0; $e < $co_stageX; $e++) {
    if ($e < 1) {
        echo 'var cal'.$stageX[$e]["id"];
    } else {
        echo ', cal'.$stageX[$e]["id"];
    }
}
?>;
    window.onload = function () {
<?php
for ($x = 0; $x < $co_stageX; $x++) {
    echo 'cal'.$stageX[$x]["id"].' = new Epoch("calendar","popup",document.getElementById("C'.$stageX[$x]["id"].'")); ';
}
?>

    };

    function saveTargetDate(stageId){

        var targetdt = $('#C' + stageId).val();
        // console.log ('saveTargetDate:: stageId: ' + stageId + '; targetdt: ' + targetdt);

        /* AJAX */
        var actionProg = 't1snapshot/targetDateSave.cgi';
        var dataString = 'a=' + actionProg + '&stageId=' + stageId + '&tdt=' + targetdt;

        $.ajax({
            type: "GET",
            url: "gearbox.cgi",
            data: dataString,
            cache: false,
            success: function(rx){
                if (rx < 1) {
                    $("#editBtn" + stageId).attr("src", "/da/icons/delete.png");
                    $("#edit" + stageId).val("delete");
                } else {
                    alert('Error: Could not save data...');
                }
            }
        });        

    }

    function showSaveButton(stageId) {

        var saveButtonId = "'#saveCal" + stageId + "'";
        // console.log ('showSaveButton...' + saveButtonId);

        $("#edit" + stageId).val("save");
        $("#editBtn" + stageId).attr("src", "/da/icons/save-red.png");
        // $("#saveCal" + stageId).css({"visibility": "visible", "cursor": "pointer"});

    }

    function editTargetDate (stageId) {

        var editBtn = $("#edit" + stageId).val();
        // console.log ('editBtn: ' + editBtn);

        if (editBtn === 'delete'){
            deleteTargetDate(stageId);
        } else {
            saveTargetDate(stageId);
        }

    }

    function deleteTargetDate (stageId) {
        // console.log ('deleteTargetDate...');

        /* AJAX */
        var actionProg = 't1snapshot/targetDateDelete.cgi';
        var dataString = 'a=' + actionProg + '&stageId=' + stageId;

        $.ajax({
            type: "GET",
            url: "gearbox.cgi",
            data: dataString,
            cache: false,
            success: function(rx){
                if (rx < 1) {
                    $("#editBtn" + stageId).attr("src", "/da/icons/delete.png");
                    $("#edit" + stageId).val("delete");
                    $("#C" + stageId).val("");
                } else {
                    alert('Error: Could not save data...');
                }
            }
        });
    }

    function setCurrentStage(stageId, stageNo) {

        //console.log('stageId: ' + stageId + ' + stageNo: ' + stageNo);
        //var currentStage = $('#currentStageId').val();

        /* AJAX */
        var actionProg = 't1snapshot/setCurrentStage.cgi';
        var dataString = 'a=' + actionProg + '&stageId=' + stageId;

        $.ajax({
            type: "GET",
            url: "gearbox.cgi",
            data: dataString,
            cache: false,
            success: function(rx){
                rx = rx.trim();
                if (rx < 1) {
                    // Reset current stage values
                    //$("#cs" + currentStage).attr("src", "/da/null.png");
                    $("#currentStageId").val(stageId);
                    $("#currentStageNo").val(stageNo);
                    // Show new Current Stage
            //$("#cs" + stageId).attr("src", "/da/tick.png");
                    // Update all other ticks
                    updateStageTickMarks(stageId,stageNo);
                } else {
                    alert('Error: Could not save data...');
                }
            }
        });

    }

    function updateStageTickMarks (stageId, stageNo) {

        stageId = stageId + 0;
        stageNo = stageNo + 0;

        for (var i = 0; i < 25; i++) {

            if (i < stageNo) {
                // Grey Ticks
                $("#cs" + i).attr("src", "/da/tick-80x40px-greyscale.png");
            } else {
                // Empty
                $("#cs" + i).attr("src", "/da/null-80x40px.png");
            }

        }

        // Current stage - Green Tick
        $("#cs" + stageNo).attr("src", "/da/tick-80x40px.png");

    }

</script>


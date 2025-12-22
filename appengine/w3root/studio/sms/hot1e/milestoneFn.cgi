<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 03-Jul-2012				|
| Updated On: 03-Jan-2013                               |
+-------------------------------------------------------+
*/
require_once 'foo/getStageArray.php';
require_once 'foo/sms/projectSchedule.php';


function tabulateStageRows($pid, $stageX, $co_stageX, $mysqli) {

    for ($i = 0; $i < $co_stageX; $i++) {

        $this_stageId   = $stageX[$i]["id"] + 0;
        $this_stageNo   = $stageX[$i]["stageno"] + 0;
        
        $targetdt       = getStageTargetDate ($pid, $this_stageId, $mysqli);
        $currentStageX  = getCurrentProjectStage ($pid, $mysqli);
        $currentStageId = $currentStageX["stageId"] + 0;
        $currentStageNo = $currentStageX["stageNo"] + 0;
                    
        if ($currentStageId === $this_stageId){
            $currentStageHTML = '<img id="cs'.$this_stageNo.'" src="/da/tick-80x40px.png" alt="Current" onclick="javascript:setCurrentStage('.$this_stageId.','.$this_stageNo.')">';
        } else {
            $currentStageHTML = '<img id="cs'.$this_stageNo.'" src="/da/null-80x40px.png" alt="Current" onclick="javascript:setCurrentStage('.$this_stageId.','.$this_stageNo.')">';
        }

        // Grey ticks
        if ($this_stageNo < $currentStageNo){
            $currentStageHTML = '<img id="cs'.$this_stageNo.'" src="/da/tick-80x40px-greyscale.png" alt="Current" onclick="javascript:setCurrentStage('.$this_stageId.','.$this_stageNo.')">';
        }

        echo '<tr class="dataRow">
                <td class="dataRowCell1" style="text-align:center;height:40px">
                    '.$stageX[$i]["stageno"].'
                </td>
                <td class="dataRowCell1" style="text-align:center;height:40px">
                    '.$stageX[$i]["sname"].'
                </td>                
                <td class="dataRowCell2">
                    '.$stageX[$i]["stage"].'
                </td>
                <td class="dataRowCell2" style="border-right:0px;width:100px;">
                    <input id="C'.$this_stageId.'" name="targetdt" type="text" value="'.$targetdt.'" style="width:100%;border:0;text-align:center;cursor: auto;">
                </td>
                <td class="dataRowCell2" style="border-right:0px;text-align:center;width:40px;">
                    <input type="image" src="/da/icons/calander.png" value="pick" onclick="cal'.$this_stageId.'.toggle();showSaveButton('.$this_stageId.');"/>
                </td>
                <td class="dataRowCell2" style="text-align:center;width:40px;">
                    <input type="hidden" id="edit'.$this_stageId.'" value="delete">
                    <img id="editBtn'.$this_stageId.'" src="/da/icons/delete.png" alt="delete" style="cursor: pointer;" onclick="editTargetDate('.$this_stageId.');">                    
                </td>
                <td id="active'.$this_stageId.'" class="activeStage" style="text-align:center;padding:0px;">
                    '.$currentStageHTML.'
                </td>
            </tr>';

    }

}

?>

<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-Jun-2012       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
?>
<style>
	.tabulation tr { height: 30px; }
</style>

<table class="tabulation" cellpadding="0" cellspacing="0" style="width:100%;">

    <tr class="dataRow">
        <td width="130px" class="headerRowCell1">&nbsp;Project Manager</td>
        <td class="headerRowCell2" style="height:30px;padding-left: 10px;">
            <?php $x = getProjectManager($projectid, $mysqli); echo $x["fname"]; ?>
        </td>
    </tr>

    <tr class="dataRow">
        <td class="dataRowCell1">&nbsp;Contract Sign-off</td>
        <td class="dataRowCell2" style="height: 30px; padding-left: 10px;">
            <?php signOffStatus($projectid, $role_id, $mysqli); ?>
        </td>
    </tr>

    <tr class="dataRow">
        <td class="dataRowCell1">&nbsp;Current Milestone</td>
        <td class="dataRowCell2" style="height:30px;padding-left: 10px;">
            <?php
            // w3etc/foo/timesheets/projectView.php
            $X = activeProjectStage ($projectid, $mysqli);
            echo $X["stage"];
            ?>
        </td>
    </tr>

    <tr class="dataRow">
        <td class="dataRowCell1">&nbsp;Deliverables</td>
        <td class="dataRowCell2" style="padding:0px;">
            <?php deliverableList($projectid, $stageX, $mysqli); ?>
        </td>
    </tr>

    <tr class="dataRow">
        <td class="dataRowCell1">&nbsp;Manhours</td>
        <td class="dataRowCell2" style="padding:0px;" >
            <?php
            # manhoursStatusShort($projectid, $stageX, $role_id, $mysqli);
            manhoursStatusWithScope($projectid, $scopeX, $stageX, $mysqli);
            ?>
        </td>
    </tr>
   

    
</table>

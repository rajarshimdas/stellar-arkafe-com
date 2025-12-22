<?php  /* Studio Management System Module
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 30-Jun-2012				                |
+-------------------------------------------------------+
*/
//$mysqli = cn1();
include 'hot2e/edit-getItemList.cgi';

// Cache Stage Scheduled Target Dates
require 'foo/sms/projectSchedule.php';
$stageNoTdt = getStageNoTdtArray($pid, $mysqli);
// print_r($stageNoTdt);

?>
<!-- 6 Containers -->
<table class="tabulation" style="width: 100%;" cellpadding="0" cellspacing="0" border="0">
    <!-- Filter -->
    <tr style="background: #E8E9FF;">
        <td style="width:75%;text-align: center; height: 40px;">
            List:
            <select id="filterBK" name="filterBK" style="width:250px;">
                <?php
                echo "<option value='AllPackages'>- Package/All -";

                $query = "select blockno, blockname from blocks where project_id = $projectid and active = 1";
                if ($result = $mysqli->query($query)) {
                    while ($row = $result->fetch_row()) {
                        echo "<option value='$row[0]'>$row[0] - $row[1]</option>";
                    }
                    $result->close();
                }
                ?>
            </select>
            <select id="filterDC" name="filterDC" style="width:250px;">
                <?php
                if ($dc && $dc !== "- Scope/All -") echo "<option>$dc</option>";
                echo "<option value='AllDiscipline'>- Scope/All -</option>";
                $query = "select disciplinecode, discipline from discipline where active > 0 order by id";
                if ($result = $mysqli->query($query)) {
                    while ($row = $result->fetch_row()) {
                        echo "<option value='$row[0]'>$row[0] - $row[1]</option>";
                    }
                    $result->close();
                }
                ?>
            </select>
            <input type="submit" name="go" value="Filter" onclick="javascript:getFilteredList();">
        </td>
        <td style="width:35%" style="text-align:center;">
            Total Items: <span id="totalItemCount" style="font-weight: bold"></span>
        </td>
    </tr>

    <tr>
        <td>
            <!-- Item List Header -->
            <table class="tabulation" style="width:100%">
                <tr class="headerRow">
                    <td class="headerRowCell1" width="100px" style="padding-left:10px; height:35px;">Itemcode</td>
                    <td class="headerRowCell2" width="520px">Title</td>
                    <td class="headerRowCell2" width="110px" style="text-align:center">Milestone</td>
                    <td class="headerRowCell2" width="100px" style="text-align:center; border-right: 0px">Targetdt</td>
                    <td class="headerRowCell2">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td id="itemCode" style="font-size: 110%; text-align: center; font-weight: bold;">
            &nbsp;
        </td>
    </tr>


    <tr>
        <td id="itemList" height="350px">
            <!-- Item List Rows -->
            <div style="width:100%; height: 100%; overflow: auto; border-bottom: 1px solid #969696;">
                <?php
                //echo "pid: $project_id";
                // Generate the Drawing List rows
                getItemList($pid, $stageNoTdt, $mysqli);
                ?>
            </div>
        </td>
        <td id="editItemProperties" valign="top" align="center">

            <!-- Edit Item Properties Dialogue Box -->
            <table
                id="editDialogueBox" class="tabulation" width="100%" border="0"
                style="visibility: hidden; text-align: left; vertical-align: bottom"
                cellpadding="4px">
                <tr>
                    <td style="height:30px;vertical-align: bottom">Title:</td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="itemTitle" style="width:100%">
                    </td>
                </tr>
                <tr>
                    <td style="height:30px;vertical-align: bottom">Milestone:</td>
                </tr>
                <tr>
                    <td>
                        <select id="stageComboBox" style="width:100%">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="height:30px;vertical-align: bottom">New Target Date:</td>
                </tr>
                <tr>
                    <td>
                        <input id="itemTdt" name="targetdt" type="date" style="width: 100%">
                    </td>
                </tr>

                <tr>
                    <td id="itemSubmit" style="text-align: center; vertical-align: bottom; height: 50px">
                        <!-- Item Cache -->
                        <input type="hidden" id="rowNo">
                        <input type="hidden" id="itemId">
                        <input type="hidden" id="itemCode">
                        <input type="hidden" id="itemStageNo">
                        <input type="hidden" id="oldTargetDt">
                        <!-- Buttons -->
                        <input type="submit" id="itemUpdate" style="width: 80px;" value="Update" onclick="javascript:buttonUpdate();">
                        <input type="submit" id="itemCancel" style="width: 80px;" value="Cancel" onclick="javascript:buttonCancel();">
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

<!-- Stage Array -->
<script type="text/javascript">
    var stageList = [
        <?php
        require_once 'foo/getStageArray.php';
        $stageX = getStageArray($mysqli);
        /* caveat: dwglist table stores stageno instead of stage_id */
        foreach ($stageX as $s) {
            echo "['" . $s['sname'] . "', '" . $s['stage'] . "', '" . $s['stageno'] . "'], ";
        }
        ?>

    ];

    var stageNoX = [];
    <?php
    foreach ($stageX as $s) {
        echo "stageNoX[" . $s['stageno'] . "] = ['" . $s['sname'] . "', '" . $s['stage'] . "']; ";
    }
    ?>
</script>
<?php
require_once __DIR__ . "/edit-js.php";

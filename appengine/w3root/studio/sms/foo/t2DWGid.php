<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 10-Dec-2007				|
| Updated On: 17-Oct-2012 (Purged to what is required)  |
+-------------------------------------------------------+
| CLASS: DWGid						|
+-------------------------------------------------------+
| METHODS:	 					|
|	1.	GetDWGDetails				|
|	2.	DWGListTableHeader			|
|	3.	DWGListTableRow				|
+-------------------------------------------------------+
| Notes & Memos (Summary of Drawing List Tab):		|
| a.							|
| DWGid()	Class displays all drawing lists.	|
| b.							$row['r0targetdt'])|
| drawing()	Class for creation of drawings list.	|
| c.							|
| Editing and deleting happens through the EditPanel	|
| and their related action programms. Since these are	|
| not required to duplicated multiple times, they are	|
| not included in the DWGid() or drawing() class.	|
| d.							|
| Read along with the revised dwglist table metadata.	|
+-------------------------------------------------------+
| Update 22-Feb-08:					|
| Major Update was required since the dwglist table was |
| updated to accomodate 'commitdt'. This was used as an |
| opportunity to clean up and perform a near complete	|
| re-write of this class.				|
| a. 	Added display on/off toggles for all columns.	|
| b. 	Major updation/re-write for faster execution.	|
|		MySQL Query Optimized for performance.	|
| c. 	ShowEdit button code removed. Editing to be	|
|           done exclusively using the new Edit Panel.  |
| d.	NEW Commitdt to be displayed for DM/TLs only.	|
| e.	NEW Properties: ProjID and RoleID.		|
| f.	Schematic Date - show proper error message when	|
|		schematic dates are not avialable.	|
| g.	Table column widths - bug fix.			|
| h.	Advanced colour formatting of dates.		|
| i.	Committed Date automated display on/off based	|
|	user's RoleID. Independent of programm calling	|
|	for these dates.                                |
+-------------------------------------------------------+
*/
require_once 'foo/sms/projectSchedule.php';
require_once BD . '/Controller/Common.php';

class DWGid
{

    /*
    +-------------------------------------------------------+
    | Properties                                            |
    +-------------------------------------------------------+
    */
    // Project and User
    var $ProjID;
    var $RoleID;

    // Drawing Specific
    var $DWGid;
    var $SheetNo;
    var $CurrentRevNo;
    var $Title;
    var $Remark;
    var $Stage;
    var $StageSN;
    // Schematic Target Dates
    var $atargetdt;
    var $anewtargetdt;
    var $aissuedflag;
    // R0 Target Dates
    var $r0targetdt;
    var $newr0targetdt;
    var $r0issuedflag;
    var $r0issuedt;
    // Last issued info
    var $lastissuedrevno;
    var $lastissueddate;
    var $active;
    var $stageclosed;
    var $actionby;
    var $commitdt;

    // Tabulation
    var $ShowNo;
    var $ShowRevNo;
    var $ShowStage;
    var $ShowSchematic;
    var $ShowCommitdt;
    var $ShowR0Date;
    var $ShowGFC;
    var $ShowLastIssued;
    var $ShowActionBy;
    var $ShowRemark;
    var $TableWidth;

    /*
    +-------------------------------------------------------+
    | Constructor                                           |
    +-------------------------------------------------------+
    */
    function __construct($ProjID, $RoleID)
    {

        // Project and User Properties
        $this->ProjID = $ProjID;
        $this->RoleID = $RoleID;
    }

    /*
    +-------------------------------------------------------+
    | Methods                                               |
    +-------------------------------------------------------+
    | GetDWGDetails                                         |
    +-------------------------------------------------------+
    */
    function GetDWGDetails($DWGid, $mysqli)
    {

        $this->DWGid = $DWGid;

        // Get all information regarding this dwg from dwglist table
        /*
        $query = "select
                    project_id,
                    CONCAT(dwgidentity,'-',disciplinecode,'-',unit) as sheetno,
                    currentrevno as revno,
                    title,
                    remark,
                    newstage as stage,
                    DATE_FORMAT(r0targetdt,'%d-%b-%y') as r0targetdt,
                    DATE_FORMAT(newr0targetdt,'%d-%b-%y') as newr0targetdt,
                    r0issuedflag,
                    DATE_FORMAT(r0issuedt,'%d-%b-%y') as r0issuedt,
                    lastissuedrevno,
                    DATE_FORMAT(lastissueddate,'%d-%b-%y') as lastissueddate,
                    active,
                    stageclosed as aissuedflag,
                    priority as actionby,
                    DATE_FORMAT(dtime, '%d-%b-%y') as commitdt,
                    part
                from
                    dwglist
                where
                    id = $DWGid and
                    project_id = $this->ProjID";

        // echo "<br>SQL: $query;";
        */

        $query = "select * from `view_drawing_list` where `id` = '$DWGid'";
        // die($query);

        if ($result = $mysqli->query($query)) {

            $row = $result->fetch_assoc();
            // die(var_dump($row));

            $this->ProjID = $row['project_id'];
            $this->SheetNo = $row['sheetno'];

            // Target Date
            if ($row['newr0targetdt'] == '0000-00-00') {

                $stageNoTdt = getStageNoTdtArray($this->ProjID, $mysqli);
                //die(var_dump($stageNoTdt));

                $newr0targetdt = $stageNoTdt[$row['stage']];
            } else {
                $newr0targetdt = $row['newr0targetdt'];
            }

            $r0targetdt = $row['r0targetdt'];
            if ($newr0targetdt > $r0targetdt) {
                $r0targetdt = $newr0targetdt;
            }

            $this->CurrentRevNo = $row['revno'];
            $this->Title = $row['title'];
            $this->Remark = $row['remark'];
            $this->Stage = $row['stage'];
            $this->StageSN = $row['stage_sn'];

            // R0
            $this->r0targetdt = bdDateMysql2Cal($r0targetdt);
            $this->newr0targetdt = bdDateMysql2Cal($newr0targetdt);
            $this->r0issuedflag = $row['r0issuedflag'];
            $this->r0issuedt = bdDateMysql2Cal($row['r0issuedt']);
            // Last issued
            $this->lastissuedrevno = $row['lastissuedrevno'];
            $this->lastissueddate = bdDateMysql2Cal($row['lastissueddate']);
            // Misc
            $this->active = $row['active'];
            $this->stageclosed = $row['aissuedflag'];
            $this->actionby = $row['actionby'];
            $this->commitdt = $row['commitdt'];

            $result->close();
        } else {
            echo "Error: $mysqli->error";
        }

        /* Result Set */
        $returnX = array(
            "ProjID" => $this->ProjID,
            "sheetno" => $this->SheetNo,
            "revno" => $this->CurrentRevNo,
            "title" => $this->Title,
            "remark" => $this->Remark,
            "stage" => $this->Stage,
            "stage_sn" => $this->StageSN,
            "r0targetdt" => $this->r0targetdt,
            "newr0targetdt" => $this->newr0targetdt,
            "r0issuedflag" => $this->r0issuedflag,
            "r0issuedt" => $this->r0issuedt,
            "lastissuedrevno" => $this->lastissuedrevno,
            "lastissueddate" => $this->lastissueddate,
            "active" => $this->active,
            "stageclosed" => $this->stageclosed,
            "actionby" => $this->actionby,
            "commitdt" => $this->commitdt
        );
        // die(var_dump($returnX));

        return $returnX;
    }

    /*
+-------------------------------------------------------+
|  DWGListTableHeader					|
+-------------------------------------------------------+
    */
    function DWGListTableHeader(
        $ShowNo,
        $ShowRevNo,
        $ShowStage,
        $ShowSchematic,
        $ShowCommitdt,
        $ShowR0Date,
        $ShowGFC,
        $ShowLastIssued,
        $ShowActionBy,
        $ShowRemark,
        $TableWidth
    ) {

        // Save the Tabulation Properties
        $this->ShowNo         = $ShowNo;
        $this->ShowRevNo     = $ShowRevNo;
        $this->ShowStage     = $ShowStage;
        $this->ShowSchematic     = $ShowSchematic;   // Legacy - Not used any more
        $this->ShowCommitdt     = $ShowCommitdt;    // Legacy - Not used any more
        $this->ShowR0Date     = $ShowR0Date;      // Not used here
        $this->ShowGFC         = $ShowGFC;
        $this->ShowLastIssued     = $ShowLastIssued;
        $this->ShowActionBy     = $ShowActionBy;
        $this->ShowRemark     = $ShowRemark;
        $this->TableWidth     = $TableWidth;

        // Total Width for all fixed width columns
        /* Drawing Number column */
        $width = 90;
        if ($ShowNo > 0)         $width = $width + 35;
        if ($ShowRevNo > 0)         $width = $width + 40;
        if ($ShowStage > 0)        $width = $width + 40;
        if ($ShowSchematic > 0)     $width = $width + 160;
        if ($ShowCommitdt > 0 && $this->RoleID < 45)
            /* User is a TL/DM */        $width = $width + 80;
        if ($ShowR0Date > 0)         $width = $width + 80;
        if ($ShowGFC > 0)         $width = $width + 160;
        if ($ShowLastIssued > 0)    $width = $width + 120;
        if ($ShowActionBy > 0)         $width = $width + 80;
        if ($ShowLastIssued > 0)     $width = $width + 80;
        //echo "<br>Width: $width<br>";

        //$TitleColumnWidth = $TableWidth - $width;
        //echo "<br>Title Column Width: $TitleColumnWidth";

        // Header - Top Row
        echo '<table class="tabulation" style="text-align: center;width:100%" border="0" cellpadding="1" cellspacing="0">
                <tr class="headerRow" height=30px; style="background:#FFF6F4;font-weight:bold;">';

        if ($ShowNo > 0) echo "<td class='headerRowCell1' style='width:35px;text-align:center;' rowspan='2'>No</td>";
        echo '<td class="headerRowCell2" style="width: 150px;text-align:left;" rowspan="2">Sheet No</td>';
        echo '<td class="headerRowCell2" style="width: 300px; text-align:left;" rowspan="2">&nbsp;Title</td>';
        if ($ShowStage > 0) echo '<td class="headerRowCell2" style="width:60px;text-align:center;" rowspan="2">Stage</td>';
        if ($ShowSchematic > 0) echo "<td class='headerRowCell2' style='width:160px;text-align:center;' colspan='2'>Schematic</td>";
        if ($ShowCommitdt > 0 && $this->RoleID < 45) echo '<td class="headerRowCell2" style="width:80px;text-align:center;" rowspan="2">Commit<br>Date</td>';
        if ($ShowRevNo > 0) echo '<td class="headerRowCell2" style="width:60px;text-align:center;" rowspan="2">Current<br>Rev No</td>';
        if ($ShowR0Date > 0) echo '<td class="headerRowCell2" style="width:80px;text-align:center;" rowspan="2">GFC(R0)<br>Release<br>Date</td>';
        if ($ShowGFC > 0) echo "<td class='headerRowCell2' style='width:160px;text-align:center;' colspan='2'>GFC(R0)</td>";
        if ($ShowLastIssued > 0) echo "<td class='headerRowCell2' style='width:80px;text-align:center;' rowspan='2'>Last<br>Issued</td>";
        if ($ShowActionBy > 0) echo "<td class='headerRowCell2' style='width:80px;text-align:center;' rowspan='2'>Action<br>By</td>";
        if ($ShowRemark > 0) echo '<td class="headerRowCell2" style="width:80px;text-align:left;" rowspan="2">&nbsp;Remark</td>';

        echo '</tr>';

        // Header - Bottom Row
        echo '<tr height=30px; style="background:#FFF6F4;font-weight:bold;">';

        if ($ShowSchematic > 0) echo "<td class='headerRowCell3' width='80px'>Target</td><td class='headerRowCell3' width='80px'><span style='color:RED;'>Revised</span>&nbsp;/<br><span style='color:GREEN;'>Completed</span></td>";
        if ($ShowGFC > 0) echo "<td class='headerRowCell3' width='80px'>Target</td><td class='headerRowCell3' width='80px'>Completed</td>";

        echo '</tr>';

        /* Table Header: Remember to close the table </table> */
    }

    /*
    +-------------------------------------------------------+
    | DWGListTableRow                                       |
    +-------------------------------------------------------+
    */
    function DWGListTableRow(
        $ShowNo,
        $dwgX,
        $atag = "Yes",
        $returnpath = "t2xsummary-dwg"
    ) {

        // Get the current UNIX Timstamp
        $now = strtotime("now");

        // Formatting SheetNo for 'a' tag
        $sheetno = $dwgX["sheetno"];
        if ($atag === "Yes") {
            $sheetno = "<td class='dataRowCell2a' align='left'><a href='project.cgi?a=$returnpath&id=$this->DWGid'>$sheetno</a></td>";
        } else {
            $sheetno = "<td class='dataRowCell2' align='left'>" . $sheetno . "</td>";
        }

        // Last issued Revision Number
        if ($dwgX["lastissuedrevno"] === "-") {
            $lastissuedrevno = "&nbsp;";
            $lastissueddate = '&nbsp;';
        } else {
            $lastissuedrevno = $dwgX["lastissuedrevno"];
            $lastissueddate    = $dwgX["lastissueddate"];
        }

        // Action By
        if ($dwgX["actionby"] === "-") {
            $actionby = "&nbsp";
        } else {
            $actionby = $dwgX["actionby"];
        }

        // R0Date
        if ($dwgX["r0issuedflag"] > 0) {
            $R0Date = $dwgX["newr0targetdt"];
        } else {
            $R0Date = '&nbsp;';
        }

        // Remark
        if (!$dwgX["remark"] || $dwgX["remark"] === "-") {
            $remark = '&nbsp';
        } else {
            $remark = $dwgX["remark"];
        }

        /*--------------------------------------------------------------------------+
            |	Colour Formatting of - Schematic Target Dates                           |
            +--------------------------------------------------------------------------*/
        /*
            if (!$dwgX["atargetdt"]) {

                // Stage dates not yet set
                $atargetdt = 'X';

            } else {

                if ($dwgX["stageclosed"] > 0 || $dwgX["r0issuedflag"] > 0) {

                    // Schematic drawing is issued
                    $atargetdt = '<span style="color: grey;">'.$dwgX["atargetdt"].'</span>';

                } else {

                    // Schematic drawing is not issued
                    $d = strtotime($dwgX["atargetdt"]);
                    $x = $now - $d;

                    if ($x > 0 && !$dwgX["anewtargetdt"]) {

                        // date is missed and no revised target date assigned
                        $atargetdt = "<span style='color:grey;'>".$dwgX["atargetdt"]."</span><span style='color:red;font-weight:bold'>&nbsp;?</span>";

                    } else {

                        // future date
                        $atargetdt = $dwgX["atargetdt"];

                    }

                }

            }
        */
        /*--------------------------------------------------------------------------+
            |	Colour Formatting of - Schematic Completed Target Dates                 |
            +--------------------------------------------------------------------------*/
        /*
            if (!$dwgX["r0issuedt"]) {

                // No Revised Schmatic Target date
                $anewtargetdt = "&nbsp;";

            } else {

                // Revised Target date available
                if ($dwgX["r0issuedflag"] > 0) {

                    // GFC is released
                    $anewtargetdt = "<span style='color:grey;'>".$dwgX["anewtargetdt"]."</span>";

                } else {

                    // schmatic not released
                    if ($dwgX["stageclosed"] < 1) {

                        // Stage is active
                        $d = strtotime($dwgX["anewtargetdt"]);
                        $x = $now - $d;

                        if ($x > 0) {

                            // Passed Date
                            $anewtargetdt = "<span style='color:red;font-weight:bold;'>".$dwgX["anewtargetdt"]."&nbsp;?</span>";

                        } else {

                            // Furture date
                            $anewtargetdt = "<span style='color:red;'>".$dwgX["anewtargetdt"]."</span>";

                        }

                    } else {

                        // Stage is closed
                        $anewtargetdt = "<span style='color:green;'>".$dwgX["anewtargetdt"]."</span>";

                    }

                }

            }
        */
        /*--------------------------------------------------------------------------+
            |	Colour Formatting of - Committed Dates                                  |
            +--------------------------------------------------------------------------*/
        /*
            if ($dwgX["commitdt"]) {

                if ($dwgX["r0issuedflag"] > 0) {

                    // GFC is released
                    $commitdt = "<span style='color:grey;'>".$dwgX["commitdt"]."</span>";

                } else {

                    // GFC is not released
                    $commitdt = $dwgX["commitdt"];
                }

            } else {

                $commitdt = '&nbsp;';
            }
        */
        /*--------------------------------------------------------------------------+
            |	Colour Formatting of - GFC Target Dates                                 |
            +--------------------------------------------------------------------------*/
        $d = strtotime($dwgX["r0targetdt"]);
        $x = $now - $d; /* Positive value indicates that the date is a past date */

        if (!$dwgX["r0targetdt"]) {

            // R0TargetDate not set...
            $r0targetdt = '&nbsp;';
        } else {

            // R0TargetDate is set...
            if ($dwgX["r0issuedflag"] > 0) {

                // Drawing has been released
                $r0targetdt = "<span style='color:grey;'>" . $dwgX["r0targetdt"] . "</span>";
            } else {

                // Drawing is not released
                if ($x > 0) {
                    // Date is a passed date
                    $r0targetdt = '<span style="color:red;">' . $dwgX["r0targetdt"] . '</span>';
                } else {
                    // Date is a future date
                    $r0targetdt = $dwgX["r0targetdt"];
                }
            }
        }
        /*--------------------------------------------------------------------------+
            |	Colour Formatting of - GFC Revised/Completed Target Dates 	|
            +--------------------------------------------------------------------------*/
        if ($dwgX["r0issuedflag"] > 0) {

            // R0 is already issued
            $r0issuedt = "<span style='color:green;'>" . $this->r0issuedt . "</span>";
        } else {

            // R0 not issued
            $r0issuedt = "&nbsp;";
        }

        /*--------------------------------------------------------------------------+
            |	Generate Row for this Drawing                                           |
            +--------------------------------------------------------------------------*/
        echo "<tr class='dataRow'>";

        if ($this->ShowNo > 0) echo "<td class='dataRowCell1'>" . $ShowNo . "</td>";
        echo $sheetno;
        echo "<td class='dataRowCell2' style='text-align:left'>" . $dwgX["title"] . "</td>";
        if ($this->ShowStage > 0) echo '<td class="dataRowCell2">' . $dwgX["stage_sn"] . '</td>';
        // if ($this->ShowSchematic > 0) echo "<td class='dataRowCell2'>" . $atargetdt . "</td><td>" . $anewtargetdt . "</td>";
        // if ($this->ShowCommitdt > 0 && $this->RoleID < 45) echo '<td class="dataRowCell2">' . $commitdt . '</td>';
        if ($this->ShowRevNo > 0) echo "<td class='dataRowCell2'>" . $dwgX["revno"] . "</td>";
        if ($this->ShowR0Date > 0) echo "<td class='dataRowCell2'>" . $R0Date . "</td>";
        if ($this->ShowGFC > 0) echo "<td class='dataRowCell2'>" . $r0targetdt . "</td><td class='dataRowCell2'>" . $r0issuedt . "</td>";
        if ($this->ShowLastIssued > 0) echo "<td class='dataRowCell2'>" . $lastissueddate . "</td>";
        if ($this->ShowActionBy > 0) echo "<td class='dataRowCell2' style='text-align:center;'>" . $actionby . "</td>";
        if ($this->ShowRemark > 0) echo "<td class='dataRowCell2' style='text-align:left'>" . $remark . "</td>";

        echo "</tr>
                ";
    }

    /*
+-------------------------------------------------------+
| Close class DWGid	*/
}/*				|
+-------------------------------------------------------+
*/

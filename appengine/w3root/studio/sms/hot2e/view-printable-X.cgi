<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: Mar-07					                |
| Updated On: 03-Mar-2008 				                |
+-------------------------------------------------------+
| Drawing List > View Form				                |
+-------------------------------------------------------+
| Common Function to display drawing list for:		    |
|	1. Printable-Disciplinecode			                |
|	2. Printable-Blockwise				                |
+-------------------------------------------------------+
*/

/* Function to Generate the Table rows */

function xtable($ProjID, $bk, $bn, $dc, $dn, $mode, $fx, $from, $to, $c1, $stage, $gfc, $c2, $c3, $c4, $mysqli)
{

    //echo "<br>Project id: $ProjID<br>Project Name: $ProjNM<br>bk: $bk<br>dc:$dc<br>ck1: $c1 <br>ck2: $c2 <br>ck3: $c3<br>Fx: $fx<br>From: $from To: $to<br>Stage: $stage<br>GFC: $gfc<br>From2: $from2 To2: $to2";
    // Check box value parsing
    if ($c1 === "on")
        $c1 = 1;
    else
        $c1 = 0;
    if ($c2 === "on")
        $c2 = 1;
    else
        $c2 = 0;
    if ($c3 === "on")
        $c3 = 1;
    else
        $c3 = 0;
    if ($c4 === "on")
        $c4 = 1;
    else
        $c4 = 0;

    // Radio Buttons
    if ($stage === "a")
        $stage_a = 1;
    else
        $stage_a = 0;
    if ($stage === "b")
        $stage_b = 1;
    else
        $stage_b = 0;

    // Radio Buttons
    if ($gfc === "a")
        $gfc_a = 1;
    else
        $gfc_a = 0;
    if ($gfc === "b")
        $gfc_b = 1;
    else
        $gfc_b = 0;

    // Construct Query - basic filtering
    $x = "project_id = $ProjID and dwgidentity = '$bk' and disciplinecode = '$dc' and active = 1";


    // Construct Query - Filtered Selection of data to be displayed
    if ($fx === "b")
        $x = "r0issuedflag = 1 and $x";
    if ($fx === "c")
        $x = "r0issuedflag = 0 and $x";


    // Construct Query - Date Range for GFC releases
    if ($from && $to && $fx === "b")
        $x = "lastissueddate >= '$from' and lastissueddate <= '$to' and $x order by lastissueddate DESC";


    // Construct Query - final query
    // Update 04-Sep-2013
    // Added sheetno for sort by sheetno
    $sql = "select 
                id,
                CONCAT(dwgidentity,'-',disciplinecode,'-',unit,part) as sheetno                
            from 
                dwglist 
            where 
                $x
            order by
                sheetno";
    // echo "<br>sql: $sql;";


    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_row()) {

            // Array of DWGids
            $DWGidX[] = array("id" => $row[0]);
        }

        $result->close();
    } else
        echo "Error: $mysqli->error";

    // We got all the DWGid list, use the DWGid() class to get the details..
    $co = empty($DWGidX) ? 0 : count($DWGidX);
    if ($co > 0) {

        /* Title */
        if ($mode === "block")
            echo "<br>&nbsp;<br><span style='font-weight:bold;'>$dc - $dn</span>";
        if ($mode === "discipline")
            echo "<br>&nbsp;<br><span style='font-weight:bold;'>$bk - $bn</span>";

        /* Display the Header Row */
        $roleid = 100;
        $dx = new DWGid($ProjID, $roleid);
        $dx->DWGListTableHeader(1, $c1, $stage_a, $stage_b, $c2, $gfc_a, $gfc_b, $c3, 0, $c4, 850);
        $no = 1;
        /* Display the Data Rows */
        for ($i = 0; $i < count($DWGidX); $i++) {

            $DWGid = $DWGidX[$i]["id"];
            $a = $dx->GetDWGDetails($DWGid, $mysqli);
            $dx->DWGListTableRow($no, $a);
            $no++;
        }

        /* Close the table */
        echo '</table>';
    }
}

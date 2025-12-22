<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 09-Jul-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
require_once 'foo/dateMysql2Cal.php';

function getItemList($pid, $stageNoTdt, $mysqli)
{

    /*
    +-------------------------------------------------------+
    | Get Item List from database                           |
    +-------------------------------------------------------+
    */
    $query = 'select
                t1.id,
                concat (t1.dwgidentity, "-", t1.disciplinecode, "-", t1.unit) as itemcode,
                concat ("-", t1.part),
                t1.title,                
                t1.newr0targetdt as targetdt,
                t1.dwgidentity as package,
                t1.disciplinecode,
                t1.newstage as stageno,
                t2.name as stage,
                t1.r0issuedflag,
                t2.sname
            from
                dwglist as t1,
                projectstage as t2
            where
                t1.project_id = ' . $pid . ' and
                t1.active = 1 and
                t1.newstage = t2.stageno
            order by
                itemcode';

    //echo "Q: ".$query.'<br>';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            if (trim($row[2]) !== "-") {
                $itemcode = $row[1] . $row[2]; // Concat Part
            } else {
                $itemcode = $row[1];
            }

            $listX[] = array(

                "id"                => $row[0],
                "itemcode"          => $itemcode,
                "title"             => $row[3],
                "targetdt"          => $row[4],
                "block"             => $row[5],
                "disciplinecode"    => $row[6],
                "stageNo"           => $row[7],
                "stage"             => $row[8],
                "r0flag"            => $row[9],
                "sname"             => $row[10],

            );
        }

        $result->close();
    }
    //die(var_dump($listX));
    // Rows
    $co_listX = empty($listX) ? 0 : count($listX);
    echo '<input type="hidden" id="co_listX" value="' . $co_listX . '">';

    /*
    +-------------------------------------------------------+
    | Generate Rows                                         |
    +-------------------------------------------------------+
    */
    // Table
    echo '<table class="tabulation" style="width:100%" cellspacing="0" cellpadding="0">';

    for ($e = 0; $e < $co_listX; $e++) {

        $thisStageNo = $listX[$e]["stageNo"];

        // Target Date
        if ($listX[$e]["targetdt"] !== '0000-00-00') {

            // Instance Target Date
            $tdt = $listX[$e]["targetdt"];
        } else {

            // Try to get the stage target Date - If available
            $tdt = empty($stageNoTdt[$thisStageNo]) ? "&nbsp;" : $stageNoTdt[$thisStageNo];
        }

        // R0 issued status
        if ($listX[$e]["r0flag"] < 1) {
            $deleteButton = '<img src="/da/icons/delete.png" alt="Delete" style="cursor: pointer;" onclick="javascript:itemDelete(' . $e . ',' . $listX[$e]["id"] . ');">';
        } else {
            $deleteButton = '&nbsp;';
        }

        /*require_once BD . '/Controller/Common.php';
        $tdtISO = '';
        if (strlen($tdt) > 5) {
            $tdtISO = bdDateCal2Mysql($tdt);
        }
            */
        // echo '<br>thisStageId: ' . $thisStageNo . ' tdt: ' . $tdt. ' tdtISO: ' . $tdtISO;
        $tdtCal = ($tdt != "&nbsp;") ? bdDateMysql2Cal($tdt) : "&nbsp;";

        echo '
            <!-- Metadata -->
            <input type="hidden"    id="rowId-' . $e . '"                   value="' . $listX[$e]["id"] . '">
            <input type="hidden"    id="ic-' . $listX[$e]["id"] . '"        value="' . $listX[$e]["itemcode"] . '">
            <input type="hidden"    id="bk-' . $listX[$e]["id"] . '"        value="' . $listX[$e]["block"] . '">
            <input type="hidden"    id="dc-' . $listX[$e]["id"] . '"        value="' . $listX[$e]["disciplinecode"] . '">
            <input type="hidden"    id="tx-' . $listX[$e]["id"] . '"        value="' . $listX[$e]["title"] . '">
            <input type="hidden"    id="tdt-' . $listX[$e]["id"] . '"       value="' . $tdt . '">
            <input type="hidden"    id="sNo-' . $listX[$e]["id"] . '"       value="' . $listX[$e]["stageNo"] . '">
            <input type="hidden"    id="sNm-' . $listX[$e]["id"] . '"       value="' . $listX[$e]["sname"] . ' - ' . $listX[$e]["stage"] . '">
            <input type="hidden"    id="active-' . $listX[$e]["id"] . '"    value="1">
            <!-- Row -->
            <tr id="tr-' . $e . '" class="dataRow" style="font-size:90%">
                <td class="dataRowCell1" width="100px" style="padding-left: 10px; height: 40px;">
                    ' . $listX[$e]["itemcode"] . '
                </td>
                <td id="cellTitle-' . $e . '" class="dataRowCell2" width="520px">
                    ' . $listX[$e]["title"] . '
                </td>
                <td id="cellStageNo-' . $e . '" class="dataRowCell2" width="110px" style="text-align: center;">
                    ' . $listX[$e]["sname"] . '
                </td>
                <td id="cellTargetdt-' . $e . '" class="dataRowCell2" width="100px" style="text-align:center; border-right: 0px;">
                    ' . $tdtCal . '
                </td>
                <td class="dataRowCell2" style="padding-left:10px">
                    <img src="/da/icons/edit.png" alt="Edit" style="cursor: pointer;" onclick="javascript:itemEdit(' . $e . ',' . $listX[$e]["id"] . ');">&nbsp;
                    ' . $deleteButton . '
                </td>
            </tr>';
    }

    // Close Table
    echo '</table>';
}

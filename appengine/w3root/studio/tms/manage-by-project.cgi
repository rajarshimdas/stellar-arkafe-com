<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 02-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

$pid = $_GET["pid"];

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <title>SMS :: Timesheets manage</title>

    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">
    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>

    <!-- <script type='text/javascript' src='/matchbox/jquery/jquery.js'></script> -->
    <!-- manage.(js|css) load after jquery is loaded -->
    <script type='text/javascript' src='moo/manage.js'></script>
    <link type='text/css' rel="stylesheet" href="moo/manage.css">
</head>

<body>
    <div id="windowBox">

        <div style="width:1000px">
            <?php include '../foo/header.php'; ?>
        </div>

        <!-- Dialog Box -->
        <?php
        
        // Project Name
        require 'foo/pid2pname.php';
        $pname = pid2pname($pid, $mysqli);
        ?>




        <style>
            .tabulation tr td {
                text-align: left;
            }

            .tabulation tr td:nth-child(1),
            .tabulation tr td:nth-child(4),
            .tabulation tr td:nth-child(6),
            .tabulation tr td:nth-child(7),
            .tabulation tr td:nth-child(8),
            .tabulation tr td:nth-child(9) {
                text-align: center;
            }

            .tabulation thead {
                font-size: 1.25em;
                text-align: center;
                background-color: #447ca9;
                color: white;
                border: 1px solid #447ca9;
            }

            .manageTable {
                text-align: center;
                vertical-align: middle;
                padding: 0;
                margin: 0;
            }

            .manageTable img {
                height: 24px;
                border: 2px solid white;
            }

            .manageTableHover {
                background-color: #c8dcdd;
            }

            .manageTableHover img {
                border: 2px solid #c8dcdd;
            }

            .manageTableHover textarea {
                background-color: #c8dcdd;
            }

            textarea {
                width: calc(100% - 4px);
                height: calc(100% - 4px);
            }
        </style>



        <dialog id="dx">
            <table width="100%">
                <tr style="height: 40px; vertical-align: top">
                    <td>
                        <div id="dxName"></div>
                    </td>
                    <td width="30px">
                        <button onclick="dxClose()">X</button>
                    </td>
                </tr>
            </table>
            <?php
            include "tm/modal_dx.php";
            ?>
            <table width="100%" style="border:0px;font-size:95%;border-spacing:4px">

                <tr>
                    <td style="width:140px;text-align:right;">Remark:</td>
                    <td colspan="2">
                        <input type="hidden" id="dxTsid">
                        <input type="text" name="dxRemark" id="dxRemark" style="width: 100%;line-height:22px">
                    </td>
               </tr>
               <tr>
                <td></td>
                <td style="width:225px;">
                    <button onclick="dxAddRemarkButton()" style="width: 100%;">Add Remark</button>
                </td>
                <td style="width:225px;">
                    <button onclick="dxClose()" style="width: 100%;">Cancel</button>
                </td>
               </tr>
            </table>
        </dialog>



        <table class="tabulation" width="1000px" cellpadding="0" cellspacing="0">
            <thead class="headerRow" style="height: 35px">
                <tr>
                    <td colspan="9">
                        <?= $pname ?>
                    </td>
                </tr>
            </thead>
            <tr style="font-weight: bold;">
                <td class="headerRowCell1" width="40px">SNo.</td>
                <td class="headerRowCell2" width="100px">Date</td>
                <td class="headerRowCell2" width="100px">Name</td>
                <td class="headerRowCell2" width="70px">Milestone</td>
                <td class="headerRowCell2" width="450px">Work Description</td>
                <td class="headerRowCell2" width="70px">Hours<br>Worked</td>
                <td class="headerRowCell2" style="text-align: center;" colspan="3">Approval</td>
            </tr>

            <tbody class="manageTable" style="height: 35px">

                <?php
                /*
                +-------------------------------------------------------+
                | Data Rows                                             |
                +-------------------------------------------------------+
                */
                $query = 'select
                                DATE_FORMAT(t1.dt,"%a, %d-%b-%y") as dt,
                                t2.fullname,
                                t1.work,
                                t1.no_of_hours,
                                t1.no_of_min,
                                t4.name as stage,
                                t5.name as task,
                                t1.id,
                                t4.sname
                            from
                                timesheet as t1,
                                users as t2,
                                projectstage as t4,
                                timesheettasks as t5
                            where
                                /* Criteria */
                                t1.project_id = ' . $pid . ' and
                                t1.approved = 0 and
                                t1.active = 1 and
                                /* Table Relation */
                                t1.user_id = t2.id and
                                t1.projectstage_id = t4.id and
                                t1.task_id = t5.id and
                                t1.pm_review_flag < 1
                            order by
                                t1.id DESC';

                if ($result = $mysqli->query($query)) {

                    while ($row = $result->fetch_row()) {

                        $tsX[] = array(
                            "date"      => $row[0],
                            "fullname"  => $row[1],
                            "work"      => $row[2],
                            "hour"      => $row[3],
                            "min"       => $row[4],
                            "stage"     => $row[5],
                            "task"      => $row[6],
                            "tsid"      => $row[7],
                            "sname"     => $row[8]
                        );
                    }
                    $result->close();
                }

                $co = count($tsX);
                for ($i = 0; $i < $co; $i++) {

                    // Sr No
                    $no = $co - $i;

                    /* Work Tabulation */
                    $wk = '<textarea style="width:100%;border:0" readonly>' . $tsX[$i]["work"] . '</textarea>';

                    // Generate the row
                    $tmRowId = 'tm-row-' . $tsX[$i]["tsid"];
                    echo '<tr id="' . $tmRowId . '" valign="top" onmouseover="fnMouseOver(' . "'" . $tmRowId . "'" . ')" onmouseout="fnMouseOut(' . "'" . $tmRowId . "'" . ')" style="height:60px;">
                                <td class="dataRowCell1">' . $no . '</td>
                                <td class="dataRowCell2">' . $tsX[$i]["date"] . '</td>
                                <td class="dataRowCell2" style="font-weight:bold;">' . $tsX[$i]["fullname"] . '</td>
                                <td class="dataRowCell2">' . $tsX[$i]["sname"] . '</td>
                                <td class="dataRowCell2">' . $wk . '</td>
                                <td class="dataRowCell2">' . $tsX[$i]["hour"] . ':' . $tsX[$i]["min"] . '</td>
                                <td class="manageTable dataRowCell2" style="border-right: 1px solid white;">
                                    <img src="/da/icons/ok.32px.png" title="Accept Timesheet" onclick="javascript:buttonAccept(' . $tsX[$i]["tsid"] . ');">
                                </td>
                                <td class="manageTable dataRowCell2" style="border-right: 1px solid white;">
                                    <img src="/da/icons/no.32px.png" title="Reject Timesheet" onclick="javascript:buttonReject(' . $tsX[$i]["tsid"] . ');">
                                </td>
                                <td class="manageTable dataRowCell2">
                                        <img src="/da/icons/edit.32px.png" title="Add Remark" onclick="javascript:buttonAddRemark(' . $tsX[$i]["tsid"] . ');">
                                </td>
                            </tr>';
                }

                ?>
            </tbody>
        </table>
        <br>
    </div>
</body>

</html>
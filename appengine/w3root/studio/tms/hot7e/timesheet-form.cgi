<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 18-Aug-2009       			            |
| Updated On: 22-Feb-2012                               |
|               28-Jan-2024                             |
+-------------------------------------------------------+
| Timesheet :: Timesheet Form for Adding and Editing    |  
+-------------------------------------------------------+
*/
require_once BD . "Controller/Projects/Projects.php";



/*
+-------------------------------------------------------+
| Stage/Milestone                                       |
+-------------------------------------------------------+
*/
$projectstageX = bdGetProjectStageArray($mysqli);
$projectstageXcount = count($projectstageX);

/*
+-------------------------------------------------------+
| Projectscope                                          |
+-------------------------------------------------------+
*/
$scopeX = bdGetProjectScopeArray($mysqli);

$pstage = json_encode($projectstageX);
$pscope = json_encode(bdGetProjectScopeArrayX($mysqli));
$pscopemap = json_encode(bdProjectScopeMap($mysqli));
?>
<!-- Markups -->
<?= '<script type="application/json" id="pstage">' . $pstage . '</script>' ?>

<?= '<script type="application/json" id="pscope">' . $pscope . '</script>' ?>

<?= '<script type="application/json" id="pscopemap">' . $pscopemap . '</script>' ?>

<style>
    #formTS tr td:first-child {
        text-align: right;
    }

    #formTS tr td {
        border: 0px solid red;
    }

    #formTS select,
    #formTS input,
    #formTS textarea {
        width: 100%;
        box-sizing: border-box;
    }

    .statusBox {
        color: red;
        font-weight: bold;
        text-align: left;
        padding: 10px;
        border: 1px solid red;
        border-radius: 5px;
    }
</style>


<form name="tmForm" action="execute.cgi" method="POST" onsubmit="return formValidation();">

    <input type="hidden" name="a" id="a" value="<?php echo $Form_hidden_a; ?>">
    <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
    <input type="hidden" name="uid" <?php echo "value='$userid'"; ?>>
    <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
    <!-- Task static -->
    <input type="hidden" id="tk" name="tk" value="1">

    <?php
    /*
    +-------------------------------------------------------+
    | Timesheet Lock                                        |
    +-------------------------------------------------------+
    */

    // Load class timesheetLockDt
    require_once 'foo/timesheets/timesheetLockDt.php';
    require_once 'foo/dateMysql2UnixTS.php';
    require_once 'foo/dateMysql2Cal.php';
    // Instantiate
    $ts = new timesheetLockDt($uid, $holidayListFile, cn2());
    // GetTimesheetLockDt
    $lockdt = $ts->getTimesheetLockDt();

    $lockdtTS = dateMysql2UnixTS($lockdt);
    $lockdtCal = dateMysql2Cal($lockdt);

    echo '<input type="hidden" id="lockdtTS" name="lockdtTS" value="' . $lockdtTS . '">';
    echo '<input type="hidden" id="lockdtCal" name="lockdtCal" value="' . $lockdtCal . '">';
    ?>

    <!-- Edit Form -->
    <?php if ($tsid) echo '<input type="hidden" name="tsid" value="' . $tsid . '">'; ?>


    <table id="formTS" width="100%" style="border:0px;font-size:95%;background:<?php echo $FormBackgroundColor; ?>;border-spacing:4px;padding:8px 0px;">

        <!-- Date & Time -->
        <tr valign="top">
            <td style="width:400px;">Date & Time:</td>
            <td style="width:150px;">
                <?php
                if ($form_date)
                    $calendar = $form_date;
                else
                    $calendar = date('d-M-y');
                ?>
                <input id="dt" name="dt" type="text" value="<?php echo $calendar; ?>">
                <input type="hidden" name="lx" value="1">
            </td>

            <td style="width:150px;">
                <select id="hr" name="hr">
                    <?php if ($form_hour) echo '<option value="' . $form_hour . '">' . $form_hour . ' hour(s)'; ?>
                    <option value="0">0 hour</option>
                    <option value="1">1 hour</option>
                    <?php
                    for ($i = 2; $i <= 10; $i++) {
                        echo "<option value='$i'>$i hours</option>";
                    }
                    ?>
                </select>
            </td>

            <td style="width:150px;">
                <select id="mn" name="mn">
                    <?php if ($form_min) echo '<option value="' . $form_min . '">' . $form_min . ' min'; ?>
                    <option value="0">0 min
                    <option value="15">15 min
                    <option value="30">30 min
                    <option value="45">45 min
                </select>
            </td>

            <!-- Status box -->
            <td rowspan="7">
                <div id='statusBox'>
                    <?php
                    // Echo Error Message 
                    if (isset($_GET["mc"])) {
                        echo '<div class="statusBox">Server Validation Error<br>' . $_GET["mc"] . '</div>';
                    }
                    ?>
                </div>
            </td>

        </tr>


        <!-- Project -->
        <tr valign="top">
            <td>Project:</td>
            <td colspan="3">
                <select id="pj" name="pj" onchange="javascript:onProjectSelect();">
                    <?php
                    if ($form_projectid > 0)
                        echo "<option value='$form_projectid'>$form_projectname</option>";
                    else
                        echo "<option value='0'>-- Select Project --</option>";
                    /*
                    +-------------------------------------------------------+
                    | Overheads                                             |
                    +-------------------------------------------------------+
                    | Overhead ids - 11 to 99                               |
                    | The overheads variable is set in LocalSettings.php    |
                    +-------------------------------------------------------+
                    */
                    for ($x = 1; $x < 100; $x++) {
                        if (isset($overheads[$x])) {
                            echo '<option value="' . $x . '">-- ' . $overheads[$x] . ' --</option>';
                        }
                    }

                    include('../sms/foo/ShowProjects.php');
                    $ProjX = ShowProjects($userid, $mysqli);
                    $totalprojects = sizeof($ProjX);

                    for ($i = 0; $i < $totalprojects; $i++) {
                        echo "<option value='" . $ProjX[$i]["id"] . "'>" . $ProjX[$i]["pn"] . "</option>";
                    }
                    ?>

                </select>
            </td>

        </tr>

        <tr valign="top">
            <td>Scope & Milestone:</td>

            <!-- Project Scope -->
            <td>
                <?php
                $form_scope_id = ($form_scope_id > 1) ? $form_scope_id : "1";
                $form_scope_name = ($form_scope_id > 1) ? $form_scope_name : "-- Select Scope --";
                ?>
                <input type="hidden" id="form_scope_id" value="<?= $form_scope_id ?>">
                <input type="hidden" id="form_scope_name" value="<?= $form_scope_name ?>">

                <select name="scope" id="scope" onchange="onScopeSelect()">
                    <option>-- Select Scope --</option>
                </select>
            </td>

            <!-- Project Milestone -->
            <td colspan="2">

                <?php
                $form_projectstage_id = ($form_projectstage_id > 1) ? $form_projectstage_id : "1";
                $form_projectstage_name = ($form_projectstage_id > 1) ? $form_projectstage_name : "-- Select Milestone --";
                ?>
                <input type="hidden" id="form_stage_id" value="<?= $form_projectstage_id ?>">
                <input type="hidden" id="form_stage_name" value="<?= $form_projectstage_name ?>">

                <select id="ps" name="ps">
                    <option value='0'>-- Select Milestone --</option>
                </select>

            </td>

        </tr>


        <tr valign="top">
            <td>Work Description:</td>
            <td colspan="3">
                <textarea id="wk" name="wk" style="height:60px;"><?php if ($form_work) echo $form_work; ?></textarea>
            </td>
        </tr>

        <!-- Details | Worked from & Percent -->
        <tr valign="top">
            <td>Details:</td>
            <td colspan="2">
                <?php
                    $wfid[10] = "Worked From | Office";
                    $wfid[20] = "Worked From | Remote Location";
                ?>
                <select id="lk" name="lk">
                    <?php if (isset($form_worked_from)) echo "<option value='".$form_worked_from."'>".$wfid[$form_worked_from]."</option>"; ?>
                    <option value='10'>Worked From | Office</option>
                    <option value='20'>Worked From | Remote Location</option>
                </select>
            </td>
            <td colspan="1">
                <?php
                $tag = 'placeholder="Percent Completed"';
                if (isset($form_percent)) {
                    if ($form_percent > 0) $tag = "value='$form_percent'";
                }
                ?>
                <input type="text" id="percent" name="percent" <?= $tag ?>>
            </td>
        </tr>


        <tr valign="top">
            <td></td>
            <td>
                <input class="button" type="submit" name="go" value="<?= $FormButtonName ?>">
            </td>
            <td colspan="2">
                <?php
                if ($Form_show_Cancel === "Yes") {
                ?>
                    <a href="<?= $base_url ?>studio/tms/timesheets.cgi?a=timesheet">
                        <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" alt="Cancel">
                    </a>
                <?php
                }
                ?>
            </td>
        </tr>

    </table>
</form>

<script>
    var cal
    window.onload = (event) => {
        // Hookup calendar
        cal = new Epoch('epoch_popup', 'popup', document.getElementById('dt'))

        const pid = document.getElementById("pj").value
        const a = document.getElementById("a").value
        // console.log(a)

        if (a == "timesheet-add") {
            // Add form
            onScopeSelect()
            onProjectSelect()
        } else {
            // Edit form
            onProjectSelect()
            if ((pid > 10 && pid < 100) || pid < 1) {

                // console.log("Overheads")
                stage.disabled = true
                percent.disabled = true
                scope.disabled = true

            } else {
                onScopeSelect()
            }
        }
    }
</script>
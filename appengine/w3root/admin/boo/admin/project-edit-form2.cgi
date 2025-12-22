<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Aug-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

include 'boo/admin/project-functions.php';
$px = getProjectData($projectid, $mysqli);

// var_dump($px);

?>

<form action="engine.cgi" method="POST">

    <input type="hidden" name="a" value="project-edit">
    <input type="hidden" name="pid" value="<?php echo $projectid; ?>">

    <table class="formTBL" style="background:#ffe4bb">
        <tr>
            <td colspan="4">
                Edit Project: <?php echo $px["projectname"]; ?>
            </td>
        </tr>
        <tr>
            <td>Project Name:</td>
            <td colspan="3">
                <input id="pn" type="text" name="pn" value="<?php echo $px["projectname"]; ?>">
            </td>
        </tr>
        <tr>
            <td>Jobcode:</td>
            <td colspan="3">
                <input type="text" name="jc" value="<?php echo $px["jobcode"]; ?>">
            </td>
        </tr>
        <tr>
            <td>Project Leader:</td>
            <td colspan="3">
                <?php
                include('boo/admin/comboUsers.php');
                comboUsers($px["projectleader"], $px["projectleader_id"], $mysqli, 'pm_uid');
                ?>
            </td>
        </tr>
        <tr>
            <td>Project Coordinator:</td>
            <td colspan="3">
                <?php
                comboUsers($px["pc_name"], $px["pc_uid"], $mysqli, 'pc_uid');
                ?>
            </td>
        </tr>
        <tr>
            <td>Branch</td>
            <td colspan="3">
                <?php
                include('boo/admin/comboBranch.php');
                comboBranch($px["branchname"], $px["branch_id"], $mysqli);
                ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Project Attribute (optional):</td>
        </tr>
        <tr>
            <td style="width: 30%;">Contract Date & Period</td>
            <td style="width: 30%;">
                <?php
                $cdt = "id='cdt'";
                if ($px["contractdt"] != '0000-00-00') $cdt = $cdt . ' value="' . $px["contractdt"] . '"';
                ?>
                <input type="date" name='cdt' <?= $cdt ?>>
            </td>
            <td style="width: 20%;">
                <select name="cpy" id="cpy">
                    <?php
                    if ($px["contract_period_years"] > 0) {
                        echo " <option value=" . $px["contract_period_years"] . ">" . $px["contract_period_years"] . " years</option>";
                    }
                    for ($i = 0; $i < 11; $i++) {
                        echo " <option value=" . $i . ">" . $i . " years</option>";
                    }
                    ?>
                </select>
            </td>
            <td style="width: 20%;">
                <select name="cpm" id="cpm">
                    <?php
                    if ($px["contract_period_months"] > 0) {
                        echo " <option value=" . $px["contract_period_months"] . ">" . $px["contract_period_months"] . " years</option>";
                    }
                    for ($i = 0; $i < 12; $i++) {
                        echo " <option value=" . $i . ">" . $i . " months</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Escalation Start Date</td>
            <td><?php
                $esdt = 'id="esdt"';
                if ($px["escalationdt_start"] != "0000-00-00") {
                    $esdt = $esdt . ' value="' . $px["escalationdt_start"] . '"';
                }
                ?>
                <input type="date" name="esdt" <?= $esdt ?>>
            </td>
            <td style="text-align: right;">Kickoff:</td>
            <td style="text-align: left;">
                <?php
                $ek = 'id="ek"';
                if ($px["escalation_kickoff"] > 0) $ek = $ek . ' checked';
                ?>
                <input type="checkbox" style="width:20px" name="ek" <?= $ek ?>>
            </td>
        </tr>
        <tr>
            <td>Escalation Rate</td>
            <td>
                <?php
                $erate = ($px["escalation_rate"] > 0)? 'value="'.$px["escalation_rate"].'"': "placeholder='Rate'";
                $enote = (strlen($px["escalation_note"]) > 0)? 'value="'.$px["escalation_note"].'"': "placeholder='Note'";
                //echo $enote . ' | '.$px["escalation_note"];
                ?>
                <input type="text" name="erate" <?= $erate ?>>
            </td>
            <td colspan="2">
                <input type="text" name="enote" <?= $enote ?>>
            </td>
        </tr>
        <tr>
            <td>Active:</td>
            <td colspan="3">
                <input type="checkbox" name="active" <?php if ($px["active"] > 0) echo 'checked'; ?> style="width:20px">
            </td>
        </tr>

        <tr>
            <td></td>
            <td colspan="3">
                <input type="submit" name="go" value="Edit" style="width:80px">
                <input type="submit" name="go" value="Cancel" style="width:80px">
            </td>
        </tr>
    </table>

</form>
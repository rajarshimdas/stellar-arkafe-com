<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: Mar-07					                |
| Updated On: 19-Mar-2012                               |
+-------------------------------------------------------+
| Drawing List > View Form				                |
+-------------------------------------------------------+
*/
$option = empty($_GET['s']) ? "NA" : $_GET['s'];
?>

<script type='text/javascript'>
    function gfcDataValidation() {
        var blockname = $('#bn').val();
        var disciplinecode = $('#dc').val();
        console.log('gfcDataValidation: ' + blockname + ' | ' + disciplinecode);
        if (blockname === "-- Select --" || blockname == null) {
            alert('Select Block...');
            return false;
        }
        if (disciplinecode === "-- Select --" || disciplinecode == null) {
            alert('Select Scope...');
            return false;
        }
    }

    function stagewiseDataValidate() {
        var stageno = $('#stageid').val();
        console.log('stagewiseDataValidate: ' + stageid);
        if (stageno < 1 || stageno == null) {
            alert('Select Stage...');
            return false;
        }
    }
</script>

<table style="text-align:left;width:100%;" border="0" cellpadding="2" cellspacing="0">
    <tbody>
        <tr style="height:40px;">

            <!-- Left side -->
            <form action="project.cgi" method="get">
                <td align="center" valign="top" width="35%" style="background:#E8E9FF;">
                    <input name="a" type="hidden" value="t2xview">
                    List Format:
                    <select name="s" style="width:180px">
                        <?php

                        if ($option != 'NA')
                            echo "<option>$option</option>";
                        else
                            echo "<option>-- Select Option --</option>";
                        // Packagewise (Previously Blockwise)
                        if ($option !== 'Printable - Packagewise')
                            echo '<option>Printable - Packagewise</option>';
                        // Discipline wise
                        if ($option !== 'Printable - Scope')
                            echo '<option>Printable - Scope</option>';
                        /* Stagewise - Disabled since now the Summary table Milestone Nos can be clicked to generate this info.
                        if ($option !== 'Stagewise Drawing List')
                            echo '<option>Stagewise Drawing List';
                        */
                        // GFC Release History
                        if ($option !== 'GFC Release History')
                            echo '<option>GFC Release History</option>';

                        ?>
                    </select>
                    <input type="submit" name="submit" value="Go">
                </td>
            </form>

            <!-- Right side -->
            <?php

            /* -- Select Option -- */
            if ($option == "NA") {
            ?>
                <td align="center" width="65%" style="background:#E8E9FF;vertical-align: top;">
                    Please select an option for displaying the Deliverable/Drawing List
                </td>
            <?php
            }


            /*------------------------------------------+
            | Printable - Packagewise (Blockwise)       |
            +------------------------------------------*/
            if ($option === "Printable - Packagewise") {
                echo '<td align="center" width="65%" style="background:#E8E9FF;vertical-align:top;">';
                include 'view-blockwise-01.php';
                echo '</td>';
            }


            /*------------------------------------------+
            | Printable - Scope (Disciplinecode)        |
            +------------------------------------------*/
            if ($option === "Printable - Scope") {
                echo '<td align="center" width="65%" style="background:#E8E9FF;vertical-align:top;">';
                include 'view-disciplinecode-01.php';
                echo '</td>';
            }


            /*------------------------------------------+
            | GFC Release History                       |
            | Function created on: 27-Nov-2007          |
            +------------------------------------------*/
            if ($option === "GFC Release History" && $step2 !== "s2") { ?>
                <form action="project.cgi" method="get" onsubmit="return gfcDataValidation();">
                    <input name="a" type="hidden" value="t2xview-GFCHistory">
                    <td align="center" width="65%" style="background:#E8E9FF;vertical-align: top;">
                        GFC Release History<br>
                        <?php /* Show form */
                        include 'foo/t2GFCHistory.php';
                        $lx = new GFCHistory($projectid);
                        $lx->formGFCHistory();
                        ?>
                    </td>
                </form>
            <?php
            }

            
            /*------------------------------------------+
            | Stagewise Drawing List                    |
            | Function created on: 15-Feb-2007          |
            +------------------------------------------*/
            if ($option === "Stagewise Drawing List" && $step2 !== "s2") {
                echo '<td align="center" width="65%" style="background:#E8E9FF;vertical-align: top;">Stagewise Drawing List<br>';
                include('hot2e/view-stagewise-01.cgi');
                echo '</td>';
            }
            ?>


    </tbody>
</table>
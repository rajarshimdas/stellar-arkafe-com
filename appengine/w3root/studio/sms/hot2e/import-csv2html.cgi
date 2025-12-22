<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-07					                |
| Updated On: 07-Feb-25					                |
+-------------------------------------------------------+
| Read from the user's uploaded excel file and		    |
| tabulated the drawing list. Validate the drawing 	    |
| list for errors and highlight rows with errors 	    |
+-------------------------------------------------------+
| 28-Jan-08						                        |
| a.Target dates are now optional while creating the	|
| drawing list. Validation part updated accordingly.	|
| b.Comitted dates - new				                |
| c.Above changes required that the excel template from	|
| which we read the drawings is also updated. done.	    |
+-------------------------------------------------------+
| 15-Feb-08						                        |
| Bug fix: On the FreeBSD production server, the	    |
| date validation was reading "x" as an invalid date	|
| rather than allowing it as a Null Date. Fixed         |
+-------------------------------------------------------+
| 17-May-08						                        |
| Bug Fix: If no SheetNo specified in an excel row and	|
| had the other fields correct, the line was assumed	|
| valid - false positive.				                |
+-------------------------------------------------------+
| 17-Nov-12                                             |
| fgetcsv function used to read the csv file            |
+-------------------------------------------------------+
| 07-Feb-25                                             |
| Milestone in shortcode insted of number               |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once 'bootstrap.php';

include 'foo/t2drawing.php';

$uploaddir          = $filedb . '/import/';
$filename           = $uploaddir . '/dlist-' . $_GET['n'] . '.csv';
$orgfilename        = $_GET['o'];
$maxRowsInCSVFile   = $_GET['e'];

/* Open file for read-only */
$file = fopen($filename, "r");
if (!$file) {
    echo "<p>Unable to open remote file.\n";
    exit;
}

?>

<form action='import-html2db.cgi' method='POST' style="background-color: #E8E9FF;">
    <table class="formX">
        <tr>
            <td style='width:6%;vertical-align:top;text-align:right'>
                Note:</td>
            <td style='width:65%;font-size:12px;'>The rows with elements highlighted in red (not as per our Studio Practice Standards) and Sheet Numbers indicated in blue (already exists) will not be imported into the Deliverable List. You may click cancel and go back to the excel file to rectify them before importing or you may click Create to upload the correct ones. Items in this list which are already existing in the project database will be ignored.</td>
            <td style='width:27%;text-align:center;'>
                Deliverable/Drawing List
                <br>
                <input type="hidden" name="projectid" value="<?php echo $projectid; ?>">
                <input type="hidden" name="rid" value="<?php echo $roleid; ?>">
                <input type="submit" name="submit" value="Upload">
                <input type="submit" name="submit" value="Cancel">
            </td>
            <td style='width:2%;'></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <style>
        #csvtable {
            border-collapse: collapse;
        }

        #csvtable tr td {
            border: 1px solid gray;
            height: 20px;
        }

        #csvtable tr td:first-child,
        #csvtable tr.t5 td {
            color: white;
            background-color: #adbfbc;
            height: 20px;
        }
    </style>
    <?php
    # Milestone/Stage Shortcode to id
    # 17-Feb-25
    #

    $milestones = bdGetProjectStageArray($mysqli);
    // echo '<pre>',var_dump($milestones),'</pre>';
    foreach ($milestones as $ms) {
        # CAVEAT :: dwglist stores stageno insted of id
        $shortcode[$ms['stage_sn']] = $ms['stageno'];
    }


    /* Read lines in the file */
    $rowno = 1;            // Excel sheet row numbers
    $countvalidrows = 0;    // Count Valid rows for importing

    /* Start reading from the file */
    while (($rowDataX = fgetcsv($file, 1000, ",")) !== FALSE) {

        // Row Data
        $no         = trim($rowDataX[0]);
        $sheetno    = trim($rowDataX[1]);
        $title      = trim($rowDataX[2]);
        $milestone  = strtoupper(trim($rowDataX[3]));
        $stage      = empty($shortcode[$milestone]) ? 'X: ' . $milestone : $shortcode[$milestone];
        $error_m    = '&nbsp;';


        // echo 'rowno: '.$rowno.' | no: '.$no.' | sheetno: '.$sheetno.' | title: '.$title.' | stage: '.$stage;

        // Data Validation Flag
        $rowX = 1; // Assume valid data.

        /* Generate row 1 if the headers are correct */
        if ($rowno < 2) {
            if ($no !== "No" || $sheetno !== "SheetNo*" || $title !== "Title*" || $milestone !== strtoupper("Milestone SC*")) {
                die('<br>Invalid CSV file. Use the template file and try again');
            } else {
                echo "<span style='font-size:85%;width:100%;'>Reading file: $orgfilename</span>
                        <table id='csvtable' style='font-size:85%;width:100%' cellspacing='0' cellpadding='3'>
                            <tr class='t5'>
                                <td width='20px'>&nbsp;</td>
                                <td width='50px' align='center'>A</td>
                                <td width='125px' align='center'>B</td>
                                <td align='center'>C</td>
                                <td width='120px' align='center'>D</td>
                                <!-- <td width='80px' align='center'>E</td> -->
                                <td width='350px' align='center'>Message</td>
                            </tr>";
            }
        }

        /* Check validity of sheetno */
        $test01 = 0;
        if ($rowno > 1) {

            // Data rows
            if (($sheetno !== '&nbsp;' || $sheetno !== '') && $sheetno) {

                $sheetno = strtoupper($sheetno);
                $dwg = new drawing($projectid, $sheetno);

                // echo ' | sheetnoCheck: '.$sheetno;

                /* Check sheetno validity [valid sheetno returns 1]*/
                $test01 = $dwg->checksheetno();
                if ($test01 < 1) {

                    /* SheetNo is invalid */
                    $sheetnoCell = "<span style='color:red;'>$sheetno</span>";
                    $rowX = 0; //set row data as invalid
                    $error_m = $mc;
                } else {

                    /* SheetNo is valid */
                    if ($dwg->checkifexists() > 1) {

                        /* Sheet exists and is active */
                        $sheetnoCell = "<span style='color:blue;'>$sheetno</span>";
                        $rowX = 0; //set row data as invalid
                        $error_m = 'Drawing Exists.';
                    } else {

                        /* Sheet may exists but is inactive */
                        $sheetnoCell = "<span style='color:black;'>$sheetno</span>";
                    }
                }
            }

            /* Check validity of Stage */
            if (!empty($shortcode[$milestone])) {
                /* Stage is correct */
                $stageCell = "<span style='color:black;'>$milestone</span>";
            } else {
                /* Stage is not correct */
                if ($test01 > 0) $error_m = "Milestone Shortcode [ $milestone ] is wrong.";
                $rowX = 0; //set row data as invalid
                $stageCell = "<span style='color:red;'>$milestone</span>";
            }
            
        } else {

            // This is the header row
            $sheetnoCell    = $sheetno;
            $stageCell      = trim($rowDataX[3]);
        }

        if (!$sheetno || $sheetno === '&nbsp;') $rowX = 0;    // Updated on 17-May-08

        /* Check validity of title */
        if ($title === "") {
            $rowX = 0;
        }

        /* Generate row */
        if ($rowX > 0 && $rowno > 1) {

            /* Valid Row */
            $bgcolor = "white";
            $countvalidrows++;

            $hiddenFields = "
                <input type='hidden' name='SheetNo$countvalidrows' value='$sheetno'>
                <input type='hidden' name='Title$countvalidrows' value='$title'>
                <input type='hidden' name='Stage$countvalidrows' value='$stage'>                
                    ";
        } else {

            /* Invalid Row */
            $bgcolor = "RGB(200,200,200)";
            $hiddenFields = '&nbsp;';
        }

        //echo ' | rowValidFlag: '.$rowX.'<br>';

        if ($rowno < 2) {
            /* Background color of first row */
            $bgcolor = "tan";
        }

        /* Display the Row */
        echo "
        <tr style='text-align:center;background:$bgcolor'>
                " . $hiddenFields . "
                <td style='color:white;background-color: #adbfbc;'>" . $rowno++ . "</td>
                <td>&nbsp;$no&nbsp;</td>
                <td>&nbsp;$sheetnoCell&nbsp;</td>
                <td align='left' style='padding-left:5px'>$title&nbsp;</td>
                <td>&nbsp;$stageCell&nbsp;</td>
                <td align='left' style='padding-left:5px'>$error_m&nbsp;</td>
            </tr>";

        unset($sheetnoCell, $stageCell);
    } // close while loop

    echo "</table>
	<input type='hidden' name='totalvalidrows' value='$countvalidrows'>";
    ?>

</form>
<?php
/* Close file */
fclose($file);

unset($mc);

echo "Total Valid Rows in the input csv file: $countvalidrows<br>";

?>
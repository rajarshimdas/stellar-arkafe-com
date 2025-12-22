<?php /* import-html2db.cgi
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-07					                |
| Updated On: 27-Dec-12					                |
+-------------------------------------------------------+
*/

require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';
require_once 'foo/t2drawing.php'; // In sms/foo folder

// Collect Inputs
$submit		    = $_POST["submit"];
$projectid	    = $_POST["projectid"];
$totalvalidrows = $_POST["totalvalidrows"];
$roleid 	    = $_POST["rid"];
$revno		    = 'A';
$commitdt       = '0000-00-00';
$targetdt       = '0000-00-00';

$mysqli = cn2();

// Upload the drawing list
if ($submit === "Upload") {

    // Include drawing class
    $importcounter = 0;

    for ($i = 0; $i <= $totalvalidrows; $i++) {

        // Get Row information
        $sheetno    = $_POST["SheetNo$i"];
        $title      = $_POST["Title$i"];
        $stage      = $_POST["Stage$i"];

        // Instantiate the sheet no [Needs Validation]
        $dwg = new drawing ($projectid, $sheetno);

        if ($dwg->CreateDWG($revno, $title, '-', $stage, $commitdt, $targetdt, $mysqli) !== FALSE) {
            $importcounter++;
        }

    }

}

/* Testing. No of drawings imported
die ('No of drawings imported: '.$importcounter); 
*/

/* Redirection */
header("Location: project.cgi?a=t2ximport&co=$importcounter");

?>

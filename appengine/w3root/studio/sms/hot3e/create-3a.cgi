<?php /* Wizard - Step 3 action
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Jan-2007				                |
| Updated On: 14-Feb-2012   03-Jun-2024  				|
+-------------------------------------------------------+
|  */ require 'hot3e/transmittal.php'; /*               |
+-------------------------------------------------------+
*/

/* Input Data */
$tmid     = $_POST['tmid'];
$submit = $_POST['submit'];

// Check GFC Revision - 03-Jun-2024
function is_gfc_revision($str)
{
	return (bool) preg_match('/^r[0-9]+$/i', $str);
}

/* User hit the back button */
if ($submit === "<< Back") {

    $mysqli = cn2();
    $query = "update tmheader set `wizardstepno` = 2 where `id` = $tmid";

    if (!$mysqli->query($query)) {
        printf("Error: %s\n", $mysqli->error);
    }

    $mysqli->close();

    header("location:project.cgi?a=t3xcreate-2&tm=ok&tmid=$tmid");
    die;
}

/* User hit the Create button */
if ($submit === "Create") {

    $tm = new CommitTM($projectid, $tmid, $fullname, 10, "t3xcreate-3");

    if ($tm->SaveTM()) {
        header("location:project.cgi?a=t3xview");
    } else {
        global $mc;
        header("location:project.cgi?a=t3xcreate-3&tmid=$tm->$tmid&mc=$mc");
    }
}

/* If none of above then User hit Cancel button or something went wrong - exit */
if ($submit === "Cancel") {

    $mysqli = cn2();
    //
    $query = "update tmheader set active = 0 where id = $tmid";
    if (!$mysqli->query($query)) {
        printf("Error: %s\n", $mysqli->error);
        die;
    }
    $mysqli->close();
    //
    header("location:project.cgi?a=t3xcreate");
}

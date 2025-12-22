<?php /* feeEstimate.cgi
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 16-Jan-2012				|
| Updated On: 19-Nov-2012				|
+-------------------------------------------------------+
| Manhour Budgetting                                    |
+-------------------------------------------------------+
| */ include 'hot1e/manhoursFn.cgi'; /*                 |
+-------------------------------------------------------+
*/

/* Show the Fee Estimator Upload Wizard till project gets signed off
$flag = signOffStatusFlag($project_id, $mysqli);
if ($flag < 2) {
    displayManhoursWizard($sessionid);
} else {
    echo '&nbsp;<br>';
}
*/

if ($role_id <= 12) {
    displayManhoursWizard($sessionid);
} else {
    echo '&nbsp;<br>';
}

?>
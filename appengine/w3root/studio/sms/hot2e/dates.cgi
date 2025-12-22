<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 29-Jan-07					|
| Updated On:						|
+-------------------------------------------------------+
| Tabulate the Committed Date and Target Dates for the 	|
| reference of TLs and DMs.				|
+-------------------------------------------------------+
*/

// check user role - someone tampering with the location bar...
if ($roleid > 45 || !$roleid) {
    die("<h3>Bad Dog: Go away no biscuits....</h3>");
}

// Get user selection
$dx = trim($_GET["dx"]);

include("foo/arnav/angels.cgi");

// If no disciplinecode information, show select form
if (!$dx || $dx === "-- Select --") {

    // Display a form to select the discipline code
    include('hot2e/dates-form.cgi');

} else {

    // Display the List as per user's request
    include("foo/t2DWGid.php");
    include('hot2e/dates-list.cgi');

}

$mysqli->close();

?>
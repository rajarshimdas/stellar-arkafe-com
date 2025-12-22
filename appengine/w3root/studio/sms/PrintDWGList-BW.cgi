<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 29-Feb-08					|
| Updated On: 						|
+-------------------------------------------------------+
| Print Drawing List - Blockwise			|
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

include('hot2e/view-printable-vars.cgi');	// Get variables - common
include('hot2e/view-printable-X.cgi');		// Loop funtion to display the tables - common		
// include('foo/arnav/angels.cgi');		// Get MySQL select privilege connection
include('foo/t2DWGid.php');			// DWGid() class for extracting the drawing details

//echo "Project id: $ProjID<br>Project Name: $ProjNM<br>bk: $bk<br>dc:$dc<br>ck1: $c1 <br>ck2: $c2 <br>ck3: $c3<br>Fx: $fx<br>From: $from To: $to<br>Stage: $stage<br>GFC: $gfc<br>From2: $from2 To2: $to2";

?><!DOCTYPE html">
<head>
    <link href='/matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php include 'hot2e/blocks-tabulate.cgi'; ?>
</body>

<?php $mysqli->close(); ?>
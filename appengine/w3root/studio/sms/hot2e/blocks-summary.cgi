<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 						|
| Updated On: 16-Apr-2008				|
+-------------------------------------------------------+
| Drawing List :: Blocks - Summary			|
+-------------------------------------------------------+
*/

$mysqli     = cn1();

$bk         = $_GET['bn'];		// blockno to be displayed
$ProjID     = $projectid;
$ProjNM     = $projectname;
$dc         = '-- All/Select --';

$fx         = 'a';

//$from     = trim($_POST['dt1']);
//$to       = trim($_POST['dt2']);

$c1         = "on";
$c2         = "off";
$c3         = "on";
$c4         = "on";

$stage      = 'a';
$gfc        = 'b';

$hideHeader = 'T';


//include 'hot2e/view-printable-vars.cgi';	// Get variables - common - READ this
include 'hot2e/view-printable-X.cgi';		// Loop funtion to display the tables - common
include 'foo/t2DWGid.php';			// DWGid() class for extracting the drawing details

// Tabulate
include 'hot2e/blocks-tabulate.cgi'; 

$mysqli->close();

?>
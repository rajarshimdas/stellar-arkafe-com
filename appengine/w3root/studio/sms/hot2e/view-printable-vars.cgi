<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 03-Mar-2008 				|
| Updated On: 						|
+-------------------------------------------------------+
| Drawing List > View Form				|
+-------------------------------------------------------+
| Common Variable extraction for:			|
|	1. Printable-Disciplinecode			|
|	2. Printable-Blockwise				|
+-------------------------------------------------------+
*/ 

$ProjID = $_POST['ProjID'];
$ProjNM	= $_POST['ProjNM'];

$dc     = trim($_POST['dc']);
$bk 	= trim($_POST['bk']);

$fx     = $_POST['fx'];

$from 	= trim($_POST['dt1']);
$to	= trim($_POST['dt2']);

$c1	= $_POST['c1'];
$c2	= $_POST['c2'];
$c3	= $_POST['c3'];
$c4	= $_POST['c4'];

$stage	= $_POST['stage'];
$gfc	= $_POST['gfc'];


// Block Selection Validation:
if ($discipline === "-- Select --") {	
	die("<h3>Error: Please select a Discipline and try again</h3>");
}

// Date data validation
$test01 = 1;

/* Date Formatting */
if ($from && $to){
	$date 	= new DateTime($from);
	$from2 	= $date->format("Y-m-d");
	$date 	= new DateTime($to);
	$to2 	= $date->format("Y-m-d");
}

/* Variables 
echo "Project id: $ProjID<br>Project Name: $ProjNM<br>bk: $bk<br>dc:$dc<br>ck1: $c1 <br>ck2: $c2 <br>ck3: $c3<br>ck4: $c4<br>Fx: $fx<br>From: $from To: $to<br>Stage: $stage<br>GFC: $gfc<br>From2: $from2 To2: $to2";
*/

?>
<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-Mar-2007				                |
| Updated On: 14-Feb-2012				                |
+-------------------------------------------------------+
*/

$identity       = strtoupper($_POST['identity']);
$disciplinecode = $_POST['disciplinecode'];
$unit 		= $_POST['unit'];
$part 		= strtolower($_POST['part']);
$revno		= "A";
$title 		= $_POST['title'];
$remark 	= $_POST['remark'];
$stage		= $_POST['stage'];
$targetdt 	= $_POST['targetdt'];

/* Check if stage is selected */
if ($stage < 1) {
    $mc = "Select stage...";
    header("Location: project.cgi?a=t2xcreate&mc=$mc");
    die;
}

/*
Update: 28-Jan-08
Allow users to import drawing list without target date. Users can set the
commited target date and the publicly displayed target dates later.
Syntax: For Targetdts values can be: 
	a. a valid date 
   	b. X
    
The drawing class will handle the date checkup and errors...

if ($targetdt === "R0 Target date" || !$targetdt || $targetdt === "") {
    $targetdt = "X";
}
*/
$targetdt = "X";

/* Create the sheetno from the input */
if ($part || $part != "") {
    $sheetno = $identity.'-'.$disciplinecode.'-'.$unit.'-'.$part;
} else {
    $sheetno = $identity.'-'.$disciplinecode.'-'.$unit;
}

/*
+-------------------------------------------------------+
| Class :: drawing                      		        |
+-------------------------------------------------------+
*/
include 'foo/t2drawing.php';

// Instantiate the Drawing
$dwg = new drawing ($projectid, $sheetno);

// Save the drawing to the database
if ($dwg->CreateDWG($revno, $title, $remark, $stage, 'X', $targetdt, cn2()) === FALSE){
    $mysqli->close();
    global $mc;
    header("Location: project.cgi?a=t2xcreate&mc=$mc");
} else {
    $mysqli->close();
    header("Location: project.cgi?a=t2xcreate&b=$sheetno");
}   

?>

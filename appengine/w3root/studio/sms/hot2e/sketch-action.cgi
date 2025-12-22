<?php
$sketchno = $_POST['sketchno'];
$title    = $_POST['title'];
$sentto   = $_POST['sentto'];
$sentby   = $_POST['sentby'];
$remark   = $_POST['remark'];

/* Updated on 5-Dec-2007 */
$blockno    = $_POST['bno']; // blockno
$dc         = $_POST['dc']; // disciplinecode

//echo "$sketchno<br>$title<br>$sentto<br>$sentby<br>$remark";
/* User role */
if ($roleid > 99) {
  $mc = "You do not have rights to create a sketch for $projectname";
  header("Location: project.cgi?a=t2xsketch&mc=$mc");
  die;
}
/*
+-------------------------------------------------------+
| Data Validation                                      	|
+-------------------------------------------------------+
*/
if (!$sketchno || !$title || !$sentto) {
  $mc = "Input data is not valid or empty";
  header("Location: project.cgi?a=t2xsketch&mc=$mc");
  die;
} 
if ($sentby === "-- Sent by --") {
  $mc = "Select mode of sending from the drop down menu";
  header("Location: project.cgi?a=t2xsketch&mc=$mc");
  die;
}

if ($blockno === "-- Select Block --") {
  $mc = "Select block from the drop down menu";
  header("Location: project.cgi?a=t2xsketch&mc=$mc");
  die;
}

if ($dc === "-- Select Discipline --") {
  $mc = "Select Discipline from the drop down menu";
  header("Location: project.cgi?a=t2xsketch&mc=$mc");
  die;
}

$company = '-'; //Pending
$address = '-'; //Pending


// Validate title
$title_ok_flag = 0; // Assume fail

if (preg_match ('/^([A-Za-z0-9 .,-`!()@%&$\n\r]+)+$/', $title)){
	
	// echo "Pass"; No special characters
	$title_ok_flag = 1;
}

if ($title_ok_flag < 1){
	$mc = "Title must contain Alpha Numeric characters only...";
    header("Location: project.cgi?a=t2xsketch&mc=$mc"); 
	die;
}

// Validate remark
$remark_ok_flag = 0; // Assume fail
if ($remark === "" || !$remark) $remark = '&nbsp;';
if (preg_match ('/^([A-Za-z0-9 .,;-`!()@%&$\n\r]+)+$/', $remark)){
	// echo "Pass"; No special characters
	$remark_ok_flag = 1;
}

if ($remark_ok_flag < 1){
	$mc = "Remark must contain Alpha Numeric characters only...";
    header("Location: project.cgi?a=t2xsketch&mc=$mc"); 
	die;
}
/*
+-------------------------------------------------------+
| Insert into Database                                 	|
+-------------------------------------------------------+
*/
$sql =	"select company,dooradd,streetadd,city,pincode from transadd
		where id in (select transadd_id from transname where project_id = $projectid and contact = '$sentto')";

include('foo/arnav/dblogin.cgi');

if ($result = $mysqli->query($sql)) {    						
    while ($row = $result->fetch_row()) {
        $company = $row[0];
        $address = "$row[1], $row[2], $row[3]"; 
    }    
    $result->close();
} else {
	global $mc; $mc = "Error: $mysqli->error";
	$mysqli->close();	
}

$sql = "insert into sketches values 
        
		(NULL, 
		$projectid, 
		$sketchno,
		'$blockno',
		'$dc', 
		'$title', 
		'$remark', 
        '$sentto',
        '$company',
        '$address',
		'$sentby',
		CURRENT_DATE(),
		'$loginname',
		1)";	
        
if (!$mysqli->query($sql)) {
	echo "Error: $mysqli->error";
	die();
}

$mysqli->close();

/* Redirect */
if ($mc) 
  header("Location: project.cgi?a=t2xsketch&mc=$mc"); 
else
  header("Location: project.cgi?a=t2xsketch&sk=ok&sn=$sketchno");

?>

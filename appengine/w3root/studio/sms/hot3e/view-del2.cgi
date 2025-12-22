<?php

$tmid 		= $_GET['tmid'];
$transno 	= $_GET['transno'];
$submit 	= $_GET['submit'];

if ($submit === "Yes") {
	/*
	$sql = "update transmittals set active = 0 where id = $tmid";
	include('foo/arnav/dblogin.cgi');
	if (!$mysqli->query($sql)) {
		$mc = $mysqli->error;	
		$mysqli->close();
		header("location:project.cgi?a=t3xview&mc=$mc");
	}
	*/
	include('hot3e/transmittal.php');
	
	$a = "t3xview";
	$tm = new CommitTM($projectid, $tmid, $a);
	$tm->DeleteTM($transno);	
	
}

//header("location:project.cgi?a=t3xview");

?>

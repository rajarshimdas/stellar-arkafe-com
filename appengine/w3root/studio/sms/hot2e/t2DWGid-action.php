<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 10-Dec-2007								|
| Updated On: 31-Jan-2008								|
+-------------------------------------------------------+
| Delete and Done button - Action						|
+-------------------------------------------------------+
*/

// Session information
$sx = $_GET["sx"];

/* MySQL connection - select only */
include('../foo/arnav/config.php');
include('../foo/arnav/angels.cgi');

/* Validate the session */
if (!$sx) {	
	
	die('<h3>Error: Session invalid...</h3>');
	
} else {

	include('../foo/StartSession.php');	
	$ValidUser 	= 0;
	/* Validate and return critical information about this login session */
	$a 			= StartSession($sx,$mysqli);
	$ValidUser 	= $a["ValidUser"];
	$project_id = $a["projectid"];
	$roleid		= $a["roleid"];
	$loginname	= $a["loginname"];
	
}


/* Invalid User */
if ($ValidUser < 1) die ("Error: Session could not be validated...");


/* Check User role */
if ($roleid > 99) die("You do not have drawing editing rights...");


/* Action - Done button */
if ($_GET["go"] === "Done"){
	header("Location:t2DWGid-edit.php?sx=$sx");
}


/* Action - Delete Button */
if ($_GET["go"] === "Delete") {
	
	$sheetno = $_GET["sheetno"];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">

	<tr>
		<td colspan="2" align="center" valign="top" bgcolor="#E8E9FF" height="55px" style="font-size:125%;">
			Edit: <?php if (!$sheetno) echo "Select Drawing"; else echo $sheetno; ?>
		</td>		
	</tr>
	
	<tr align="center">
		<td colspan="2">
			Do You want to Delete Drawing No: <?php echo $sheetno; ?>
			 <?php /* echo "Drawing List ID: ".$_GET["id"]; */ ?>
		</td>
	</tr>
	
	<tr>
		<form action="t2DWGid-delete.php" method="GET">
		<input name="id" type="hidden" value="<?php echo $_GET["id"]; ?>">
		<input name="sx" type="hidden" value="<?php echo $sx; ?>">
		<input type="hidden" name="sheetno" value="<?php echo $sheetno; ?>"		
		<td colspan="2" align="center">
			<input type="submit" name="go" value="Yes" style="width:100px;">
			<input type="submit" name="go" value="No" style="width:100px;">
		</td>
		</form>
	</tr>
	
	
<?php }; ?>
	
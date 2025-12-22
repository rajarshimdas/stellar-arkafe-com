<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 25-Jul-08									|
| Updated On:											|
+-------------------------------------------------------+
*/

/* Data received from clientpmc-01 */
$cn = $_GET["cn"];		// Contact Name


/* Data received from clientpmc-02 form - when in case an error occurs */
$un 		= $_GET["un"];	// Loginname returned as an error in next stage.
$em 		= $_GET["em"];	// Email address supplied by the user returned by clientpmc-02.cgi in case of error.
$user_exits = $_GET["el"];	// Username exists.


if ($user_exits === "a") {
	
	/* The form action returned that the username exists */
?>
	
	<script type="text/javascript">
		window.onload = function () {
			document.getElementById('email').select();
		};
	</script>	
	
<?php } else { ?>

	<script type="text/javascript">
		window.onload = function () {
			document.getElementById('loginname').focus();
		};
	</script>
	
	<?php 
	/* Get the email id of this contact from transname if avaliable */
	include("foo/arnav/angels.cgi");
	
	$query = "select email from transname where contact = '$cn' and project_id = $projectid and active = 1";
	
	if ($result = $mysqli->query($query)) {    
	    $row = $result->fetch_row();
	    $em = $row[0];     
	    $result->close();
	}
	
	$mysqli->close();

}

?>


<form action="execute.cgi" method="POST">

	<input type="hidden" name="a" value="t1xclientpmc-02">
	<input type="hidden" name="sx" <?php echo 'value="'.$sessionid.'"'; ?>>
	<input type="hidden" name="cn" value="<?php echo $cn; ?>">


<table style="text-align: left; width: 100%;background:#E8E9FF;" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td align="center" valign="undefined" width="25%"> 
			Register User	 
		</td>
		<td align="right" width="35%">
			Loginname*:
		</td>	
		<td align="left" width="35%">
			<input id="loginname" type="text" name="un" <?php if ($un) echo "value='$un'"; ?> style="width:100%;">	
		</td>
		<td width="5%">&nbsp;</td>
	</tr>
	
	<tr>
		<td align="center"><?php echo "<span style='font-weight:bold;'>$cn</span>"; ?></td>
		<td align="right">email*:</td>
		<td align="left">
			<input id="email" type="text" name="em" <?php if ($em) echo "value='$em'"; ?> style="width:100%;">
		</td>
		<td width="100%">&nbsp;</td>	
	</tr>
	
	<tr>
		<td align="center">&nbsp;</td>
		<td align="right">Notify User's Login info through email:</td>
		<td align="left">
			<input type="checkbox" name="ck" checked>
		</td>
		<td width="100%">&nbsp;</td>	
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center">
			<input type="submit" name="go" value="Register">			
		</td>
		<td width="100%">&nbsp;</td>	
	</tr>
	
	
</table>

<?php 
	// Error Prompts for invalid or wrong user inputs
	if ($e = $_GET["e"]) 		echo "<span style='font-size:85%;'>The user <span style='color:red;'>$e</span> already exists. Pls select a different loginname.</span>";
	if ($_GET["e0"] === "a") 	echo "<span style='font-size:85%;'>Incomplete data.</span>";
	if ($_GET["e1"] === "a") 	echo "<span style='font-size:85%;'>The <span style='color:red;'>email</span> address is wrong. Pls enter a valid email address.</span>";
	if ($_GET["e2"] === "a") 	echo "<span style='font-size:85%;'>Valid <span style='color:red;'>Loginname</span> of the registered user is required.</span>";
	if ($un = $_GET["e3"]) 		echo "<span style='font-size:85%;'>Username must be minimum 4 character long.</span>";
?>

</form>
<?php
/*
-------------------------------------------------------------------------------
Rajarshi Das
Created:        1:04 PM 31-Jan-07
Last Updated:   1:04 PM 31-Jan-07 
-------------------------------------------------------------------------------
*/

if (!$projectname) 
{
/* Prompt user to select a project and setcookie for selected project */

/* -------------------------------------------------------------------- */
/* 				Reset&Clear all prior session info 						*/
/* ----------- Unedited Code from php help files-Works! --------------- */
/* -------------------------------------------------------------------- */
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();

// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Finally, destroy the session.
session_destroy();
/* -------------------------------------------------------------------- */

?>
<table style="text-align: left; width: 100%; color:white; height:110px;" 
border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: right; width:200px">
        Please select a project:
        </td>
      <td style="text-align: center; vertical-align: top; width:400px;">
        <form name="selectproject" action="show.cgi" method="POST">
          <input type="hidden" name="loginname" value="<?php echo "$loginname"; ?>">
                  

<?php 
if ($loginname) {
?>

<select name="projectname" style="width:300px">
            <option> -- Select Project -- 
<?php            
$db = odbc_connect("vaprojects", "", "") or die("ODBC connection Failed");
$sql = "select ProjName from ProjMast order by ProjName";
$rs = odbc_prepare($db, $sql);
odbc_execute($rs) or die("Execuion of sql failed");
while ($row = odbc_fetch_array($rs)) {
  $pn = $row[ProjName];
  	//echo "<option>$pn"; 
  	if ($pn !== "Overhead") if ($pn !== "Leave/Permission") echo "<option>$pn";  
}
odbc_close($db);
echo "</select>";
echo "<input type='submit' name='submit' value='Enter'>";
} else {
	echo "CGI Error: System failed to identify you.";
}
?>	          
          
        </form>
        </td>
      <td style="width:300px; text-align: center;vertical-align:top;">  
      
        <img
        style="width: 171px; height: 64px;" 
        alt="Powered by FreeBSD"
        src="foo/images/000freebsd.gif"><br><br>
        <a href="r24in.cgi" style="text-decoration:none;color:white;">Admin Login</a>
        </td>
    </tr>
  </tbody>
</table>
<?php
die;
}
else 
{echo "$projectname";}
?>
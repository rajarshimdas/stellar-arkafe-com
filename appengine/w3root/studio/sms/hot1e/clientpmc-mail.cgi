<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 28-Jul-08									|
| Updated On:											|
+-------------------------------------------------------+
| Send Login Information to external user				|
+-------------------------------------------------------+
| PEAR Mail package must be installed for this to work	|
+-------------------------------------------------------+
*/

$user_profiles_id = $_GET["id"];

include("foo/arnav/angels.cgi");

// Sendmail function
include("hot1e/clientpmc-sendmail.cgi");


if (clientpmc_sendmail($user_profiles_id,$mysqli)) {
	
	//echo("<p>Message successfully sent!</p>");	
	header("Location: rajarshi.cgi?a=t1xclientpmc-show&id=$user_profiles_id&m=ok");

} else {
	
	die("Error:: Could not send email to contact...");
	
}

$mysqli->close();

?>
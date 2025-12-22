<?php /* 
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 11-Feb-2008								|
| Updated On: 											|
+-------------------------------------------------------+
| Session Preference - testing session_x.php			|
+-------------------------------------------------------+

Testing only - You may safely delete this file.
1. test with the userpreference in sessi0ns table as -.
2. update the userpreference in sessi0ns table with values:
	'tab:x3;raj:cool;arnav:cute'
	and try calling their values;
	
*/

echo "<h3>Test class session_x()</h3>";

// MySQL connection with select, update privileges
include("arnav/dblogin.cgi");
echo "MySQL: $mysqli->host_info"; 

// session
session_start();
$sessionid = session_id();

// include the class defination
include("session_x.php");

// Instantiate the session_x class
$px = new session_x($sessionid,$mysqli);
echo "<br>sessionid: $px->sessionid<br>userpreference: $px->userpreference";

// Test GetTagValue Method
$tag = 'arnav';
if ($value = $px->GetTagValue($tag)){
	echo "<br>Function GetTagValue returned: $value";
} else {
	echo "<br>Function GetTagValue returned: Tag ($tag) value not set";
}


/* Test UpdateTagValue Method 
$tag 	= 'arnav';
$value 	= 'cute';

if ($px->UpdateTagValue($tag,$value,$mysqli)){
	echo "<br>UpdateTagValue: Successfull";
} else {
	echo "<br>UpdateTagValue: Error in execution";
}
*/

$mysqli->close();

?>
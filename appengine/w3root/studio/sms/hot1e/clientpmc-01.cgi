<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 14-Jan-08									|
| Updated On:											|
+-------------------------------------------------------+
| Page for User loginname selection. The loginname must	|
| be unique in usersdb. 								|
+-------------------------------------------------------+
*/

/* Data received from the clientpmc form */
$cn	= $_GET["cn"];	// Contact Name.
$go = $_GET["go"];	// User selection of input buttons.


// Divert user to Add a new contact
if ($go === "New Contact") {	
	header("Location: rajarshi.cgi?a=t1xcontacts");
	die;
}


// User needs to select a Contact name - redivert back to originating page
if ($cn === "-- Select --"){
	header("Location: rajarshi.cgi?a=t1xclientpmc&mc=Select a user from the drop down list...");
	die;
}


// Check user's role
if ($roleid > 40) {
	header("Location: rajarshi.cgi?a=t1xclientpmc&mc=Only DM and TL can register Client and PMC users");
	die;	
}


// Data Validated - Show the next form
header("Location: rajarshi.cgi?a=t1xclientpmc-01a&cn=$cn");

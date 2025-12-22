<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 14-Jan-08									|
| Updated On: 25-Jul-08									|
+-------------------------------------------------------+
*/

// Check user's rights
if ($roleid > 40) {
	header("Location:rajarshi.cgi?a=t1xclientpmc&mc=Only DM and TL can register Client and PMC users");
	die;	
}

/*
+-------------------------------------------------------+
| Get Variables	| Set temporary local Variables			|
+-------------------------------------------------------+
*/
$cn = trim($_POST["cn"]);	// Contact Name
$un = trim($_POST["un"]);	// Loginname for this user
$em = trim($_POST["em"]);	// email id for this user
$ck = $_POST["ck"];			// Send email notification to user
$go = $_POST["go"];			// Submit Button
//echo "Contact Name: $cn<br>Loginname: $un<br>Email: $em<br>Go: $go";


/*
+-------------------------------------------------------+
| General Expressions Tests	on data inputs - To do...	|
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Data Validation										|
+-------------------------------------------------------+
*/
// check input data for go button
if ($go === "Register"){
	if (!$cn || !$un || !$em){		
		header("Location:rajarshi.cgi?a=t1xclientpmc-01a&cn=$cn&un=$un&em=$em&e0=a");
		die;
	}
}


// Validate the email address for new user only...
if ($go === "Register") {
	include("foo/checkemail.php");
	if (!check_email_address($em)){		
		header("Location:rajarshi.cgi?a=t1xclientpmc-01a&cn=$cn&un=$un&em=$em&e1=a");
		die;
	}
}

// Validate the username
if (strlen($un) < 4){
	header("Location:rajarshi.cgi?a=t1xclientpmc-01a&cn=$cn&em=$em&e3=$un");
	die;
}

/* Check if this loginname already exists */
include("foo/arnav/angels.cgi");
$row_cnt = 0;
$query = "select 1 from user_profiles where loginname = '$un' and active = 1";
if ($result = $mysqli->query($query)) {
    
    $row_cnt = $result->num_rows; 
    
    if ($row_cnt > 0 && $go === "Register"){ 
		/* Loginname exists, return back to loginname selection page */
		header("Location:rajarshi.cgi?a=t1xclientpmc-01a&cn=$cn&em=$em&e=$un");
		die;
	}
      
    $result->close();
}


/* Get the data from transname for this contact */
$query = "select role_id, transadd_id, extranetlogin from transname where contact = '$cn' and project_id = $projectid and active = 1";

if ($result = $mysqli->query($query)) {
    
    $row = $result->fetch_row();
    
    $role_id 			= $row[0];  
    $transadd_id 		= $row[1];
    $extranetlogin		= $row[2];    
    
    $result->close();
    
}

$mysqli->close(); 

/*
+-------------------------------------------------------+
| Generate a Random Password for this user				|
+-------------------------------------------------------+
| six characters from the MD5 sum of the username		|
| start from a random number from 5 to 20 and go upto	|
| the next six characters from there...					|
+-------------------------------------------------------+
*/
$pw_length		= 4;
$md5 			= md5($un);
$random_no 		= rand(3,23);
$random_no_2 	= $random_no + $pw_length;
$md5_array 		= str_split($md5);

for ($i = $random_no; $i < $random_no_2; $i++) {
    $passwd = $passwd . $md5_array[$i];
}

/* 
+-------------------------------------------------------+
| Register this user in the usersdb						| 
+-------------------------------------------------------+
| Insert into Tables:									|
| 1. user_profiles										|
| 2. user_projects										|
| 3. Update the transname table							|
+-------------------------------------------------------+
*/

// MySQL connect using privileged user
include("foo/arnav/dblogin.cgi");
$mysqli->autocommit(FALSE);

// Transaction error flag
$mysqli_error 		= 0;
$transactions_ok 	= 0;
	
// Insert user into the users_profile
$query = "insert into user_profiles 
				(loginname,passwd,fname,email,registered_on,dt) 
		values 	('$un','$passwd','$cn','$em',CURRENT_TIMESTAMP(),CURRENT_DATE())";
//echo "<br>SQL: $query";

if (!$mysqli->query($query)) {
	$mysqli->rollback();
	$mysqli->close();
	$mc= "Error at Q1.";
	header("location: rajarshi.cgi?a=t1xcontacts&mc=$mc");	
}		

// Get user's  user_profiles_id
$query = "select id from user_profiles 
			where
				loginname 	= '$un' 	and			
				email 		= '$em'";
//echo "<br>SQL: $query";
	
if ($result = $mysqli->query($query)) {
    
   	$row = $result->fetch_row();
   	$user_profiles_id = $row[0];
   	//echo "<br>User_Profiles_ID: $user_profiles_id";   	 
   	$result->close();    	
    
} else {
	$mysqli->rollback();
	$mysqli->close();
	$mc= "Error at Q2.";
	header("Location: rajarshi.cgi?a=t1xcontacts&mc=$mc");	
}


// Register user in user_projects
$query = "insert into user_projects 
					(user_profiles_id,project_id,role_id,role_assigned_by,role_assigned_on)
			values 	($user_profiles_id,$projectid,$role_id,'$loginname',CURRENT_TIMESTAMP())";
//echo "<br>SQL: $query";

if (!$mysqli->query($query)) {
	$mysqli->rollback();
	$mysqli->close();
	$mc= "Error at Q3.";
	header("Location: rajarshi.cgi?a=t1xcontacts&mc=$mc");	
}

// Update the transname table	
$query = "update transname set
 
			extranetlogin 		= 1,
			email 				= '$em',
			user_profiles_id 	= $user_profiles_id
			
		where 
			
			contact 	= '$cn' and 
			project_id 	= $projectid and 
			active 		= 1";

if (!$mysqli->query($query)) {
	$mysqli->rollback();
	$mysqli->close();
	$mc= "Error at Q4.";
	header("Location: rajarshi.cgi?a=t1xcontacts&mc=$mc");	
}

$mysqli->commit();
$mysqli->close();

/*
+-------------------------------------------------------+
| Email Notification to the user						|
+-------------------------------------------------------+
*/
if ($ck === "on") {
	
	// Send email notification to the user			
	include("foo/arnav/angels.cgi");	
	include("hot1e/clientpmc-sendmail.cgi");	
	
	if (clientpmc_sendmail($user_profiles_id,$mysqli)) {
		
		//echo("<p>Message successfully sent!</p>");	
		header("Location: rajarshi.cgi?a=t1xclientpmc-show&id=$user_profiles_id&m=ok");
	
	} else {
		
		die("Error:: Could not send email to contact...");
		
	}
	
	$mysqli->close();
	
}

/*
+-------------------------------------------------------+
| Header												|
+-------------------------------------------------------+
*/
header("Location:rajarshi.cgi?a=t1xclientpmc"); 

?>
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

function clientpmc_sendmail ($user_profiles_id,$mysqli) {
	
	/*
	+-------------------------------------------------------+
	| PEAR Mail package must be installed for this to work	|
	+-------------------------------------------------------+
	|		*/ require_once "Mail.php";	/*					|
	+-------------------------------------------------------+
	|		*/	include("foo/arnav/config.php");	/* 		|
	+-------------------------------------------------------+
	*/
	
	$query = "select 
					loginname,
					passwd,
					fname,
					lname,
					email
				
				from user_profiles
				
				where id = $user_profiles_id and active = 1";
				
	
	
	if ($result = $mysqli->query($query)) {
	
	    $row = $result->fetch_row();
	    	
		/* Create the data Array */        
	    $dataX = array(
	    				"loginname" 		=> $row[0],
						"passwd"			=> $row[1],
						"fname"				=> $row[2],
						"lname"				=> $row[3],
						"email"				=> $row[4]					
	    				);      
	    
	
	    $result->close();
	}
	
	
	$from 		= $mx["from"];
	$host 		= $mx["host"];
	$username 	= $mx["username"];
	$password 	= $mx["password"];
	
	$to 		= $dataX["email"];
	$subject 	= $company["name"]." :: Your Studio Login Information.";
	$body 		= "Hi ".$dataX["fname"].",\n\nStudio Login Information.\n\nUsername: ".$dataX["loginname"]."\nPassword: ".$dataX["passwd"];
	
	/*
	echo "To: $to<br>From: $from<br>Host: $host<br>Username: $username<br>PW: $password"; 
	*/
	
	$headers = array (
						'From' => $from,
						'To' => $to,
						'Subject' => $subject
					);
		
	$smtp = Mail::factory	('smtp',
								  array (
									'host' => $host,
								    'auth' => true,
								    'username' => $username,
								    'password' => $password)
							);
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		
		// Error in sending email
		// echo("<p>" . $mail->getMessage() . "</p>");
		return false;
	  
	 } 

	return true;

}

?>
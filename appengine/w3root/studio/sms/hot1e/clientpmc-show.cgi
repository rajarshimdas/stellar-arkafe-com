<?php /*
+-------------------------------------------------------+
| Rajarshi Das											|
+-------------------------------------------------------+
| Created On: 14-Jan-08									|
| Updated On:											|
+-------------------------------------------------------+
| Page for Client and PMC Registration and Display.		|
+-------------------------------------------------------+
*/ 

$contact_id = $_GET["id"]; // id for usersdb.user_profiles table 

/*
+------------------+------------------+------+-----+---------+----------------+
| Field            | Type             | Null | Key | Default | Extra          |
+------------------+------------------+------+-----+---------+----------------+
| id               | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
| loginname        | varchar(50)      | NO   | UNI |         |                |
| passwd           | varchar(150)     | NO   |     | xyz     |                |
| fname            | varchar(50)      | NO   |     |         |                |
| lname            | varchar(50)      | NO   |     | -       |                |
| email            | varchar(150)     | NO   |     |         |                |
| organization     | varchar(150)     | NO   |     | -       |                |
| designation      | varchar(50)      | NO   |     | -       |                |
| address_door_no  | varchar(50)      | NO   |     | -       |                |
| address_street   | varchar(50)      | NO   |     | -       |                |
| address_locality | varchar(50)      | NO   |     | -       |                |
| address_city     | varchar(50)      | NO   |     | -       |                |
| address_state    | varchar(50)      | NO   |     | -       |                |
| address_country  | varchar(50)      | NO   |     | -       |                |
| address_pincode  | varchar(50)      | NO   |     | -       |                |
| mobile_no        | varchar(150)     | NO   |     | -       |                |
| phone_no         | varchar(150)     | NO   |     | -       |                |
| registered_on    | datetime         | NO   |     |         |                |
| dt               | date             | NO   |     |         |                |
| active           | tinyint(1)       | NO   |     | 1       |                |
+------------------+------------------+------+-----+---------+----------------+
*/

// Read from usersdb
include("foo/arnav/angels.cgi");
//printf("<br>angels: %s\n", $mysqli->host_info);   		

$query = "select 
				loginname,
				passwd,
				fname,
				lname,
				email,
				organization,
				designation,
				address_door_no,
				address_street,
				address_locality,
				address_city,
				address_state,
				address_country,
				address_pincode,
				mobile_no,
				phone_no,
				registered_on,
				active			
			
			from user_profiles
			
			where id = $contact_id and active = 1";
			


if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();
    	
	/* Create the data Array */        
    $dataX = array(
    				"loginname" 		=> $row[0],
					"passwd"			=> $row[1],
					"fname"				=> $row[2],
					"lname"				=> $row[3],
					"email"				=> $row[4],
					"organization"		=> $row[5],
					"designation"		=> $row[6],
					"address_door_no"	=> $row[7],
					"address_street"	=> $row[8],
					"address_locality"	=> $row[9],
					"address_city"		=> $row[10],
					"address_state"		=> $row[11],
					"address_country"	=> $row[12],
					"address_pincode"	=> $row[13],
					"mobile_no"			=> $row[14],
					"phone_no"			=> $row[15],
					"registered_on"		=> $row[16],
					"active"			=> $row[17]
    				);      
    

    $result->close();
}

// Check if the user's Login is enabled or disabled
$query = "select active from user_projects where user_profiles_id = $contact_id and project_id = $projectid";
//echo "query: $query";
if ($result = $mysqli->query($query)) {
    
    $row = $result->fetch_row();
    	
	/* Login Status 0-Disabled | 1-Enabled */        
	$login_status = $row[0];
    
    $result->close();
}



/* close connection */
$mysqli->close();

// Generate the address string in html format
$address = "&nbsp;";
if ($dataX["address_door_no"] !== "-") $address = $address.$dataX["address_door_no"].",";
if ($dataX["address_street"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_street"];
if ($dataX["address_locality"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_locality"];
if ($dataX["address_city"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_city"];
if ($dataX["address_state"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_state"];
if ($dataX["address_country"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_country"];
if ($dataX["address_pincode"] !== "-") $address = $address."<br>&nbsp;".$dataX["address_pincode"];


?>

<span style="font-weight:normal;font-size:90%;">
<a href="execute.cgi?a=t1xclientpmc-mail&id=<?php echo $contact_id; ?>&sx=<?php echo $sessionid; ?>" style="text-decoration:none;color:black;">Send Login Information to the user</a>
&nbsp;|&nbsp;
<?php if ($login_status < 1) { ?>
	<a href="execute.cgi?a=t1xclientpmc-EL&id=<?php echo $contact_id; ?>&sx=<?php echo $sessionid; ?>" style="text-decoration:none;color:black;">Enable the User Login</a>
<?php } else { ?>
	<a href="execute.cgi?a=t1xclientpmc-DL&id=<?php echo $contact_id; ?>&sx=<?php echo $sessionid; ?>" style="text-decoration:none;color:black;">Disable the User Login</a>
<?php } ?>

</span>
<br>&nbsp;
<?php if ($_GET["m"] === "ok") echo "<span style='color:red;'>Login Information sent...</span>"; ?>

<table width="70%" style="font-size:85%;">
	<tr>
		<td height="30px;" colspan="2" align="left" valign="bottom" style="border-bottom:1px solid RGB(200,200,200);;font-weight:bold;font-size:100%;">
			&nbsp;Personal Information:
		</td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;First Name:</td>
		<td width="50%" align="left"><?php echo $dataX["fname"]; ?></td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;Last Name:</td>
		<td width="50%" align="left"><?php echo $dataX["lname"]; ?></td>
	</tr>
	
	<tr>
		<td height="30px" colspan="2" align="left" valign="bottom" style="border-bottom:1px solid RGB(200,200,200);;font-weight:bold;font-size:100%;">
			&nbsp;Login Information&nbsp;			
		</td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;Loginname:</td>
		<td width="50%" align="left">
			<?php echo $dataX["loginname"]; ?>
		</td>
	</tr>
	<!-- Only daemon user can see -->
	<?php if ($roleid < 2) { ?>
	<tr>
		<td width="50%" align="left">&nbsp;Password:</td>
		<td width="50%" align="left">
			<?php echo $dataX["passwd"]; ?>
		</td>
	</tr>
	<?php } ?>
	
	
	<tr>
		<td height="30px" colspan="2" align="left" valign="bottom" style="border-bottom:1px solid RGB(200,200,200);;font-weight:bold;font-size:100%;">
			&nbsp;Contact
		</td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;Email:</td>
		<td width="50%" align="left"><?php echo $dataX["email"]; ?></td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;Mobile:</td>
		<td width="50%" align="left"><?php echo $dataX["mobile_no"]; ?></td>
	</tr>
	<tr>
		<td width="50%" align="left">&nbsp;Phone:</td>
		<td width="50%" align="left"><?php echo $dataX["phone_no"]; ?></td>
	</tr>
	
	<?php if ($address !== "&nbsp;") { ?>
	<tr>
		<td height="30px" colspan="2" align="left" valign="bottom" style="border-bottom:1px solid RGB(200,200,200);;font-weight:bold;font-size:100%;">
			&nbsp;Address
		</td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $address; ?></td>
	</tr>
	<?php } ?>
	
</table>
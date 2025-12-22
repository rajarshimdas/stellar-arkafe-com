<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 16-May-09	(Noida)				            |
| Updated On: 05-Feb-11 				                |
+-------------------------------------------------------+
| SMS :: Change Password - Action			            |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

if ($_POST["go"] === "Cancel") {
    header("Location:home.cgi");
    die;
}

/* Update Log
+-------------------------------------------------------+
| 05-Feb-11     Removed restriction on use or special   |
|               characters in the password              |
+-------------------------------------------------------+
*/

$username   = $loginname;
$passwd     = addslashes(trim($_POST['pw']));
$p1         = trim($_POST['p1']);
$p2         = trim($_POST['p2']);

/*
+-------------------------------------------------------+
| Data Validation					                    |
+-------------------------------------------------------+
*/

if (!$username && !$passwd && !$p1 && !$p2) {
    header("Location:password.cgi");
    die;
}

if (!$username || !$passwd || !$p1 || !$p2) {
    $message = "Incomplete data input.";
    header("Location:password.cgi?e=$message");
    die;
}

// Check if both passwords match
if ($p1 !== $p2) {
    $message = "New Password and Retype Password did not match.";
    header("Location:password.cgi?e=$message");
    die;
}

// Check password lenght
if (strlen($p1) < 8) {
    $message = "New Password must be minimum 8 characters.";
    header("Location:password.cgi?e=$message");
    die;
}

/*
+-------------------------------------------------------+
| User Authentication					                |
+-------------------------------------------------------+
*/

/* Search Username + Password from the users table */
$founduser = 0;
$sql = "select `id` from `users` where `loginname` = '$username' and `passwd` = '$passwd' and `active` = 1";

if ($r2 = $mysqli->query($sql)) {
    $founduser = $r2->num_rows;
    $r2->close();
} else {
    printf("Error: %s\n", $mysqli->error);
}

if ($founduser < 1) {
    /* Invalid User */
    header("Location:password.cgi?e=Current password did not match. Please try again...");
    die;
} 

$mysqli->close();
/*
+-------------------------------------------------------+
| Change Password for Authenticated User		        |
+-------------------------------------------------------+
*/ 
$mysqli = cn2();

$query = "update `users` set `passwd` = '$p1' where `loginname` = '$username' and `active` = 1";
//echo "Q1: $query";
if ($mysqli->query($query)) {
    header("Location:passwordok.cgi");
    die;
} else {
    echo "Error[4]: Password change query failed.";
}

$mysqli->close();

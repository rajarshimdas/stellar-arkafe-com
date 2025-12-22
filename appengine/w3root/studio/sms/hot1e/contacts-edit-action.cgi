<?php  /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 2007					|
| Updated On: 28-Jul-2008				|
+-------------------------------------------------------+
| contacts edit action					|
+-------------------------------------------------------+
*/
include('foo/arnav/dblogin.cgi');

/* Cancel button action */
if ($_POST['submit'] === "Cancel") {
    header("location:project.cgi?a=t1xcontacts");
    die;
}

/* Check user's rights */
if ($roleid > 40) {
    $mc = "You do not have contact editing rights...";
    header("Location:project.cgi?a=t1xclientpmc&mc=$mc");
    die;
}

/*
+-------------------------------------------------------+
|	Data Collection					|
+-------------------------------------------------------+
*/
$transname_id 	= $_POST["transname_id"];
$contact	= trim($_POST['cnm']);
$pno            = trim($_POST['pno']);
$eml 		= trim($_POST['eml']);
$transadd_id    = $_POST["cid"];
$rid            = $_POST["rid"];
$go             = $_POST["go"];

//echo "transname_id: $transname_id<br>contact: $contact<br>transadd_id: $transadd_id<br>phoneno: $pno<br>email: $eml<br>role_id: $rid";
//die;

/*
+-------------------------------------------------------+
|	Cancel      					|
+-------------------------------------------------------+
*/
if ($go === "Cancel"){
    header("location:project.cgi?a=t1xcontacts");
    die;
}

/*
+-------------------------------------------------------+
|	Delete      					|
+-------------------------------------------------------+
*/
if ($go === "Delete"){
    $query = "update transname set active = 0 where id = $transname_id";
    // echo "Q: ".$query; die;
    $mysqli->query($query);
    header("location:project.cgi?a=t1xcontacts");
    die;
}

/*
+-------------------------------------------------------+
| 	Data Validation					|
+-------------------------------------------------------+
*/
/* Validate contact name
if (ereg("[^A-Za-z0-9`!@#$%^&*()-. ]", $contact)) {
    $mc = "Contact Name ($contact) can contain Alpha Numeric characters only...";
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}
*/

/* Validate phone number
if (ereg("[^0-9/+ ]", $pno)) {
    $mc = "Phone number ($pno) can Numeric characters, + and / characters only...";
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}
*/
$pno = addslashes($pno);

/* Data validation - Email address (empty email address field is always valid)
if ($eml) {
    include('foo/checkemail.php');
    if (check_email_address($eml) === false) {
        $mc="Error: Email address ($eml) is not valid...";
        header("location:project.cgi?a=t1xcontacts-edit&mc=$mc");
        die;
    }
}
*/

/*	
+-------------------------------------------------------+
| Update smsdb						|
+-------------------------------------------------------+
*/
$sql = "update 
            transname
        set
            contact	= '$contact',
            role_id 	= $rid,
            transadd_id = $transadd_id,
            phoneno 	= '$pno',
            email 	= '$eml'
        where
            id          = $transname_id";
// echo "sql: $sql;"; 

if (!$mysqli->query($sql)) {    
    $mc = "Error at Q3.";
    header("Location:project.cgi?a=t1xcontacts");
    die;
}

$mysqli->close();		

/*
+-------------------------------------------------------+
|	Redirection					|
+-------------------------------------------------------+
*/
header("Location:project.cgi?a=t1xcontacts");

?>
<?php  /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 2007					|
| Updated On: 29-Jul-08					|
+-------------------------------------------------------+
*/ 

/* User role validation */
if ($roleid > 99) {
    $mc = "$loginname, you do not have contacts editing rights for $projectname.";
    header("Location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}

// if user selected the new button
if ($_POST['submit'] === "Add New Company") {
    header("Location:project.cgi?a=t1xcontacts-addcomp");
    die;
}

/*
+-------------------------------------------------------+
|	Data Collection					|
+-------------------------------------------------------+
*/
$cid 	= $_POST['cid'];
$cnm	= trim($_POST['cnm']);
$rid	= $_POST['rid'];
$pno	= trim($_POST['pno']);
$eml	= trim($_POST['eml']);

/*
+-------------------------------------------------------+
| 	Data Validation					|
+-------------------------------------------------------+
*/
if (!$cnm) {
    $mc="Name field was empty. Please try again.";
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}

if ($cid < 1) {
    $mc="Please select company from the drop down list and try again";
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}

if ($rid < 0) {
    $mc="Please select role from the drop down list and try again";
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}


/* Validate contact name
if (ereg("[^A-Za-z0-9`!@#$%^&*()-. ]", $cnm)) {
    $mc = "Contact Name can contain Alpha Numeric characters only...";
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
$pno = addslashes($pno); // Text field now

// validate the email address 
include('foo/checkemail.php');
if ($eml !== "") {
    /* empty address is always ok */
    if (!check_email_address($eml)) {
        /* any user typed address needs to be validated */
        $mc='The email addressis ($eml) wrong. Please enter a valid email address';
        header("location:project.cgi?a=t1xcontacts&mc=$mc");
        die;
    }
}

/*
+-------------------------------------------------------+
|	Register new contact in the smsdb database	|
+-------------------------------------------------------+
*/
include('foo/arnav/dblogin.cgi');	

// check if the name already exists for this specific project
$nameexists = 0;
$sql = "select
            1
        from 
            transname
        where
            project_id = $projectid and
            contact = '$cnm' and
            transadd_id = $cid and
            active = 1";
//echo "Q: $sql";
//die;

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_row()) {
        $nameexists = $row[0];
    }
    $result->close();
} else {
    echo "Error: $mysqli->error";
}

if ($nameexists > 0) {
    $mc= "The contact name $pnm already exists" ;
    header("location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}

// Insert Data
$sql = "insert into transname
            (contact,role_id,project_id,transadd_id,phoneno, email, extranetlogin, passwd, user_profiles_id, active)
        values
            ('$cnm',$rid,$projectid,$cid,'$pno','$eml',0,'-',0,1)";


if (!$mysqli->query($sql)) {
    $error = "Error: $mysqli->error";
    die("Error: $error");
}

$mysqli->close();		

/*
+-------------------------------------------------------+
| Redirection                                           |
+-------------------------------------------------------+
*/
header("Location:project.cgi?a=t1xcontacts");	

?>
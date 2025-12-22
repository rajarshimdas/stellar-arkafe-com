<?php
/* Create a new company */

/* Cancel button */
$submit	= $_POST['submit'];
if ($submit === "Cancel") {
    header("Location:project.cgi?a=t1xcontacts");
    die;
}

/* User Inputs */
$company    = trim($_POST['company']);
$door       = trim($_POST['door']);
$street     = trim($_POST['street']);
$locality   = trim($_POST['locality']);
$city       = trim($_POST['city']);
$state      = trim($_POST['state']);
$pincode    = trim($_POST['pincode']);
$website    = trim($_POST['website']);


/* Output the user's inputs
echo 	"company: 	$company<br>
        door:		$door<br>
        street:		$street<br>
        locality:	$locality<br>
        city:		$city<br>
        state:		$state<br>
        pincode:	$pincode<br>
        website:	$website<br>
        projectid:      $projectid<br>
        roleid:         $roleid<br>
        projectname:    $projectname";

die();
*/

// Data Validation
$t1 = 0;
if (!$company) {

    $mc = "Incomplete data input. Fill all the fields with the * symbol.";
    header("Location:project.cgi?a=t1xcontacts-addcomp&mc=$mc");
    die;

}


$mysqli = cn2();

$query = "insert into transadd
            (project_id,company,dooradd,streetadd,locality,city,statecountry,pincode,website,active)
        values
            ($projectid,'$company','$door','$street','$locality','$city','$state','$pincode','$website',1)";

//echo "Q: ",$query;
//die;
if (!$mysqli->query($query)) {
    $mc = 'Error[1]: Data could not be saved.';
    header("location:project.cgi?a=t1xcontacts-addcomp&mc=$mc");
}

$mysqli->close();

// Redirection
header("location:project.cgi?a=t1xcontacts-addcomp&q=ok&nm=$company");

?>
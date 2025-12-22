<?php

/* User role validation */
if ($roleid > 99) {
    $mc = "$loginname, you do not have contacts editing rights for $projectname.";
    header("Location:project.cgi?a=t1xcontacts&mc=$mc");
    die;
}

// Get variables
$id 		= $_POST['id'];
$submit 	= $_POST['submit'];
$company	= $_POST['company'];
$dooradd	= $_POST['door'];
$streetadd	= $_POST['street'];
$locality	= $_POST['locality'];
$city		= $_POST['city'];
$statecountry   = $_POST['statecountry'];
$pincode	= $_POST['pincode'];
$website	= $_POST['website'];


if ($submit === "Cancel") {
    header("location:project.cgi?a=t1xcontacts-edit-company");
    die;
}

/* Class Defination */
include('hot1e/contacts.php');


/* Delete company address */
if ($submit === "Delete") {

    $x = new company($projectid);

    if ($x->DeleteCompany($id)) {
        header("location:project.cgi?a=t1xcontacts-edit-company");
        die;
    } else {
        if ($err) {
            $mc = $err;
        }else {
            $mc = "System error: Could not edit the Company Address";
        }
        header("location:project.cgi?a=t1xcontacts-edit-company-form&id=$id&company=$company&dooradd=$dooradd&streetadd=$streetadd&locality=$locality&city=$city&statecountry=$statecountry&pincode=$pincode&website=$website&mc=$mc");
        die;
    }

}


/* Edit company address */
if ($submit === "Edit") {

    $x = new company($projectid);

    if ($x->EditCompany($id,$company,$dooradd,$streetadd,$locality,$city,$statecountry,$pincode,$website)) {
        header("location:project.cgi?a=t1xcontacts-edit-company");
        die;
    } else {
        if ($err) {
            $mc = $err;
        }else {
            $mc = "System error: Could not edit the Company Address";
        }
        header("location:project.cgi?a=t1xcontacts-edit-company&mc=$mc");
        die;
    }

}

?>
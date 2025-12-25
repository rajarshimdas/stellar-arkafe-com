<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On:   11-Jan-09	(Sunday evening)            |
| Updated On:   19-Oct-2010				                |
|               22-Dec-2022 (Stop Email Notification)   |
|               24-Oct-2023 Message - Success/Error     |
+-------------------------------------------------------+
| */ $mysqli = cn2(); /*                                |
+-------------------------------------------------------+
*/

// Domain
// $domain_id = $user_domain_id;
$domain_id = 2;

// Get the variables
$this_loginname = trim($_POST["lx"]);
$passwd = $_POST["pw"];
$displayname = trim($_POST["dn"]);
$reports_to_user_id = (int)$_POST["rm_uid"];    // Abhikalpan mod
$userhrgroup_id = (int)$_POST["hid"];
$branch_id = (int)$_POST["bid"];
$doj = (strlen($_POST["doj"]) > 0) ? $_POST["doj"] : '1900-01-01';

/* Variables
  echo "thisLoginname: $this_loginname
  <br>Display Name: $displayname
  <br>First Name: $firstname
  <br>Last Name: $lastname
  <br>email: $email
  <br>Gender_id: $gender
  <br>Branch_id: $branch_id
  <br>HR: $userhrgroup_id
  <br>Department_id: $department_id
  <br>Reprots_to_user_id: $reports_to_user_id
  <br>Designation: $designation";
 die;
*/

/*
+-------------------------------------------------------+
| Data Validation                                       |
+-------------------------------------------------------+
*/
// Validation
$flag = 0;

/* check all the fields are available */
if (
    !$this_loginname ||
    !$displayname ||
    !$passwd ||
    $branch_id < 1 ||
    $userhrgroup_id < 1 || 
    $reports_to_user_id < 150
) {
    $flag = 2;
    $message = "Invalid / Incomplete data at input.";
}

if (strlen($this_loginname) < 6){
    $flag = 2;
    $message = "Loginname must be at least 6 characters";
}

/*
+-------------------------------------------------------+
| ci3 Data Validation                                   |
+-------------------------------------------------------+
*/

// check 1:
if (alpha_numeric_dot($this_loginname) !== true) {
    $flag = 2;
    $message = 'Loginname can contain Alphabets, Integers and Dot only.<br>Please try again.';
}


// check 2: loginname already exists
if ($flag < 1) {

    $query = "select 
                1
            from
                users
            where
                domain_id = $domain_id and
                loginname = '$this_loginname' and
                active = 1";

    if ($result = $mysqli->query($query)) {

        $row_cnt = $result->num_rows;

        if ($row_cnt > 0) {

            // The user already exists
            $message = "The Loginname must be unique. $this_loginname already exists.";
            $flag = 2;
        }

        $result->close();
    }
}

/*
+-------------------------------------------------------+
| Store in database                                     |
+-------------------------------------------------------+
*/

// If no error encountered - enter this user in the users table.
if ($flag < 1) {

    $mysqli->autocommit(FALSE);

    $query = "insert into users
                (domain_id,loginname,passwd,fullname,emailid,internaluser,remark,active)
            values
                ($domain_id,'$this_loginname','$passwd','$displayname','user@domain.tld',1,'-',1)";
    //echo "Q2: $query";

    if (!$mysqli->query($query)) {
        $message = "Error[Q2] :: " . $mysqli->error;
        $flag = 2;
    } else {
        // Get the user_id
        $this_user_id = $mysqli->insert_id;
    }

    // $DateOfBirth = '1977-11-23';
    $gender = '-';
    $designation = '-';
    $firstname = '-';
    $lastname = '-';
    $department_id = 3;

    $query = "insert into users_a
                    (user_id,gender,designation,reports_to_user_id,fname,lname,dt_of_joining,department_id,branch_id,userhrgroup_id)
            values
                    ($this_user_id,'$gender','$designation',$reports_to_user_id,'$firstname','$lastname','$doj',$department_id,$branch_id,$userhrgroup_id)";
    //echo "Q3: $query";

    if (!$mysqli->query($query)) {
        $message = "Error[Q3] :: " . $mysqli->error;
        $flag = 2;
    }

    // Commit Data
    if ($flag > 0) {
        $mysqli->rollback();
    } else {
        $mysqli->commit();
        $flag = 1;
        $message = "New User $displayname added.<br>(Loginname: $this_loginname | Password: $passwd).";
    }
}

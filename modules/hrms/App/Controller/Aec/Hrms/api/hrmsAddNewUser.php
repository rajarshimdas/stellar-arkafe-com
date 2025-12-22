<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On:   11-Jan-2009	(Sunday evening)            |
| Updated On:   19-Oct-2010				                |
|               22-Dec-2022 (Stop Email Notification)   |
|               24-Oct-2023 Message - Success/Error     |
|               08-Dec-2025 HRMS version                |
+-------------------------------------------------------+
*/

// env.php Variables
// $domain_id
// $domain_id 
// $branch_id


// Get the variables
$this_loginname         = trim($_POST["dxLoginname"]);
$displayname            = trim($_POST["dxDisplayname"]);
$reports_to_user_id     = (int)$_POST["dxReportsToUId"];    // Abhikalpan mod
$userhrgroup_id         = (int)$_POST["dxHrgroupId"];
$doj                    = !empty($_POST["dxdoj"]) ? $_POST["dxdoj"] : '1977-11-23';
$passwd                 = bdGeneratePassword(8);

/*
+-------------------------------------------------------+
| Data Validation                                       |
+-------------------------------------------------------+
*/
// Validation flag
$flag = 0;

/* check all the fields are available */
if (
    !$this_loginname ||
    !$displayname ||
    $userhrgroup_id < 1 ||
    $reports_to_user_id < 150
) {
    $flag = 2;
    $message = '<div class="rd-messagebox-fail">Invalid / Incomplete data at input.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

if (strlen($this_loginname) < 6) {
    $flag = 2;
    $message = '<div class="rd-messagebox-fail">Loginname must be at least 6 characters</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}

/*
+-------------------------------------------------------+
| Data Validation                                       |
+-------------------------------------------------------+
*/

// check 1:
if (!isAlphaNumDot($this_loginname)) {
    $flag = 2;
    $message = '<div class="rd-messagebox-fail">Loginname can contain Alphabets, Integers and Dot only. Please try again.</div>';
    die(bdReturnJSON(["F", $message, 'x']));
}


// check 2: loginname already exists
if ($flag < 1) {

    $query = "select 
                1
            from
                users
            where
                domain_id = $domain_id and
                loginname = '$this_loginname' 
                /* and active = 1 */";
    // die(bdReturnJSON(["F", $query, 'x']));

    if ($result = $mysqli->query($query)) {

        $row_cnt = $result->num_rows;

        if ($row_cnt > 0) {

            // The user already exists
            $flag = 2;
            $message = '<div class="rd-messagebox-fail">The Loginname must be unique. ' . $this_loginname . ' already exists.</div>';
            die(bdReturnJSON(["F", $message, 'x']));
        }

        $result->close();
    }
}

/*
+-------------------------------------------------------+
| Store in database                                     |
+-------------------------------------------------------+
*/
$mysqli = cn2();

// If no error encountered - enter this user in the users table.
if ($flag < 1) {

    $mysqli->autocommit(FALSE);

    $query = "insert into users
                (domain_id,loginname,passwd,fullname,emailid,internaluser,remark,active)
            values
                ($domain_id,'$this_loginname','$passwd','$displayname','user@domain.tld',1,'-',1)";
    //echo "Q2: $query";

    if (!$mysqli->query($query)) {
        $message = '<div class="rd-messagebox-fail">Error[Q2] :: ' . $mysqli->error . '</div>';
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
        $message = '<div class="rd-messagebox-fail">Error[Q3] :: ' . $mysqli->error . '</div>';
        $flag = 2;
    }

    // Commit Data
    if ($flag > 0) {
        $mysqli->rollback();
        die(bdReturnJSON(["F", $message, 'x']));
    } else {
        $mysqli->commit();
        $flag = 1;
        $message = "<div class='rd-messagebox-ok'><div><b>$displayname</b></div><div>Loginname: $this_loginname</div><div>Password: $passwd</div></div>";
        die(bdReturnJSON(["T", $message, 'x']));
    }
}

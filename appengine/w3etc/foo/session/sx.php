<?php

/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 14-Aug-2009                 		        |
| Updated On: 12-Jul-2013                               |
|             24-Oct-2023 view_users                    |
+-------------------------------------------------------+
| Returns userLogin array                               |
+-------------------------------------------------------+
*/

function sessionHandler($base_url, $LoginTimeOut, $cookieName, $w3path, $mysqli)
{

    // Variable initialization
    $ValidUser      = 0;            // Flag set to false
    $loginExpFlag   = "F";          // Flag set to false. Live
    $loginname      = '-';
    $fullname       = '-';
    $user_id        = 0;
    $dept_id        = 0;
    $dept_name      = '-';
    $now            = time();
    $daemon         = 0;            // 1 is daemon

    $sessionid = startSession($base_url, $cookieName, $w3path, $now);

    /* Authenticate and Get variables from the sessi0ns table */
    $query = "SELECT
                loginname,
                project_id,
                projectname,
                jobcode,
                role_id
            FROM
                sessi0ns
            WHERE
                sessionid = '$sessionid' AND
                active > 0";

    //die('Q1: '.$query.'<br>');

    $matchFlag = 0;

    if ($result = $mysqli->query($query)) {

        $matchFlag = $result->num_rows;

        if ($matchFlag < 1) {
            /* No match found - invalid user */
            header("Location:" . $base_url . "login/index.cgi?e=session-not-found");
            die;
        } else {
            /* The user is currently logged in and is a valid user */
            $ValidUser = 1;

            /* Get details of the session from sessi0ns table */
            $row = $result->fetch_row();

            $loginname      = $row[0];
            $project_id     = $row[1];
            $projectname    = $row[2];
            $jobcode        = $row[3];
            $role_id        = $row[4];
        }

        $result->close();
    } else {

        die("Error[sessionHandler 1]: " . $mysqli->error);
    }


    /*
    +-------------------------------------------------------+
    | Users                                                 |
    +-------------------------------------------------------+
    */
    $query = "select * from `view_users` where `loginname` = '$loginname' and `active` > '0'";

    $result = $mysqli->query($query);
    if ($row = $result->fetch_assoc()) {
        $u = $row;
    }

    /*
    +-------------------------------------------------------+
    | Project Domain                                        |
    +-------------------------------------------------------+
    */
    $query = "select
                t1.id,
                t1.domainname,
                t1.corporatename,
                t1.description as address
            from
                domain as t1
            where
                t1.id = 2";

    // die('Q3: '.$query.'<br>');

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();

        $domainid = $row[0];
        $domainname = $row[1];
        $corporatename = $row[2];
        $corporateaddress = $row[3];
    } else {

        printf("Error[3]: %s\n", $mysqli->error);
        die;
    }

    /*
    +-------------------------------------------------------+
    | Daemon                                                |
    +-------------------------------------------------------+
    */
    $query = "select 1 from `daemons` where `name` = '$loginname'";

    if ($result = $mysqli->query($query)) {
        $daemon = $daemon + $result->num_rows;
        $result->close();
    } else {
        die('Error[4]: ' . $mysqli->error);
    }


    /*
    +-------------------------------------------------------+
    | Save the results in userLogin array                   |
    +-------------------------------------------------------+
    */
    $userLogin = array(
        "sessionid"         => $sessionid,
        "validUser"         => $ValidUser,
        "loginExpFlag"      => $loginExpFlag,

        "loginname"         => $loginname,
        "daemon"            => $daemon,
        "fullname"          => $u["displayname"],
        "user_id"           => $u["user_id"] + 0,
        "user_domain_id"    => 2,
        "dept_id"           => $u["department_id"] + 0,
        "dept_name"         => $u["departmentname"],
        "branch_id"         => $u["branch_id"] + 0,
        "branch_name"       => $u["branchname"],
        "emailid"           => $u["emailid"],
        "hrgroup_id"        => $u["userhrgroup_id"] + 0,
        "hrgroup"           => $u["hrgroup"],
        "avatar"            => $u["avatar"],

        "projectid"         => $project_id + 0,
        "projectname"       => $projectname,
        "jobcode"           => $jobcode,
        "roleid"            => $role_id + 0,

        "domainid"          => $domainid + 0,
        "domainname"        => $domainname,
        "corporatename"     => $corporatename,
        "corporateaddress"  => $corporateaddress,

    );
    // var_dump($userLogin);

    $_SESSION["loginname"] = $loginname;
    $_SESSION['loginexp'] = ($now + $LoginTimeOut + 0);

    return $userLogin;
}

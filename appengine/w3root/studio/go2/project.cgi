<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: Jan-2007					|
| Last Updated On: 26-Oct-2010				|
+-------------------------------------------------------+
| SMS :: go2project					|
+-------------------------------------------------------+
| Update: 25-Feb-08					|
| Setup a session variable to set a login timeout	|
+-------------------------------------------------------+
| Update: 12-Aug-09					|
| The session variable "loginexp" can now be controlled	|
| from the config.php file				|
+-------------------------------------------------------+
| Update: 21-Oct-10                                     |
| The roleinproject table is changed. loginname column  |
| is removed and user_id column is added. The           |
| corresponding update is done to this program          |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"].'/clientSettings.cgi';

// $mysqli = cn1();

/*
+-------------------------------------------------------+
| Get Variables						|
+-------------------------------------------------------+
*/
$userid 	= $_POST['uid'];
$projectid 	= $_POST['pid'];
$loginname  = $_POST['ln'];

/*
+-------------------------------------------------------+
| Data Validation					                    |
+-------------------------------------------------------+
*/
if ($projectid < 1) {
    header("Location:".$base_url."studio/home.cgi");
    die;
}


/* PART A
+-------------------------------------------------------+
| 1. Project information				                |
| 2. Role of this user in this project			        |
+-------------------------------------------------------+
*/
$roleid = 250; // Setting the default role - GUEST

$sql20 = "select 
            projectname,
            jobcode,
            teamleader,
            designmanager
        from
            projects
        where
            id = $projectid";

//echo "<br>Q20: $sql20";

if ($r2=$mysqli->query($sql20)) {

    /* Set counter to identifying new project or projectname change */
    $co=0;
    $co=$r2->num_rows;
    
    /*Project is found*/
    if ($co > 0) {
        $row=$r2->fetch_row();

        $projectname 	= addslashes($row[0]);
        $jobcode 	= $row[1];
        $teamleader	= $row[2];
        $designmanager 	= $row[3];

    }

    $r2->close();
}else {
    printf("Error[20]: %s\n", $mysqli->error);
}

/* Select roleid from roleinproject table */	
$sql7 = "select 
            roles_id
        from
            roleinproject 
        where
            user_id = '$userid' and
            project_id = $projectid";

//echo "<br>Q17: $sql7";

if ($r2=$mysqli->query($sql7)) {
    $row = $r2->fetch_row();
    $roleid = $row[0];
    $r2->close();
} else {
    printf("Error[7]: %s\n", $mysqli->error);
}		

/* Update roleid for the daemon users */
$sql88 = "select 
            t1.role_id
        from
            daemons as t1,
            users as t2
        where
            t2.id = $userid and
            t2.loginname = t1.name";

if ($r2=$mysqli->query($sql88)) {

    $co5 = 0;
    $co5 = $r2->num_rows;
    // User is a system daemon
    if  ($co5 > 0) {
        $row = $r2->fetch_row();
        $roleid = $row[0];
    }

    $r2->close();
} else {
    printf("Error[88]: %s\n", $mysqli->error);
}	

$mysqli->close();

/* PART B
+-------------------------------------------------------+
| 1. Update the session table information		        |
+-------------------------------------------------------+
*/
$mysqli = cn2();

$query = "update sessi0ns set
            project_id = $projectid,
            projectname = '$projectname',
            jobcode = '$jobcode',
            role_id = $roleid
        where
            sessionid = '$sessionid'";

if (!$mysqli->query($query)) {
    //echo "Q: $query";
    printf("Error[69]: %s\n", $mysqli->error);
    die();
}

$mysqli->close();

/*
+-----------------------------------------------------------+
|	Display wellcome page of selected project				|
+-----------------------------------------------------------+
*/	
header("Location:".$base_url."studio/sms/project.cgi?a=t1xsnapshot");		    

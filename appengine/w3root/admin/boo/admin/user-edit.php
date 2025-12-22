<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 20-Oct-10                   		        |
| Updated On:               				            |
+-------------------------------------------------------+
*/ $mysqli = cn2(); /*                                 |
+-------------------------------------------------------+
*/

$go     = $_POST["go"];
$url    = $_SERVER["HTTP_HOST"];

// No action required if Cancel button
if ($go === "Cancel") {
    header("Location:?a=User Edit");
}

// Edit button action
if ($go === "Edit") {

    $flag = 0;

    // Get Variables
    $thisUID            = $_POST["thisUID"];
    $displayName        = $_POST["dn"];
    $branch_id          = $_POST["bid"];
    $reports_to_user_id = $_POST['rm_uid'];                    // Abhikalpan mod
    $hrgroup_id         = $_POST["hid"];
    $doj = (strlen($_POST['doj']) > 0) ? $_POST['doj'] : '1977-11-23';
    $doe = (strlen($_POST['doe']) > 0) ? $_POST['doe'] : '2050-12-31';

    /*
    var_dump($_POST);
    echo('<br>doj: ' . $doj. ' | <br>');
    die('doe: ' . $doe);
    */


    if ($thisUID == $reports_to_user_id) {
        //die("<div style='text-align:center;'>Error: Reporting Manager cannot be self.</div>");
        $flag = 2;
        $message = "Error: Reporting Manager cannot be self.";
    }

    if ($flag < 1) {
        // Update Database
        if (updateUserData(
            $thisUID,
            $displayName,
            $branch_id,
            $reports_to_user_id,
            $hrgroup_id,
            $doj,
            $doe
        ) === FALSE) {
            $flag = 2;
            $message = "Error in updating the user.";
        } else {
            $flag = 1;
            $message = "User data is updated.";
        }
    }
}

/*
+-------------------------------------------------------+
| Function: updateUserData 				                |
+-------------------------------------------------------+
*/
function updateUserData(
    int $thisUID,
    string $displayName,
    int $branch_id,
    int $reports_to_user_id,
    int $hrgroup_id,
    string $doj,
    string $doe
): bool {

    $mysqli = cn2();

    // Disable Autocommit
    $mysqli->autocommit(FALSE);
    $error_flag = 0;

    $query = "update users set
                fullname = '$displayName'
            where
                id = $thisUID";
    // echo "<br>Q1: $query";

    /* Run Query */
    if (!$mysqli->query($query)) {
        # die("Error[Q1] :: " . $mysqli->error);
        $error_flag = 1;
    }

    $query = "update 
                users_a
            set
                reports_to_user_id = $reports_to_user_id,
                branch_id = $branch_id,
                userhrgroup_id = $hrgroup_id,
                dt_of_joining = '$doj',
                dt_of_termination = '$doe'
            where
                user_id = $thisUID";
    //echo "<br>Q2: $query";

    /* Run Query */
    if (!$mysqli->query($query)) {
        # die("Error[Q2] :: " . $mysqli->error);
        $error_flag = 1;
    }

    // Commit Data
    if ($error_flag > 0) {
        $mysqli->rollback();
        $mysqli->close();
        
        return false;
    } else {
        $mysqli->commit();
        $mysqli->close();

        return true;
    }
}

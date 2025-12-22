<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 19-Oct-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Session
if (isset($_GET["showDel"])) {
    $sysShowDeletedUser = $_GET['showDel'];
    $_SESSION["sysShowDeletedUser"] = $sysShowDeletedUser;
}

// Check if the user is selected

// Try GET method
$thisUID = isset($_GET["uid"]) ? $_GET["uid"] : 0;
// Try POST method
if ($thisUID < 1) {
    $thisUID = isset($_POST["uid"]) ? $_POST["uid"] : 0;
}

// Logic for loading relevant form
if ($flag == 1) {

    // After the user is edited.
    include 'boo/admin/user-edit-message.cgi';
} else {

    if ($thisUID > 1) {

        // User for editing is selected
        include('boo/admin/user-edit-form.cgi');
    } else {

        // User for editing is to be selected
        include('boo/admin/user-edit-tabulation.cgi');
    }
}

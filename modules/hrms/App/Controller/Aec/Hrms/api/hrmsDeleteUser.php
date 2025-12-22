<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 09-Jan-09					                |
| Updated On: 						                    |
+-------------------------------------------------------+
*/

$delete_uid = !empty($_POST["uid"]) ? $_POST["uid"] : 0;

if ($delete_uid < 1) {

    // User not selected
    $message = '<div class="rd-messagebox-fail">Select a user and try again.</div>';
    $flag = 2;
    die(bdReturnJSON(["F", $message, 'x']));

} else {
    $query = "select fullname from users where id = $delete_uid";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $fullname = $row[0];

        $result->close();
    }

    // Validate user timesheet approval status
    $new_no = 0;
    $query = 'select 
                1 
            from 
                timesheet 
            where 
                user_id = ' . $delete_uid . ' and 
                active > 0 and 
                approved < 1 and
                pm_review_flag < 1 and 
                project_id > 15';
                
    //die($query);

    if ($result = $mysqli->query($query)) {
        $new_no = $result->num_rows;
        $result->close();
    }


    if ($new_no > 0) {

        $message = "<div class='rd-messagebox-fail'>User $fullname has $new_no timesheet entries which require approval before deleting.</div>";
        $flag = 2;
        die(bdReturnJSON(["F", $message, 'x']));

    } else {
        // delete the user
        $mysqli2 = cn2();

        $query = "update users set active = 0 where id = $delete_uid";
        // echo "Q1: $query";
        if ($mysqli2->query($query)) {
            $message = "<div class='rd-messagebox-ok'>User $fullname is  deleted.</div>";
            $flag = 1;
            die(bdReturnJSON(["T", $message, 'x']));
        }
    }
}

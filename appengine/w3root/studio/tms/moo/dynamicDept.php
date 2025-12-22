<?php /* Select Department on the fly
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 11-Feb-11                                 |
| Updated On:       					|
+-------------------------------------------------------+
*/
// $mysqli = cn1();

/*
+-------------------------------------------------------+
| Get this users dept_id from sessioncache              |
+-------------------------------------------------------+
*/
$query = "select
            `value`
        from
            sessioncache
        where
            sessionid = '$sessionid' and
            `key` = 'timesheetDeptID'";

//echo "Q: $query<br>";

if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();
    $deptX = $row[0];
    // echo "DeptX: $deptX<br>";

    $result->close();
} else {
    printf("Error[1]: %s\n", $mysqli->error);
}

$deptX      = explode(':',$deptX);
$dept_id    = $deptX[0];
$dept_name  = $deptX[1];

// echo $deptX." DeptID: $dept_id<br>DeptName: $dept_name";

// $mysqli->close();

?>

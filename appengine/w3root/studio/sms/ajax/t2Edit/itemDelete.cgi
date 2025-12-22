<?php
/*
 * Make sure there is no transmittal created for this row.
 * Make sure that the member is not sending a spoofed itemId.
 * Limit risk by allowing to edit current session project only.
 *
*/

$itemId = trim($_GET["itemId"]);


// Check if a transmittal is already created
$r0flag = 0;
$query = "select 1 from dwglist where id = $itemId and r0issuedflag > 0";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {
        $r0flag = $row[0] + 0;
    }

    $result->close();
}

if ($r0flag > 0) {
    echo "Item has a transmittal entry. Cannot be deleted.";
    die;
}


// Delete
$query = 'update
            dwglist
        set
            active = 0
        where
            id = '.$itemId.' and
            r0issuedflag < 1 and
            project_id = '.$project_id;

if (!$mysqli->query($query)){

    echo "Item could not be deleted.".$query;

} else {

    echo "T";

}

?>

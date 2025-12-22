<?php
// Not used

require_once 'bootstrap.php';
$mysqli = cn1();

// Variables
$pid = $_GET['pid'];
// echo 'Show Versions PHP. '.$pid;

$query = 'select version from timeestimateversion where project_id = '.$pid.' order by dtime desc';
//echo "Q: ".$query;

if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
        //printf ("%s (%s)\n", $row[0], $row[1]);
        $verX[] = array("ver" => $row[0]);

    }

    /* free result set */
    $result->close();
}

$co_verX = count($verX);

// Display the Versions combo box
?>
<input id="f5pid" type="hidden" value="<?php echo $pid; ?>">
Select Version: <select id="f5ver" style="width:250px" onchange="javascript:changeVersion()">
    <?php
    if ($co_verX < 1) {
        echo '<option value="0">No Fee Calculator data uploaded for this project.</option>';
    } else {
        for ($i = 0; $i < count($verX); $i++) {
            if ($i < 1) echo '<option value="0">-- Select Version --</option>';
            echo '<option value="'.$verX[$i]["ver"].'">'.$verX[$i]["ver"].'</option>';
        }
    }
    ?>
</select>

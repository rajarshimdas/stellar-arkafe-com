<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 15-Feb-08					|
| Updated On:						|
+-------------------------------------------------------+
| Display the drawing list for specific stage		|
+-------------------------------------------------------+
| Inputs:						|
|	$projectid	(already known from getvars)	|
|	$stageno					|
|	$disciplinecode					|
+-------------------------------------------------------+
*/

// Require
include('hot2e/view-stagewise-03.cgi');     // Loop function to Tabulate the dwglist
include('foo/t2DWGid.php');                 // class: DWGid

// Variables
if (!$stageid) $stageid = $_GET["stageid"];
if (!$dc) $dc = $_GET["dc"];

// Data Validation
if (!numeric($stageid)) {
    die("Invalid stageid.");
}

// Get stage info
$query = 'select stageno, name from projectstage where `id` = ' . $stageid;

if ($result = $mysqli->query($query)) {

    $row = $result->fetch_row();

    $stageno = $row[0];
    $stagename = $row[1];

    $result->close();
}

// Top Line
if ($dc === "- Select/All -") {

    $discpiplinecode = 'all';
    echo "<div class='topLine'>Displaying Drawing List for Stage: $stageno. $stagename</div>";

    // Get the list of discipline
    $query = "select disciplinecode,discipline from discipline order by id";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            // Create the discipline code array
            $disiplineX[] = array("dc" => $row[0], "dn" => $row[1]);
        }

        $result->close();
    } else {

        echo "Error: $mysqli->error";
    }

    $no_of_disiplines = count($disiplineX);
} else {

    // Get Disciplinecode
    $x            = explode("-", $dc);
    $discpiplinecode    = trim($x[0]);
    $dname        = trim($x[1]);

    echo "<div class='topLine'>Displaying Drawing List for Stage: $stageno. $stagename  and Discipline: $dname</div>";
}


// Get the list of blocks
$query = "select blockno, blockname from blocks where project_id = $projectid and active = 1 order by blockno";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_row()) {

        // Create the discipline code array
        $blockX[] = array("bno" => $row[0], "bname" => $row[1]);
    }

    $result->close();
} else {

    echo "Error: $mysqli->error";
}

$no_of_blocks = count($blockX);


// Display all other blocks sequentially
for ($i = 0; $i < $no_of_blocks; $i++) {

    $blockno     = $blockX[$i]["bno"];
    $blockname     = $blockX[$i]["bname"];

    Tabulate($projectid, $stageno, $discpiplinecode, $blockno, $blockname, $mysqli);
}

$mysqli->close();

// echo '</table><br>';

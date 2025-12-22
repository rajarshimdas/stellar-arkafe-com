<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 10-Dec-2007				                |
| Updated On: 20-Dec-2007				                |
+-------------------------------------------------------+
*/
?>
<style>
    .tabulation tr {
        height: 30px;
    }
</style>
<?php
include('foo/t2DWGid.php');

/* Input Variables */
$dc = $_GET["dc"];
if (!$dc) {
    die('Error: Discipline code not available...');
} else {
    echo "Displaying Drawing List for $dc discipline.<br><br>";
}

//echo "RoleID: $roleid<br>";

// $mysqli = cn1();

// Get the disciplinecode for the selected discipline
$sql = "select disciplinecode from discipline where discipline = '$dc'";

if ($result = $mysqli->query($sql)) {

    $row     = $result->fetch_row();
    $dcode     = $row[0];

    /* free result set */
    $result->close();
}

// Get the list of blocks for this project
$sql = "select blockno, blockname from blocks where project_id = $projectid and active = 1 order by blockno";
//echo "<br>SQL: $sql<br>";

if ($result = $mysqli->query($sql)) {

    $no_of_blocks = 0;
    while ($row = $result->fetch_row()) {

        $blocks[] = array("blockno" => $row[0], "blockname" => $row[1]);
        $no_of_blocks++;
    }

    $result->close();
} else {

    echo "Error: $mysqli->error";
}

/* Display Masterplan first
for ($i = 0; $i <= $no_of_blocks; $i++) {

    $bno 	= $blocks[$i]["blockno"];
    $bname 	= $blocks[$i]["blockname"];

    if ($bno == 'MP') {
        Displaylist($bno, $bname, $blocks, $dcode, $projectid, $roleid, $mysqli);
    }

}
*/

/* Display all other blocks */
for ($i = 0; $i <= $no_of_blocks; $i++) {

    $bno    = $blocks[$i]["blockno"];
    $bname  = $blocks[$i]["blockname"];

    /*
    if ($bno != 'MP') {
        Displaylist($bno, $bname, $blocks, $dcode, $projectid, $roleid, $mysqli);
    }
    */
    Displaylist($bno, $bname, $blocks, $dcode, $projectid, $roleid, $mysqli);
}

$mysqli->close();


/*
+-----------------------------------------------------------------------+
| Function: Display block+discipline specific drawing list              |
+-----------------------------------------------------------------------+
*/
function Displaylist($blockno, $blockname, $blocks, $dcode, $projectid, $roleid, $mysqli)
{

    /* echo "$blockno - $blockname ($dcode)<br>"; */
    $sql = "select
                id,
                CONCAT(dwgidentity,'-',disciplinecode,'-',unit) as sheetno,
                part
            from
                dwglist
            where
                project_id = $projectid and
                dwgidentity = '$blockno' and
                disciplinecode = '$dcode' and
                active = 1
            order by
                sheetno";
    //echo "SQL: $sql<br>";

    if ($result = $mysqli->query($sql)) {

        $no = 1; // Serial number that will appear in the dwglist.
        $row_cnt = $result->num_rows;
        //echo "<br>Row count: $row_cnt";

        if ($row_cnt > 0) {

            echo "<span style='font-weight:bold;'>$blockno - $blockname</span>";

            $dx = new DWGid($projectid, $roleid);
            /*DWGListTableHeader(
                            1$ShowNo,
                            1$ShowRevNo,
                            1$ShowStage,
                            $ShowSchematic,
                            $ShowCommitdt,
                            $ShowR0Date,
                            1$ShowGFC,
                            1$ShowLastIssued,
                            $ShowActionBy,
                            $ShowRemark,
                            $TableWidth
                            )
            */
            $dx->DWGListTableHeader(1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 850);


            while ($row = $result->fetch_row()) {

                // Drawing id
                $dwglist_id = $row[0];

                // Get details of this drawing
                $a = $dx->GetDWGDetails($dwglist_id, $mysqli);

                // Display the row in the table
                $dx->DWGListTableRow($no, $a, "Yes", "t2xsummary-dwg");

                $no++;
            }

            echo "</table><br><br>";
            unset($dx);
        }

        $result->close();
    } else {

        echo "Error: $mysqli->error";
    }
}

?>
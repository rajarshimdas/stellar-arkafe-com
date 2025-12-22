<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 15-Feb-08					|
| Updated On:						|
+-------------------------------------------------------+
| Loop function						|
+-------------------------------------------------------+
*/

function Tabulate($projectid,$stageno,$dcode,$bno,$bname,$mysqli) {


    //echo "<br>ProjID: $projectid Stage: $stageno Discipline: $dcode";


    // Get the drawing list array
    if ($dcode === "all") {

        // Display all disciplines
        $dx = " newstage = $stageno ";

    } else {

        // Display selected discipline
        $dx = " disciplinecode = '$dcode' and newstage = $stageno ";

    }

    // Generate query statement
    $query = "select
                id,
                CONCAT(dwgidentity,'-',disciplinecode,'-',unit,part) as sheetno
            from
                dwglist 
            where
                project_id = $projectid and
                dwgidentity = '$bno' and
                $dx
                and active = 1
            order by
                sheetno";

    // echo $query.'<br>';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            // Create the drawing list array
            $DWGidX[] = array("id" => $row[0]);

        }

        $result->close();

    } else {

        echo "Error: $mysqli->error";

    }

    // Count the drawing list array
    $no_of_drawings = count($DWGidX);

    if ($no_of_drawings > 0) {

        // Title
        echo "<div style='font-weight:bold;'>$bno - $bname</div>";

        // Generate the rows
        // $dx = new DWGid($projectid,$roleid);
        $dx = new DWGid($projectid,15); // Hack | 16-Feb-2024

        $dx->DWGListTableHeader(1,1,0,0,0,0,1,1,0,0,850);
        $no = 1;

        for ($i = 0; $i < $no_of_drawings; $i++) {

            $DWGid = $DWGidX[$i]["id"];
            //echo "<br>dwgid: $dwglist_id";

            $a = $dx->GetDWGDetails($DWGid,$mysqli);
            $dx->DWGListTableRow($no,$a);
            $no++;

        }

        // Close the table
        echo "</table><br>";

    }

}

// coupling test
function test69($bno,$bname) {
    echo "<br>$bno - $bname";
}

?>

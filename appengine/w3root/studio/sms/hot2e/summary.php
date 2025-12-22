<?php /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 23-Jan-2007				|
| Updated On: 13-Feb-2012				|
+-------------------------------------------------------+
| Functions in summary.php				|
+-------+---------------------------+-------------------+
| no.	| Functions:                | Returns:		|
+-------+---------------------------+-------------------+
| 1.	| GenerateDisciplineSummary | Array holding all	|
|	|                           | related data.	|
+-------+---------------------------+-------------------+
| 2.	| DisplayROWinTable         | Displays the ROW 	|
|	|                           | in the summary	|
|	|                           | table.		|
+-------+---------------------------+-------------------+
| 3.	| SQL69                     | Subfunction of 	|
|	|                           | function 1.	|
+-------+---------------------------+-------------------+
*/

function GenerateDisciplineSummary ($blocks,$blockX,$discipline,$projectid,$mysqli) {

    $grandtotal_gfc = 0;
    //echo '<br>$blocks: '.$blocks.' $blockX: '.$blockX.' $discipline: '.$discipline.' $projectid: '.$projectid;

    // Reset variables
    $s1no 	= 0;
    $s2no 	= 0;
    $s3no 	= 0;
    $s4no 	= 0;
    $s5no 	= 0;
    $s6no 	= 0;
    $s7no 	= 0;
    $s8no 	= 0;
    // $s9no 	= 0;

    $total	= 0;
    $total_gfc 	= 0;

    /* Syntax for the use of $blocks and $blockX
    +---------------+---------------+---------------+---------------+
    | Variables     |               |               |               |
    +---------------+---------------+---------------+---------------+
    | $blocks       | All           | Single        | Multiple      |
    +---------------+---------------+---------------+---------------+
    | $blockX       | -             | blockno       | array         |
    +---------------+---------------+---------------+---------------+
    */

    // Case 1: Project Summary
    if ($blocks === "All") {

        $blockSQL = " ";
        $x[] = SQL69($projectid,$discipline,$blockSQL,$mysqli);

        $s1no 		= $x[0]["s1no"];
        $s2no 		= $x[0]["s2no"];
        $s3no 		= $x[0]["s3no"];
        $s4no 		= $x[0]["s4no"];
        $s5no 		= $x[0]["s5no"];
        $s6no 		= $x[0]["s6no"];
        $s7no 		= $x[0]["s7no"];
        $s8no 		= $x[0]["s8no"];
        // $s9no 	= $x[0]["s9no"];

        $total		= $x[0]["total"];
        $total_gfc 	= $x[0]["total_gfc"];

        //echo "<br>s1no: $s1no s2no: $s2no s3no: $s3no s4no: $s4no total_gfc: $total_gfc";

    }

    // Case 2: Block Summary
    if ($blocks === "Single") {

        $blockSQL = " and dwgidentity = '$blockX' ";
        $x[] = SQL69($projectid,$discipline,$blockSQL,$mysqli);

        $s1no 		= $x[0]["s1no"];
        $s2no 		= $x[0]["s2no"];
        $s3no 		= $x[0]["s3no"];
        $s4no 		= $x[0]["s4no"];
        $s5no 		= $x[0]["s5no"];
        $s6no 		= $x[0]["s6no"];
        $s7no 		= $x[0]["s7no"];
        $s8no 		= $x[0]["s8no"];
        //$s9no 		= $x[0]["s9no"];

        $total		= $x[0]["total"];
        $total_gfc 	= $x[0]["total_gfc"];

    }

    // Case 3: Phase Summary
    if ($blocks === "Multiple") {

        $count = count($blockX);
        $x = 0;

        // Loop through all the blocks
        for ($i = 0; $i < $count; $i++) {

            //echo "<br>$i. block: $blockX[$i]";
            unset($x); // Reset the array

            $blockSQL = " and dwgidentity = '$blockX[$i]' ";
            $x[] = SQL69($projectid,$discipline,$blockSQL,$mysqli);

            $s1no 	= $s1no + $x[0]["s1no"];
            $s2no 	= $s2no + $x[0]["s2no"];
            $s3no 	= $s3no + $x[0]["s3no"];
            $s4no 	= $s4no + $x[0]["s4no"];
            $s5no 	= $s5no + $x[0]["s5no"];
            $s6no 	= $s6no + $x[0]["s6no"];
            $s7no 	= $s7no + $x[0]["s7no"];
            $s8no 	= $s8no + $x[0]["s8no"];
            //$s9no 	= $s9no + $x[0]["s9no"];

            $total	= $total + $x[0]["total"];
            $total_gfc 	= $total_gfc + $x[0]["total_gfc"];

        }

    }

    /* Calulate grand total
    $s2no = $s1no + $s2no;
    $s3no = $s2no + $s3no;
    $s4no = $s3no + $s4no;
    $s5no = $s4no + $s5no;
    $s6no = $s5no + $s6no;
    $s7no = $s6no + $s7no;
    $s8no = $s7no + $s8no;
    $s9no = $s8no + $s9no;
    */

    // Percentage completed
    if ($total_gfc > 0) {
        $ex = $total_gfc * 100;
        $px = $ex / $total;
        $px = round($px);
        $percent = "(" .$px. "%)";
    }

    // GFC Status column
    $gfc_stats = $total_gfc."&nbsp;".$percent;

    // Grand total of gfc
    $grandtotal_gfc = $grandtotal_gfc + $total_gfc;

    // Return Resultset
    $jan23rd2003 = array(
            "disciplinecode" 	=> $discipline,
            "stage1" 		=> $s1no,
            "stage2" 		=> $s2no,
            "stage3" 		=> $s3no,
            "stage4" 		=> $s4no,
            "stage5"            => $s5no,
            "stage6" 		=> $s6no,
            "stage7" 		=> $s7no,
            "stage8" 		=> $s8no,
            
            "gfc"	 	=> $gfc_stats,
            "total"	 	=> $total,
            "total_gfc"		=> $total_gfc
    );

    //echo "<br>Discipline: $discipline Stage1: ".$jan23rd2003[0]["stage1"]. " GFC: $gfc_stats";

    return $jan23rd2003;

}



function DisplayROWinTable ($jan23rd2003,$no,$atag,$mysqli) {

    // Get the discipline from disciplinecode...
    $query = "select discipline from discipline where disciplinecode = '".$jan23rd2003["disciplinecode"]."'";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $discipline = $row[0];
        $result->close();

    }

    // Display the ROW
    echo "<tr class='dataRowCell'>
            <td class='dataRowCell1' align='center'>$no</td>";

    if ($atag > 0) {
        echo "<td class='dataRowCell2a' style='text-align:left'><a href='project.cgi?a=t2xsummary-list&dc=$discipline'>$discipline</a></td>";
    } else {
        echo "<td class='dataRowCell2' style='text-align:left'>$discipline</td>";
    }
    
    echo	"<td class='dataRowCell2'>".$jan23rd2003["stage1"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage2"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage3"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage4"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage5"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage6"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage7"]."</td>
                <td class='dataRowCell2'>".$jan23rd2003["stage8"]."</td>

                <td class='dataRowCell2' align='center'>".$jan23rd2003["gfc"]."</td>
                <td class='dataRowCell2' align='center'>".$jan23rd2003["total"]."</td>
		</tr>";

}

function SQL69 ($projectid,$discipline,$blockSQL,$mysqli) {

    $s1no = 0;
    $s2no = 0;
    $s3no = 0;
    $s4no = 0;
    $s5no = 0;
    $s6no = 0;
    $s7no = 0;
    $s8no = 0;
    // $s9no = 0;

    $total = 0;
    $total_gfc 	= 0;

    $sql69 = "select
                newstage,
                r0issuedflag
            from
                dwglist
            where
                project_id = $projectid and
                disciplinecode = '$discipline' and
                active = 1
            $blockSQL";

    //echo "<br>sql: $sql69";

    if ($result69 = $mysqli->query($sql69)) {

        /* Total number of drawings 
        $total = $result69->num_rows;
        */
        /* Calculate stagewise number of drawings */
        while ($row69 = $result69->fetch_row()) {

            $stage = $row69[0];

            if ($stage == 1) $s1no++;
            if ($stage == 2) $s2no++;
            if ($stage == 3) $s3no++;
            if ($stage == 4) $s4no++;
            if ($stage == 5) $s5no++;
            if ($stage == 6) $s6no++;
            if ($stage == 7) $s7no++;
            if ($stage == 8) $s8no++;
            //if ($stage == 9) $s9no++;

            $total++;
            if ($row69[1] > 0) $total_gfc++;

        }

        $result69->close();
    } else {
        echo "Error[69]: $mysqli->error";
    }

    //echo "<br>total_gfc: $total_gfc";

    // resultset
    $elation = array (

            "s1no" 	=> $s1no,
            "s2no" 	=> $s2no,
            "s3no" 	=> $s3no,
            "s4no" 	=> $s4no,
            "s5no" 	=> $s5no,
            "s6no" 	=> $s6no,
            "s7no" 	=> $s7no,
            "s8no" 	=> $s8no,
            //"s9no" 	=> $s9no,

            "total"	=> $total,
            "total_gfc" => $total_gfc
        
    );

    return $elation;
}

?>
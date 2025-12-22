<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
function getStageArray($mysqli) {

    $query = 'select `id`,`name`,`stageno`,`sname` from projectstage where active = 1 order by stageno';

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {

            $stageX[] = array(
                "id" => $row[0], 
                "stage" => $row[1], 
                "stageno" => $row[2],
                "sname" => $row[3],
            );

        }

        $result->close();
    }
    
    return $stageX;
    
}

?>

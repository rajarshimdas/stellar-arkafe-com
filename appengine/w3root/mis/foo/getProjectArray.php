<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 03-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/

function getProjectArray($mysqli)
{

    $query = "select 
                id, projectname, jobcode 
            from 
                projects 
            where 
                domain_id = 2 and active = 1 
            order by 
                projectname";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $x[] = array("id" => $row[0], "pn" => $row[1], "jc" => $row[2]);
        }

        $result->close();
    }

    return $x;
}

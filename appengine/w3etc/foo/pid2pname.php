<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 01-Feb-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
*/
function pid2pname($pid, $mysqli)
{

    $query = 'select projectname, jobcode from projects where id = ' . $pid;

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();

        if ($pid > 150) {
            $pname = $row[1] . ' - ' . $row[0];
        } else {
            $pname = $row[0];
        }

        $result->close();
    }

    return $pname;
}

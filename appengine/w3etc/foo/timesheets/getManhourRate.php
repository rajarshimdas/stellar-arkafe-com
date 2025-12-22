<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 29-Jun-2012				                |
| Updated On:           				                |
+-------------------------------------------------------+
| $ver = 'current' is allowed                           |
+-------------------------------------------------------+
*/

function getManhourRate ($hrgroupX, $pid, $ver, $mysqli) {

    // Current Version
    if ($ver === 'current') {

        $query = 'select version from timeestimateversion where project_id = '.$pid.' order by dtime desc';

        if ($result = $mysqli->query($query)) {

            $row = $result->fetch_row();
            $ver = $row[0];
            $result->close();

        }

    }

    unset($rateX);
    
    // Read data from 'timeestimaterate' table
    $row_cnt = 0;

    $query = 'select
                hrgroup_id,
                rateperhour
            from
                timeestimaterate
            where
                project_id = '.$pid.' and
                version = "'.$ver.'"
            order by 
                hrgroup_id';

    // die($query);

    if ($result = $mysqli->query($query)) {

        $row_cnt = $result->num_rows;

        while ($row = $result->fetch_row()) {

            $hrgroup_id             = $row[0];
            $manhourlyrate          = $row[1];
            $rateX[$hrgroup_id]     = $manhourlyrate;

        }

        $result->close();
    }

    // If there were not fee estimate loaded, get the default rates.
    if ($row_cnt < 1) {

        $query = 'select id, defaultrate from userhrgroup';

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {

                $hrgroup_id             = $row[0];
                $manhourlyrate          = $row[1];

                // Store in Array
                $rateX[$hrgroup_id]     = $manhourlyrate;

            }

            $result->close();
        }
    }

    return $rateX;
}

?>

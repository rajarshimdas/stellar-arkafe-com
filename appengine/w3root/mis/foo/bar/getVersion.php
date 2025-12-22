<?php

function getVersion($pid, $mysqli) {
    
    $query = 'select version from timeestimateversion where project_id = '.$pid.' order by dtime desc limit 1';
    //echo "Q: ".$query;

    if ($result = $mysqli->query($query)) {

        /* fetch object array */
        $row = $result->fetch_row();

        $x = $row[0];

        /* free result set */
        $result->close();

    }

    return $x;

}
?>
<?php

$tid = isset($_POST["tid"]) ? $_POST["tid"] : 0;

$query = "update `task` set `status_last_month` = '100' where `id` = '$tid'";

$mysqli = cn2();

if ($mysqli->query($query)) {
    rdReturnJsonHttpResponse(
        '200',
        ['T', 'Done']
    );
} else {
    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Error']
    );
}

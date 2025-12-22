<?php

$tid = isset($_POST["tid"]) ? $_POST["tid"] : 0;
$flag = $_POST["flag"];


if ($flag < 1) {
    $btnOnhold = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/taskHold.png" onclick="taskOnhold(\'' . $tid . '\', 1)" title="Set on hold">';
} else {
    $btnOnhold = '<img class="fa5button" src="' . BASE_URL . 'da/fa5/taskOnhold.png" onclick="taskOnhold(\'' . $tid . '\', 0)" title="Remove hold">';
}



$query = "update `task` set `onhold` = '$flag' where `id` = '$tid'";


$mysqli = cn2();


if ($mysqli->query($query)) {
    rdReturnJsonHttpResponse(
        '200',
        ['T', $btnOnhold]
    );
} else {
    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Error']
    );
}

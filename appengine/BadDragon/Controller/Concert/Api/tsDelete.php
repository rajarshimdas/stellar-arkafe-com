<?php

$tsId = $_POST['tsId'];     // timesheet id

$query = "UPDATE `timesheet` SET `active` = '0' WHERE `id` = '$tsId'";

$mysqli = cn2();

if ($mysqli->query($query)){

    rdReturnJsonHttpResponse(
        '200',
        ["T"]
    );
} else {

    rdReturnJsonHttpResponse(
        '200',
        ["F", "Delete timesheet failed"]
    );
}
<?php

$flag = $_POST['flag'];

$_SESSION['sysShowDeletedUser'] = $flag;

rdReturnJsonHttpResponse(
    '200', ["T"]
);
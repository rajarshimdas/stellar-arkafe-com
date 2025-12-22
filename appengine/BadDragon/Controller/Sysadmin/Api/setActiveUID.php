<?php

if (isset($_POST["userId"])) {
    
    $_SESSION['activeUID'] = $_POST['userId'];

    rdReturnJsonHttpResponse(
        '200',
        ['T', 'ok']
    );
} else {

    rdReturnJsonHttpResponse(
        '200',
        ['F', 'Unable to set activeUID']
    );
}

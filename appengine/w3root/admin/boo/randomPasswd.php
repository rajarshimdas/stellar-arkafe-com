<?php

//echo randomPasswd(5);

function randomPasswd ($passwdLength) {

    $seed = rand(10000,99999);
    $md5seed = md5($seed);
    $startPoint = rand(0,9);
   
    $passwd = substr($md5seed, $startPoint, $passwdLength);
    // echo "<br>Password: $passwd";

    return $passwd;

}

?>

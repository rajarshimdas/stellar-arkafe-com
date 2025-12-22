<?php

function startSession($base_url, $cookieName, $w3path, $now)
{

    /* Start sessions */
    session_save_path($w3path . 'w3filedb/session');
    session_name($cookieName);

    session_start();
    $sessionid = session_id();

    /* Are session variables available */
    if (isset($_SESSION["loginexp"])) {
        /* Has the session expired */
        $loginexp = $_SESSION["loginexp"];
        if (($loginexp + 5) < ($now + 5)) {
            header("Location:" . $base_url . "login/index.cgi?e=session-time-out");
            die;
        }
    } else {
        // This is a new session. Redirect to login page.
        header("Location:" . $base_url . "login/");
        die;
    }

    // echo $cookieName.' | '.$LoginTimeOut.'<br>';
    // echo $_SESSION['loginexp'] .' > '. $now;   

    /* Check all variables required for authentication are available */
    if (!$sessionid) {
        header("Location:" . $base_url . "login/index.cgi?e=session-not-valid");
        die;
    }

    return $sessionid;
}

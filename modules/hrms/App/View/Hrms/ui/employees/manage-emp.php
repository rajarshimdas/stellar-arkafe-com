<?php

/* If you want to return to same page 
if ($route->parts[5]) {
    $thisUId = (int)$route->parts[5];
    $_SESSION['activeUserId'] = $thisUId;
} else {
    $thisUId = $_SESSION['activeUserId'];
}
*/

$thisUId = empty($route->parts[5]) ? 0 : $route->parts[5];

if ($thisUId < 1)
    require_once __DIR__ . '/manage.php';
else
    require_once __DIR__ . '/manage-emp-rec.php';

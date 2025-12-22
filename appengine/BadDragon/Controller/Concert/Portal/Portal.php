<?php
/*
+-------------------------------------------------------+
| Portal                                                |
+-------------------------------------------------------+
*/

/*
$tabMenu  = [
    'tasks'     => [
        # [ URL Link,   Menu Name,      role_id]
        ['snapshot',    'Snapshot',     100],
        ['add',         'Add',          12],
        ['import',      'Import',       12],
        ['update',      'Update',       12],
        ['stats',       'Stats',        12],
    ],
    'timesheet'    => [
        ['my-tasks',    'My Tasks',     100],
        ['overheads',   'Overheads',    100],
        ['log',         'Day Logs',     100],
        ['graphs',      'Graphs',       100],
    ],

];
*/

$tabMenu = $tsPortalTabMenu; // From LocalSettings.php

// Tab > Menu
if (isset($route->parts[3])) {
    $page = $route->parts[3];
} elseif (isset($_SESSION['tab-' . $route->method])) {
    $page = $_SESSION['tab-' . $route->method];
} else {
    $page = $tabMenu[$route->method][0][0];
};

// Set session variable
$_SESSION['tab-' . $route->method] = $page;

// Load Method Controller
$controller = BD . "Controller/Concert/Portal/" . $route->method . ".php";
# echo $controller;

if (file_exists($controller)) {
    require_once $controller;
} else {
    echo "Controller [" . $controller . "] does not exists.";
}

// Page Title
$x = $tabMenu[$route->method];
for ($i = 0; $i < count($x); $i++) {
    if ($x[$i][0] == $page) $page_title = $x[$i][1];
}

// Generate Page
$title          = ucfirst($route->parts[2]) ." | " . $page_title;
$moduleName     = "Task Management";
// $returnURL   = ["MIS", BASE_URL . 'mis/concert.cgi'];
// $view        = 'Module/Concert/dashboard';

require BD . 'View/Module/Concert/generatePage.php';

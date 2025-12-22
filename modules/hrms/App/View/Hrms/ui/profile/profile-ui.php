<?php
$users = bdGetEmployeeDataById($mysqli);
$user = $users[$uid];

require_once W3APP . '/View/Widgets/_profile.php';
?>

<div id="nav">
    <?php

    $navTabLinks = [
        ['info',                'Info',             100],
        ['qualifications',      "Qualifications",   100],
        ['skills',              'Skills',           100],
        ['projects',            'Projects',         100],
        ['team',                'Team',             100],
        ['leave',               'Leave',            100],
        ['docs',                'Documents',        100],

    ];

    // Active link
    $activeLink = bdActiveLink($activeTab, $navTabLinks, $route->parts);

    echo bdNavTabLinks($activeTab, $activeLink, $navTabLinks, $route->parts);
    ?>
</div>
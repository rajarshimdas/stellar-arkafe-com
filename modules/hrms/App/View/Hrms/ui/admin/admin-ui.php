<div id="nav">
    <?php

    $navTabLinks = [
        ['employees',   "Employees",    100],
        ['stats',       "Stats",        10],
        ['mod',         "MOD",          100],
    ];
    
    // Active link
    $activeLink = bdActiveLink($activeTab, $navTabLinks, $route->parts);

    echo bdNavTabLinks($activeTab, $activeLink, $navTabLinks, $route->parts);
    ?>
</div>
<?php

$users = bdGetEmployeeDataById($mysqli);
$user = $users[$uid];

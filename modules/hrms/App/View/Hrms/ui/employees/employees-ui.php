<div id="nav">
    <?php

    $navTabLinks = [
        ['dashboard',       "Dashboard",    100],
        ['manage',          "Manage",       10],
        ['calendar',        "Calendar",     10],
    ];
    
    // Active link
    $activeLink = bdActiveLink($activeTab, $navTabLinks, $route->parts);

    echo bdNavTabLinks($activeTab, $activeLink, $navTabLinks, $route->parts);
    ?>
</div>
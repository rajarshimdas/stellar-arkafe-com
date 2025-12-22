<div id="nav">
    <?php

    $navTabLinks = [
        ['pto',             "PTO",              100],
        ['summary',         'Summary',          100],
        ['leaverecords',    'Leave Records',    100],
    ];
    
    // Active link
    $activeLink = bdActiveLink($activeTab, $navTabLinks, $route->parts);

    echo bdNavTabLinks($activeTab, $activeLink, $navTabLinks, $route->parts);
    ?>
</div>
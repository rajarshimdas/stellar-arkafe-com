<?php
$subs = bdGetSubordinatesAndMe($uid, cn0());
// var_dump($subs);
$subs_co = empty($subs) ? 0 : count($subs);
?>
<div id="nav">
    <?php

    $navTabLinks = [
        ['calendar',    "Calendar",     100],
        ['myleaves',    "My Leaves",    100],
        ['log',         "Log",          100],
        ['policy',      "Policy",       100],
        // ['logs',            "Logs",        10],
    ];

    ## Manage Link
    ##
    if ($subs_co > 1) {
        $manageLink = ['manage', "Manage", 10];
        array_unshift($navTabLinks, $manageLink);
    }
  
    // Active link
    $activeLink = bdActiveLink($activeTab, $navTabLinks, $route->parts);

    echo bdNavTabLinks($activeTab, $activeLink, $navTabLinks, $route->parts);
    ?>
</div>
<?php // var_dump($navTabLinks);
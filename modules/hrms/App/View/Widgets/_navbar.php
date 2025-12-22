<?php

// Active Tab
if (!empty($route->parts[3])) {
    $activeTab = $route->parts[3];
    $_SESSION['activeTab'] = $activeTab;
} elseif ($_SESSION['activeTab']) {
    $activeTab = $_SESSION['activeTab'];
} else {
    $activeTab = 'leaves';
}

// echo $bdUserTypeName;  ## LocalSettings.php
// var_dump($_SESSION);
// echo $activeTab;
?>
<style>
    #tabs a,
    #tabs span {
        border-radius: 6px 6px 0px 0px;
        /* margin-right: 1px; */
    }
</style>
<div id="navtab">
    <div id="tabs">
        <ul>
            <?php

            foreach ($navtabs as $t):

                $url                = BASE_URL . 'aec/hrms/ui/' . $t[0];
                $tabName            = $t[1];
                $userTypeAccess     = $t[2];

                $AccessFlag         = 0;

                if (bdTabAccessFlag($t[0], $bdUserTypeName, $navtabs)):
            ?>
                    <li>
                        <a href="<?= $url ?>" <?= bdActiveTab($t[0], $activeTab) ?>>
                            <span><?= $tabName ?></span>
                        </a>
                    </li>
            <?php
                endif;
            endforeach;
            ?>
        </ul>
    </div>
</div>
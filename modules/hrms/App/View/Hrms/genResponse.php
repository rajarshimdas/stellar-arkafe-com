<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= 'HRMS | ' . ucfirst($route->parts[3]) ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/matchbox/favicon.png">
    <link href="<?= BASE_URL ?>aec/public/BadDragon.css" rel="stylesheet" type="text/css">
    <script src="<?= BASE_URL ?>aec/public/BadDragon.js" type="text/javascript"></script>
    <link href="<?= BASE_URL ?>aec/box/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- pageBanner -->
    <div style="background-color:var(--rd-nav-light);">
        <?php require_once W3APP . '/View/Widgets/_banner.php'; ?>
    </div>

    <!-- pageNavTabs -->
    <div style="background-color:var(--rd-nav-light);height:35px;">
        <?php require_once W3APP . '/View/Widgets/_navbar.php'; ?>
    </div>

    <!-- tabMenu|Form -->
    <div style="background-color:var(--rd-nav-dark);">
        <div style="width:100%;max-width:var(--rd-max-width);margin:auto;">
            <?php
            if (is_file(__DIR__ . '/ui/' . $activeTab . '/' . $activeTab . '-ui.php')) {
                require_once __DIR__ . '/ui/' . $activeTab . '/' . $activeTab . '-ui.php';
            } else {
                echo 'System error: Failed to load NavItem [' . $defaultNavItem[$activeTab] . '].';
            }
            ?>
        </div>
    </div>

    <!-- pageBody -->
    <div style="width:100%;max-width:var(--rd-max-width);margin:auto;">
        <?php
        if (bdTabAccessFlag($activeTab, $bdUserTypeName, $navtabs)) {
            if (is_file(__DIR__ . '/ui/' . $activeTab . '/' . $activeLink . '.php')) {
                require_once __DIR__ . '/ui/' . $activeTab . '/' . $activeLink . '.php';
            } else {
                echo 'System error: Failed to load activeLink View :: ui/' . $activeTab . '/' . $activeLink . '.php';
            }
        } else {
            echo "Access denied";
        }
        ?>
    </div>

    <!-- statusbar -->
    <?php require_once W3APP . '/View/Widgets/_statusbar.php'; ?>

    <!-- Message Dialog Box -->
    <?php require_once BD . '/Toolbox/dxMessageBox.php'; ?>

    <script type="text/javascript">
        const apiUrl = "<?= BASE_URL ?>aec/index.php";
        const uid = <?= $uid ?>

        function go2(url) {
            window.location = "<?= BASE_URL ?>" + url
        }
    </script>
</body>

</html>
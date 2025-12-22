<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $companyName . ' | Home' ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/img/favicon.png">
    <link href="<?= BASE_URL ?>aec/public/BadDragon.css" rel="stylesheet" type="text/css">
    <script src="<?= BASE_URL ?>aec/public/BadDragon.js" type="text/javascript"></script>
    <link href="<?= BASE_URL ?>aec/box/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- pageBanner -->
    <div style="background-color:var(--rd-nav-light);">
        <?php require_once W3APP . '/View/Hrms/widgets/_banner.php'; ?>
    </div>




    <?php require_once W3APP . '/View/Widgets/_statusbar.php'; ?>
    
    <script type="text/javascript">
        const apiURL = "<?= BASE_URL ?>aec/index.php";
        const uid = <?= $uid ?>
    </script>
</body>

</html>
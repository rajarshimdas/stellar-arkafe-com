<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link href='<?= $base_url ?>matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">
</head>

<body>
    <div class="windowBox2">
        <?php
        /* 
        +-----------------------------------------------+
        | Variables required and sample values          |
        +-----------------------------------------------+
        $title = "MIS | Timesheet Status";
        $moduleName = "MIS";
        $returnURL = ["MIS", BASE_URL.'mis/concert.cgi'];
        $view = 'timesheetFilledStatus';
        */
        require_once $w3root . '/studio/sms/foo/header.cgi';

        // Custom styles
        require_once BD.'View/Module/mis/Template/styleMIS.php';

        require_once BD . "View/$view.php";
        ?>
    </div>
</body>

</html>
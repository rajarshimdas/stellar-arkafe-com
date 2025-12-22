<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';
require_once BD . 'Controller/Common.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>matchbox/favicon.png">
    <!-- JQuery 3.6.1 -->
    <script src="<?= $base_url ?>matchbox/api/jquery.min.js"></script>
    <!-- Pure CSS | Yahoo -->
    <link href='<?= $base_url ?>matchbox/api/pure-css/pure-nr-min.css' rel='stylesheet' type='text/css'>
    <!-- Concert CSS -->
    <link href='<?= $base_url ?>matchbox/themes/cool/style.css' rel='stylesheet' type='text/css'>

    <script src="<?= $base_url ?>studio/home.js"></script>
</head>

<body style="background-color: var(--rd-login-background);padding:20px;">
    <div class="windowBox" style="background-color: white; padding:10px;text-align:center;">

        <?php
        //echo $loginname;

        if (!in_array(strtolower($loginname), $adminSwitchUser)) die;

        $users = bdGetUsersArray($mysqli);
        // rx($users);
        ?>
        <form action="index.cgi" method="post">
            <input type="hidden" name="a" value="user-api-adminSwitchUser">
            <select name="switchUser" id="switchUser" style="width: 200px;">
                <option value="0">-- Select User --</option>
                <?php
                foreach ($users as $u) {
                    if ($u['active'] > 0 && strtolower($u['loginname']) != 'ashok.patel') 
                        echo "<option value='" . $u['loginname'] . "'>" . $u['displayname'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Switch">
        </form>
    </div>
</body>

</html>
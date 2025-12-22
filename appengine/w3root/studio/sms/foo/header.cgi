<?php
/*
+-----------------------------------------------+
| Rajarshi Das                                  |
+-----------------------------------------------+
| Created On: 01-Jan-2007                       |
| Updated On: 15-Feb-2024                       |
+-----------------------------------------------+
| $returnURL = [ "name", "url" ];               |
+-----------------------------------------------+
*/
if (!isset($moduleName)) {
    die("Server Error: moduleName missing.");
}

/*
+-----------------------------------------------+
| Error Messages                                |
+-----------------------------------------------+
| 1.  mc -> Caution or Warning Messge           |
| 2.  me -> Fatal Error Message                 |
+-----------------------------------------------+
*/

$mc = (isset($_GET["mc"])) ? $_GET["mc"] : null;
$me = (isset($_GET["me"])) ? $_GET["me"] : null;

?>
<table style="background-color:white;text-align: left; width: 100%; height:50px; padding: 5px 0px;" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td style="width: 50px">
                <img src="<?= $base_url ?>da/arkafe-a-dark.png" alt="CONCERT" style="height:35px;">
            </td>
            <!--
            <td style="text-align:center; width: 115px; text-align:right;">
                <img src="<?= $avatar ?>" alt="Avatar" width="50px" height="50px" style="border-radius:50%;">
            </td>
            -->
            <td style="vertical-align: middle; width: 150px; padding-left:15px;">
                <div>
                    <div style="font-size:85%;color:cadetblue;"><?= $moduleName ?></div>
                    <div style="color:grey;font-size:80%"><?= $fullname ?></div>
                    <div style="font-size:75%;color:rgb(130, 130, 130);">
                        <style>
                            a.bnLink {
                                text-decoration: none;
                                color: gray;
                            }
                        </style>
                        <?php
                        echo '<a class="bnLink" href="' . $base_url . 'studio/home.cgi">Home</a>';
                        if (isset($returnURL)) {
                            echo '&nbsp;|&nbsp;<a class="bnLink" href="' . $returnURL[1] . '">' . $returnURL[0] . '</a>';
                        }
                        echo '&nbsp;|&nbsp;<a class="bnLink" href="' . $base_url . 'studio/logout.cgi">Logout</a>';
                        ?>
                    </div>
                </div>
            </td>

            <td valign="middle" style="width:50px;text-align:center;">
                &nbsp;
                <?php
                if ($mc)
                    echo "<img style='width: 34px; height: 34px;' alt='message-caution' src='foo/images/caution.png'>";
                if ($me)
                    echo "<img style='width: 34px; height: 34px;' alt='message-error' src='foo/images/warning.png'>";
                ?>
            </td>

            <td valign="middle" style="text-align: center;">
                <?php
                if ($moduleName == "Project Tracker") {
                    /*
                    +---------------------------------------+
                    |	Selected Project and Date           |
                    +---------------------------------------+
                    */
                    echo '<span style="font-size:100%;font-weight:bold;color:RGB(100,100,100);">' . $jobcode . ' - ' . $projectname . '</span>';
                    echo '<span style="font-size:75%;color:grey;"><br>' . date('D, d-M-y') . '</span>';
                }
                ?>
            </td>
            <td style="width:200px; text-align: right;" valign="top">
                <img src="<?= $base_url ?>da/logo-company.jpg" alt="Logo" style="height:45px;">
            </td>
        </tr>
    </tbody>
</table>
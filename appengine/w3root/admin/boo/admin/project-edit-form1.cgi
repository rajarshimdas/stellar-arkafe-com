<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 12-Aug-10 				                |
| Updated On: 20-Oct-10                                 |
+-------------------------------------------------------+
*/
// Session
if (isset($_GET["showDel"])) {
    $sysShowDeletedProj = $_GET['showDel'];
    $_SESSION["sysShowDeletedProj"] = $sysShowDeletedProj;
}

require BD . 'Controller/Projects/Projects.php';
$scope = bdProjectScope($mysqli);
//var_dump($scope);

$scopeIds = $scope[0]["id"];
for ($i = 1; $i < count($scope); $i++) {
    $scopeIds = $scopeIds . ',' . $scope[$i]["id"];
}

// Get the projects array
include 'boo/admin/project-functions.php';
$px = getProjectsArray($mysqli);


// Tabulate the projects
?>
<input type="hidden" id="scopeIds" value="<?= $scopeIds ?>">

<table width="100%" cellpadding="0" cellspacing='0'>
    <tr style="background: RGB(240,240,240); height: 40px;">
        <td width="78%">Select Project for Editing</td>
        <td>Deleted</td>
        <td width="10%">
            <?php
            if ($sysShowDeletedProj > 0) {
                echo '<a class="button" href="' . BASE_URL . 'admin/sysadmin.cgi?a=Project%20Edit&showDel=0" style="width:40px;">Hide</a>';
            } else {
                echo '<a class="button" href="' . BASE_URL . 'admin/sysadmin.cgi?a=Project%20Edit&showDel=1" style="width:40px;">Show</a>';
            }
            ?>

        </td>
    </tr>
    <?php
    for ($e = 0; $e < count($px); $e++) {

        $thisPID = $px[$e]["pid"];
        $displayProj = ($sysShowDeletedProj < 1 && $px[$e]["active"] < 1) ? 0 : 1;

        if ($displayProj > 0) {
    ?>

        <tr>
            <td colspan="2">
                <?php tabulateThisProject($px, $e, $mysqli, $scope); ?>
            </td>
            <td valign="top">
                <?php displayEditButton($thisPID, $rootFolderName); ?>
            </td>
        </tr>

    <?php
        }

    }

    ?>

</table>
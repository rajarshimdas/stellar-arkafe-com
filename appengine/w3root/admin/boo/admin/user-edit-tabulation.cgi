<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 19-Oct-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

/*
+-------------------------------------------------------+
| Get Users Array for Tabulation			            |
+-------------------------------------------------------+
*/
$usersX = getUsersArray($mysqli);
/*
+-------------------------------------------------------+
| Tabulate Header					                    |
+-------------------------------------------------------+
*/
?>

<table border="0" cellspacing="0" width="100%">
    <tr style="background: RGB(240,240,240); height: 40px;">
        <td width="78%">Select Team Member for Editing</td>
        <td>Deleted</td>
        <td width="10%">
            <?php
            if ($sysShowDeletedUser > 0) {
                echo '<a class="button" href="' . BASE_URL . 'admin/sysadmin.cgi?a=User%20Edit&showDel=0" style="width:40px;">Hide</a>';
            }else {
                echo '<a class="button" href="' . BASE_URL . 'admin/sysadmin.cgi?a=User%20Edit&showDel=1" style="width:40px;">Show</a>';
            }
            ?>    
        </td>
    </tr>

    <?php
    /*
    +-------------------------------------------------------+
    | Tabulate Data Rows                                    |
    +-------------------------------------------------------+
    */
    // include_once 'foo/arnav/config.php';
    include 'boo/admin/user-functions.php';
    $co = count($usersX);

    for ($i = 0; $i < $co; $i++) {

        $thisUID    = $usersX[$i]["user_id"];

        $showUser = ($sysShowDeletedUser < 1 && $usersX[$i]['active'] < 1) ? 0 : 1;

        if ($showUser > 0) {
    ?>
            <tr>
                <td colspan="2"><?php tabulateUserData($usersX, $i); ?></td>
                <td valign="top">
                    <a href="<?= BASE_URL ?>admin/sysadmin.cgi?a=User%20Edit&uid=<?= $thisUID ?>">
                        <img class="fa5button" src="/da/icons/edit.png">
                    </a>
                </td>
            </tr>
    <?php
        }   // End if
    }       // End for
    ?>

</table>

<?php
/*
+-------------------------------------------------------+
| Function: getUsersArray				                |
+-------------------------------------------------------+
*/
function getUsersArray($mysqli)
{

    $query = "select
                *
            from
                view_users";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $usersX[] = $row;
        }

        $result->close();
    }

    return $usersX;
}
?>
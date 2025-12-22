<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 19-Oct-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
*/

/* ----------------- Start ----------------------------*/
/*
+-------------------------------------------------------+
| Get User Details      				                |
+-------------------------------------------------------+
*/
$thisUID = $_GET['uid'];
include 'boo/admin/user-functions.php';
$userX = getUserData($thisUID, $mysqli);

// var_dump($userX);
/*
+-------------------------------------------------------+
| Generate the Form     				                |
+-------------------------------------------------------+
*/
generateForm($userX, $mysqli);
/* ----------------- End ------------------------------*/


/* view_users
+--------------------+-----------------------+------+-----+---------+-------+
| Field              | Type                  | Null | Key | Default | Extra |
+--------------------+-----------------------+------+-----+---------+-------+
| user_id            | mediumint(8) unsigned | NO   |     | 0       |       |
| domain_id          | smallint(5) unsigned  | NO   |     | NULL    |       |
| displayname        | varchar(50)           | NO   |     | NULL    |       |
| loginname          | varchar(50)           | NO   |     | NULL    |       |
| passwd             | varchar(50)           | NO   |     |         |       |
| fname              | varchar(45)           | NO   |     | -       |       |
| lname              | varchar(45)           | NO   |     | -       |       |
| emailid            | varchar(301)          | YES  |     | NULL    |       |
| gender             | varchar(5)            | NO   |     | NULL    |       |
| department_id      | int(10) unsigned      | NO   |     | 1       |       |
| departmentname     | varchar(100)          | NO   |     | NULL    |       |
| branch_id          | smallint(5) unsigned  | NO   |     | 1       |       |
| branchname         | varchar(250)          | NO   |     | NULL    |       |
| reports_to         | varchar(50)           | NO   |     | NULL    |       |
| active             | tinyint(1)            | YES  |     | NULL    |       |
| designation        | varchar(50)           | NO   |     | NULL    |       |
| userhrgroup_id     | tinyint(3) unsigned   | NO   |     | NULL    |       |
| hrgroup            | varchar(45)           | NO   |     | NULL    |       |
| reports_to_user_id | int(10) unsigned      | NO   |     | NULL    |       |
+--------------------+-----------------------+------+-----+---------+-------+
*/


function generateForm($userX, $mysqli)
{
    $userX = $userX[0]; // Hack
?>

    <form action="sysadmin.cgi" method="POST">
        <input type="hidden" name="a" value="User Edit">
        <input type="hidden" name="x" value="user-edit">
        <input type="hidden" name="thisUID" value="<?= $userX["user_id"] ?>">

        <table class="formTBL" style="background:#ffe4bb">
            <tr>
                <td colspan="2">
                    <?php echo $userX["displayname"]; ?>
                </td>
            </tr>
            <tr>
                <td width="35%">Loginname (non-editable)</td>
                <td>
                    <input type="text" name="lx" value="<?php echo $userX["loginname"]; ?>" readonly style="width:100%;background: RGB(250,250,250)">
                </td>
            </tr>
            <tr>
                <td>Display Name</td>
                <td>
                    <input type="text" name="dn" value="<?php echo $userX["displayname"]; ?>" style="width: 100%">
                </td>
            </tr>
            <tr>
                <td>Branch</td>
                <td>
                    <?php
                    include 'boo/admin/comboBranch.php';
                    comboBranch($userX["branchname"], $userX["branch_id"], $mysqli);
                    ?>
                </td>
            </tr>
            <!--
            <tr>
                <td>Department</td>
                <td>
                    <?php
                    /*
                    include 'boo/admin/comboDepartment.php';
                    comboDepartment($userX["dept_nm"], $userX["dept_id"], $mysqli);
                    */
                    ?>
                    <input type="hidden" name="did" value="3">
                    <input type="text" name="dummy" value="Design Studio (Readonly)" readonly>
                </td>
            </tr>
            -->
            <input type="hidden" name="did" value="3">


            <!-- HR Group (17-Jan-2012) -->
            <tr>
                <td>HR Group</td>
                <td>
                    <?php
                    include('boo/admin/hrGroup.php');
                    $hrgroup = $userX["hrgroup"];
                    $hrgroup_id = $userX["userhrgroup_id"];
                    hrGroup($hrgroup, $hrgroup_id, $mysqli);
                    ?>
                </td>
            </tr>

            <tr>
                <td>Reporting Manager</td>
                <td>
                    <?php
                    $reports_to = ($userX["reports_to_user_id"] > 1) ? $userX["reports_to"] : "-- Select --";
                    include('boo/admin/comboUsers.php');
                    comboUsers($reports_to, $userX["reports_to_user_id"], $mysqli, 'rm_uid');
                    ?>
                </td>
            </tr>
            <!--
            <tr>
                <td>Monthly Salary</td>
                <td>
                    <input type="number" step=".01" name="salary" id="salary" value="<?= ($userX["salary"] / 100) ?>">
                </td>
            </tr>

            <tr>
                <td>Annual Incentives</td>
                <td>
                    <input type="number" step=".01" name="incentives" id="incentives" value="<?= ($userX["incentives"] / 100) ?>">
                </td>
            </tr>
            -->
            <tr>
                <td>Date of Joining</td>
                <td>
                    <?php
                    if ($userX['doj'] != 'NA') {
                        $x = 'id="doj" value="' . $userX['dt_doj'] . '"';
                    } else {
                        $x = ' id="doj"';  
                    }
                    ?>
                    <input type="date" name="doj" <?= $x ?>>
                </td>
            </tr>

            <tr>
                <td>Date of Exit</td>
                <td>
                    <?php
                    if ($userX['doe'] != 'NA') {
                        $x = 'id="doe" value="' . $userX['dt_doe'] . '"';
                    } else {
                        $x = ' id="doe"';  
                    }
                    ?>
                    <input type="date" name="doe" <?= $x ?>>
                </td>
            </tr>

            <tr class="formNote">
                <td>Note:</td>
                <td>Deleted User can be activated by resetting the Password</td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" name="go" value="Edit" style="width:80px">
                    <input type="submit" name="go" value="Cancel" style="width:80px">
                </td>
            </tr>
        </table>

    </form>


<?php
}
?>
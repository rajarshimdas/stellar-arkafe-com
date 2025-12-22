<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 20-Oct-10 				|
| Updated On:                                           |
+-------------------------------------------------------+
| * getUserData                                         |
| * tabulateUserData                                    |
+-------------------------------------------------------+
*/

/*
#########################################################
# Function: getUserData 				#
#########################################################
*/
function getUserData($uid, $mysqli)
{
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

    $query = "select * from view_users where user_id = $uid";

    $result = $mysqli->query($query);

    while ($row = $result->fetch_assoc()) {
        $userX[] = $row;
    }

    return $userX;
}



/*
+-------------------------------------------------------+
| Function: tabulateUserData				            |
+-------------------------------------------------------+
 * $ux  - User Data array
 * $e   - Counter
*/
function tabulateUserData($ux, $e)
{
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
| designation        | varchar(50)           | NO   |     | NULL    |       |
| userhrgroup_id     | tinyint(3) unsigned   | NO   |     | NULL    |       |
| hrgroup            | varchar(45)           | NO   |     | NULL    |       |
| reports_to_user_id | int(10) unsigned      | NO   |     | NULL    |       |
| reports_to         | varchar(50)           | NO   |     | NULL    |       |
| avatar             | varchar(150)          | NO   |     | -       |       |
| salary             | int(10) unsigned      | NO   |     | 0       |       |
| incentives         | int(10) unsigned      | NO   |     | 0       |       |
| doj                | varchar(38)           | YES  |     | NULL    |       |
| doe                | varchar(38)           | YES  |     | NULL    |       |
| active             | tinyint(1)            | NO   |     | 1       |       |
+--------------------+-----------------------+------+-----+---------+-------+
*/

    if ($ux[$e]["active"] > 0) $bgcolor = "cadetblue";
    else $bgcolor = "RGB(220,220,220)";
    // if ($ux[$e]["gender"] === "M") $gender = 'Male'; else $gender = 'Female';
    if ($ux[$e]["active"] > 0) $active = "Yes";
    else $active = "No";

    $reports_to = ($ux[$e]["reports_to_user_id"] > 1) ? $ux[$e]["reports_to"] : "<!-- Not assigned -->";

?>
    <table class="tabulation" cellspacing="0" width="100%" style="font-size: 80%; border: 1px solid gray;">
        <tr style="background: <?php echo $bgcolor; ?>">
            <td style="font-weight: bold; color:white;" colspan="2">
                <?php echo $ux[$e]["displayname"]; ?>
            </td>
        </tr>
        <tr>
            <td width="50%" valign="top" colspan="2">
                
                <table class="dataTbl" style="width: 100%;">
                    <tr>
                        <td style="width: 100px;">Loginname</td>
                        <td><?= $ux[$e]["loginname"] ?></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><?= $ux[$e]["branchname"] ?></td>
                    </tr>
                    <tr>
                        <td>HR Group</td>
                        <td><?= $ux[$e]["hrgroup"] ?></td>
                    </tr>
                    <tr>
                        <td>Reports to</td>
                        <td><?= $reports_to ?></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Monthly Salary</td>
                        <td><?= ($ux[$e]["salary"] / 100) ?></td>
                    </tr>
                    <tr>
                        <td>Annual Incentives</td>
                        <td><?= ($ux[$e]["incentives"] / 100) ?></td>
                    </tr>
                    -->
                    <tr>
                        <td>Date of Joining</td>
                        <td><?= $ux[$e]["doj"] ?></td>
                    </tr>
                    <tr>
                        <td>Date of Exit</td>
                        <td><?= $ux[$e]["doe"] ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

<?php

}

?>
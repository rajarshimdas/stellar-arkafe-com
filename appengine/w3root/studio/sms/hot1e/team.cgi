<?php
/*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 						                    |
| Updated On: 21-Oct-10					                |
+-------------------------------------------------------+
*/

// $domainid = 2;
include 'foo/sms/projectDashboard/snapshotFn.cgi';

$teamleaderX = getProjectManager($projectid, $mysqli);
$teamleader = $teamleaderX["fname"];
/*
+-------------------------------------------------------+
| Display Team Creation form				            |
+-------------------------------------------------------+
*/
?>
<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="0">

    <form action="execute.cgi" method="POST">
        <input type="hidden" name="a" value="t1xteam-add">
        <input type="hidden" name="sx" <?php echo 'value="' . $sessionid . '"'; ?>>
        <tbody>
            <tr>
                <td align="center" valign="undefined" width="30%" style="background:#E8E9FF; height: 50px">
                    Project Manager: <?php echo $teamleader; ?>
                </td>
                <td align="center" valign="undefined" width="70%" style="background:#E8E9FF;">

                    <?php
                    if ($roleid < $pm_roles_id) {
                    ?>
                        Team*:
                        <select name="uid" style="width:240px;">
                            <option value="0">-- Select User --</option>
                            <?php
                            $query = "select
                                        id,
                                        fullname,
                                        loginname
                                    from
                                        users
                                    where
                                        domain_id = $domainid and
                                        active = 1
                                    order by
                                        fullname";

                            if ($result = $mysqli->query($query)) {

                                while ($row = $result->fetch_row()) {

                                    if ($row[1] !== 'sysadmin') {
                                        echo "<option value='$row[0]'>" . $row[1] . "</option>";
                                    }
                                }

                                $result->close();
                            }
                            ?>
                        </select>

                        <input type="hidden" name="rid" value="14">
                        <input name="submit" type="submit" value="Add" style="width:90px;">
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </form>
</table>


<table class="tabulation" cellpadding="2" cellspacing="0" width="100%">

    <tr class="headerRow">
        <td class="headerRowCell1" width="50px" style="text-align: center;">
            No
        </td>
        <td class="headerRowCell2" style="border-right: 0px;">
            Team Members
        </td>
        <td class="headerRowCell2" width="50px" style="border-right: 0px;">
            &nbsp;
        </td>
        <td class="headerRowCell2" width="50px">
            &nbsp;
        </td>

    </tr>

    <?php
    $co = 1;
    $sql88 = "select
                t1.user_id as memberuid,
                t2.loginname as memberloginname,
                t1.roles_id as memberrid,
                t3.name as workgroup,
                t2.fullname as memberfullname
            from
                roleinproject as t1,
                users as t2,
                userhrgroup as t3
            where
                t1.user_id = t2.id and
                t1.project_id = $projectid and
                t1.active = 1 and
                t2.active = 1 and
                t1.roles_id > 10 and
                t1.roles_id = t3.id
            order by
                t2.fullname";
    // echo "Q88: $sql88";

    if ($r2 = $mysqli->query($sql88)) {

        while ($row = $r2->fetch_row()) {

            $memberUID          = $row[0];
            $memberLoginname    = $row[1];
            $memberRID          = $row[2];
            $memberRole         = $row[3];
            $memberName         = $row[4];

            if ($memberRID == 12) $memberName = "$memberName (Project Coordinator)";

            /* Set row for this team member */
            echo "<tr id='team-uid-$memberUID' class='dataRow'>";
            echo "<td class='dataRowCell1' style='text-align:center;'>$co</td>";
            echo "<td class='dataRowCell2' style='border-right: 0px;'>&nbsp;$memberName</td>";
            
            // PM & PC can see team timesheet
            if ($roleid < 14){
                echo "<td class='dataRowCell2' style='text-align: right; border-right: 0px;'> 
                        <a href='project.cgi?a=t1xteam-ts&tmid=$memberUID'>
                            <img class='fa5button' src='/da/fa5/clock.png'>
                        </a>
                    </td>";
            } else {
                echo "<td class='dataRowCell2' style='text-align: right; border-right: 0px;'>&nbsp;</td>";
            }


            // Only PM can delete
            if ($roleid <= 10)
                echo "<td class='dataRowCell2' style='text-align: center;'> 
                        <a href='project.cgi?a=t1xteam-M&uid=$memberUID'>
                            <img class='fa5button' src='/da/fa5/times-circle.png'>
                        </a>
                    </td>";
            else
                echo "<td class='dataRowCell2' style='text-align: right;'>&nbsp;</td>";

            echo "</tr>";
            $co++;
        }

        $r2->close();
    } else {
        printf("Error[88]: %s\n", $mysqli->error);
    }
    $mysqli->close();
    ?>
</table>
&nbsp;
<span style="color:red">
    <?php
    $message = $_GET["m"];
    if ($message)
        echo $message;
    ?>
</span>
<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 02-Feb-12					                |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once $_SERVER["DOCUMENT_ROOT"] . '/clientSettings.cgi';

// RoleId > Anyone with a roleId <= 13 will get administration access.
$projectManagerRoleId = 13;
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <title>SMS :: Timesheets manage</title>
    <link rel="icon" type="image/x-icon" href="/matchbox/favicon.png">

    <link type='text/css' rel='stylesheet' href='/matchbox/themes/cool/style.css'>
</head>

<body>
    <div id="windowBox">

        <div style="width:1000px">
            <?php include '../foo/header.php'; ?>
        </div>

        <?php
        $mysqli = cn1();

        /*
            +-------------------------------------------------------+
            | Get projects array                                    |
            +-------------------------------------------------------+
            */
        $query = "select
                        t1.project_id,
                        t2.projectname,
                        t3.domainname,
                        t2.jobcode
                    from
                        roleinproject as t1,
                        projects as t2,
                        domain as t3
                    where
                        t1.user_id = $user_id and
                        t1.roles_id <= $projectManagerRoleId and
                        t1.active = 1 and
                        t2.active = 1 and
                        t1.project_id = t2.id and
                        t2.domain_id = t3.id
                    order by
                        t2.jobcode";

        //echo "<br>Q: ".$query;

        if ($result = $mysqli->query($query)) {

            while ($row = $result->fetch_row()) {
                $projX[] = array(
                    "project_id"    => $row[0],
                    "projectname"   => $row[3] . ' - ' . $row[1],
                    "domainname"    => $row[2]
                );
            }

            $result->close();
        }

        /*
            +-------------------------------------------------------+
            | Display Header                                        |
            +-------------------------------------------------------+
            */
        ?>
        <style>
            .tabulation tr td {
                text-align: center;
                padding: 0 10px;
            }

            .tabulation tr td:nth-child(2) {
                text-align: left;
            }
        </style>
        <table class="tabulation" style="width: 1000px;" cellpadding="0" cellspacing="0">
            <tr class="headerRow">
                <td class="headerRowCell1" width="100px" height="35px">No.</td>
                <td class="headerRowCell2">Project Timesheet Approval</td>
                <td class="headerRowCell2" width="100px">New Entries</td>
                <td class="headerRowCell2" width="100px">&nbsp;</td>
            </tr>
            <?php
            /*
                +-------------------------------------------------------+
                | Display Rows                                          |
                +-------------------------------------------------------+
                */
            for ($i = 0; $i < count($projX); $i++) {

                // Get the number of new timesheet entries for this project
                $new_no = 0;
                $query = 'select 1 from timesheet where project_id = ' . $projX[$i]["project_id"] . ' and active = 1 and approved = 0';
                // echo "<br>Q: ".$query;

                if ($result = $mysqli->query($query)) {
                    $new_no = $result->num_rows;
                    $result->close();
                }

                echo '<tr class="dataRow">
                            <td class="dataRowCell1">
                            ' . ($i + 1) . '    
                            </td>
                            <td class="dataRowCell2">' . $projX[$i]["projectname"] . '</td>
                            <td class="dataRowCell2">' . $new_no . '</td>
                            <td class="dataRowCell2">
                                <a class="button" href="manage.cgi?pid=' . $projX[$i]["project_id"] . '">&nbsp;Go&nbsp;</a>
                            </td>
                        </tr>';
            }

            ?>
        </table>
    </div>
</body>

</html>
<?php
$mysqli->close();
?>
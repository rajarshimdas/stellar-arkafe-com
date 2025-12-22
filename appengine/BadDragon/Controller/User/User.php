<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 18-Feb-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function bdGetUsersArray(object $mysqli): array
{

    $query = "select * from view_users";
    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        };

        $result->close();
    }
    return $users;
}


function bdGetUsersArrayX(object $mysqli): array
{

    $query = "select * from view_users";
    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $users[$row["user_id"]] = $row;
        };

        $result->close();
    }
    return $users;
}

function bdGetUsersArrayByUID(int $uid, object $mysqli): ?array
{

    $query = "select * from `view_users` where `user_id` = '$uid'";
    if ($result = $mysqli->query($query)) {

        if ($row = $result->fetch_assoc()) {
            $user = $row;
        };

        $result->close();
    }
    return $user;
}




function bdGetUsersProjects(int $user_id, object $mysqli): ?array
{

    $domain_id = 2;

    /* Get List of projects for this user */
    $sql = "select
                t2.projectname,
                t2.id as projectid,
                t2.jobcode
            from               
                roleinproject as t1,                
                projects as t2
            where
                t1.user_id = $user_id and
                t1.project_id = t2.id and
                t2.domain_id = $domain_id and
                t2.active = 1
            order by
                jobcode";

    // echo "Q: $sql"; die;

    /* Get Projects array */
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_row()) {

            $ProjX[] = array(
                "pn"        => $row[2] . ' - ' . $row[0],
                "id"        => $row[1],
                'jobcode'   => $row[2]
            );
        }

        $result->close();
    } else {
        return null;
    }

    /* Admin Users Login */
    $adminuser = 0;
    $sql = "select 
                1
            from
                daemons as t1,
                users as t2
            where
                t1.name = t2.loginname and
                t2.id = $user_id";

    if ($result = $mysqli->query($sql)) {
        if ($row = $result->fetch_row()) {
            $adminuser = $row[0];
        }
        $result->close();
    }

    if ($adminuser > 0) {

        // Clear previous ProjX to avoid duplicates
        unset($ProjX);

        $sql = 'select 
                    projectname, 
                    id,
                    jobcode
                from 
                    projects 
                where 
                    active = 1 and 
                    domain_id = 2 
                order by 
                    jobcode';

        /* Get Projects array */
        if ($result = $mysqli->query($sql)) {

            while ($row = $result->fetch_row()) {
                $ProjX[] = array(
                    "pn"        => $row[2] . ' - ' . $row[0],
                    "id"        => $row[1],
                    'jobcode'   => $row[2]
                );
            }

            $result->close();
        }
    }

    return $ProjX;
}

function bdUserRoleInProject(int $user_id, int $project_id, object $mysqli): ?array
{

    $query = "SELECT 
                t1.roles_id as role_id,
                t2.role
            FROM 
                roleinproject as t1,
                projectsrole as t2
            where
                t1.roles_id = t2.id and
                t1.project_id = '$project_id' and
                t1.user_id = '$user_id'";
    // echo $query; die;


    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $rx = $row;
        };

        $result->close();
    }


    /*
    +-------------------------------------------------------+
    | Daemon                                                |
    +-------------------------------------------------------+
    */
    $query = "select loginname from users where id = '$user_id'";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_assoc();
        $loginname = $row['loginname'];
        $result->close();
    }

    $daemon = 0;
    $query = "select 1 from `daemons` where `name` = '$loginname'";

    if ($result = $mysqli->query($query)) {
        $daemon = $daemon + $result->num_rows;
        $result->close();
    } else {
        die('Error[4]: ' . $mysqli->error);
    }

    $rx['role_id'] = ($daemon > 0) ? 1 : $rx['role_id'];

    if (isset($rx))
        return $rx;
    else
        return null;
}

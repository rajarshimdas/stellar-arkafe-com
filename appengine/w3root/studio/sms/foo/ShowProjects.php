<?php
# Rajarshi Das
# Created On: 10-Dec-07
# Last Updated: 21-Oct-10

/*----------------------------------------------------------------------*| 
|* Show Projects Drop down Combo box 					                *|
|* Returns an array of Project Names for which the user is a team member*|
|* For daemon user all projects will be listed				            *|
|*----------------------------------------------------------------------*/

function ShowProjects(
    int $thisUID,
    object $mysqli
): ?array {

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
                t1.user_id = $thisUID and
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
                t2.id = $thisUID";

    if ($result = $mysqli->query($sql)) {
        if ($row = $result->fetch_row()) {
            $adminuser = $row[0];
        }
        $result->close();
    }

    if ($adminuser > 0) {

        // Clear previous ProjX to avoid duplicates
        if (isset($ProjX)) unset($ProjX);

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

    $p = isset($ProjX) ? $ProjX : null;

    return $p;
}

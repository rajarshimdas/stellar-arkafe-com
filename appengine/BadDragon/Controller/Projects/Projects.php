<?php /*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 18-Feb-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function bdProjectScope(object $mysqli): array
{

    $query = "select * from projectscope where id > 1 order by displayorder";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $scope[] = $row;
    }

    return $scope;
}

function bdGetThisProjectScope(int $project_id, array $scope, object $mysqli): array
{

    $query = "select * from projectscopemap where project_id = $project_id";

    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $activescopeids = $row["activescopeids"];

    $x = explode(",", $activescopeids);
    // var_dump($x);

    // First element will be discarded automatically
    for ($i = 0; $i < count($scope); $i++) {

        $scope_id = $scope[$i]['id'];
        $flag = 0;

        for ($e = 0; $e < count($x); $e++) {
            $flag = ($scope_id == trim($x[$e])) ? ($flag + 1) : ($flag + 0);
        }
        // echo 'flag: '.$flag.'<br>';
        $projectscope[$scope[$i]['id']] = ($flag > 0) ? "T" : "F";
    }

    return $projectscope;
}

/*
+-------------------------------------------------------+
| Stage/Milestone                                       |
+-------------------------------------------------------+
*/
# CAVEAT :: dwglist stores stageno insted of id
function bdGetProjectStageArray($mysqli)
{
    $query = "select
                id,
                name as stage,
                sname,
                sname as stage_sn,
                concat(sname, ' - ', name) as stage_with_id,
                stageno
            from
                projectstage
            where
                active = 1
            order by
                stageno";

    // echo "<br>Q1: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $X[] = $row;
        };

        $result->close();
    }
    return $X;
}

/*
+-------------------------------------------------------+
| Stage/Milestone                                       |
+-------------------------------------------------------+
*/
function bdGetProjectStageByShortcode($mysqli)
{
    $query = "select
                id,
                name as stage,
                sname,
                sname as stage_sn,
                concat(sname, ' - ', name) as stage_with_id
            from
                projectstage
            where
                active = 1
            order by
                stageno";

    // echo "<br>Q1: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $X[$row['sname']] = $row;
        };

        $result->close();
    }
    return $X;
}


/*
+-------------------------------------------------------+
| Stage/Milestone                                       |
+-------------------------------------------------------+
*/
function bdGetProjectStageArrayX($mysqli)
{
    $query = "select
                    id,
                    name as stage,
                    sname
                from
                    projectstage
                where
                    active = 1
                order by
                    stageno";

    // echo "<br>Q1: ".$query;

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $X[$row["id"]] = [$row["stage"], $row["sname"]];
        };

        $result->close();
    }
    return $X;
}

/*
+-------------------------------------------------------+
| Projectscope                                          |
+-------------------------------------------------------+
*/
function bdGetProjectScopeArray($mysqli)
{
    $query = "select 
                *,
                concat(`scope`, ' - ', `description`) as scope_with_id 
            from 
                `projectscope` 
            where 
                `active` > 0 
            order by 
                `displayorder`";


    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $X[] = $row;
        };

        $result->close();
    }
    return $X;
}

/*
+-------------------------------------------------------+
| Projectscope                                          |
+-------------------------------------------------------+
*/
function bdGetProjectScopeArrayX($mysqli)
{
    $query = "select * from `projectscope` where `active` > 0 order by `displayorder`";
    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $X[$row["id"]] = [$row["scope"], $row["description"]];
        };

        $result->close();
    }
    return $X;
}



/*
+-------------------------------------------------------+
| Projectscopemap                                       |
+-------------------------------------------------------+
*/
function bdProjectScopeMap(object $mysqli): array
{

    $query = "select project_id as pid, activescopeids as sids from projectscopemap";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $pscopemap[$row["pid"]] = $row["sids"];
        };

        $result->close();
    }
    return $pscopemap;
}

/*
+-------------------------------------------------------+
| Discipline/Trades                                     |
+-------------------------------------------------------+
*/
function bdDisciplineArray(object $mysqli): array
{
    $query = "select * from `discipline` where active > 0 order by `displayorder`";

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $rx[] = $row;
        };
        $result->close();
    }
    return $rx;
}
/*
+-------------------------------------------------------+
| pid to name                                           |
+-------------------------------------------------------+
*/
function bdProjectId2Name(int $pid, object $mysqli): string
{
    $query = "select `jobcode`,`projectname` from `projects` where `id` = '$pid'";

    if ($result = $mysqli->query($query)) {
        $row = $result->fetch_assoc();
        $projectname = $row["jobcode"] . " - " . $row["projectname"];
        $result->close();
    }
    return $projectname;
}

/*
+-------------------------------------------------------+
| Project List                                          |
+-------------------------------------------------------+
*/
function bdProjectList(object $mysqli): array
{
    $query = "SELECT * FROM `view_projects`";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        };

        $result->close();
    }

    return $projects;
}

/*
+-------------------------------------------------------+
| Project List (Array by id)                            |
+-------------------------------------------------------+
*/
function bdProjectListX(object $mysqli): array
{
    $query = "SELECT * FROM `view_projects`";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {
            $projects[$row["pid"]] = $row;
        };

        $result->close();
    }

    return $projects;
}



function bdGetProjectTasks(int $pid, object $mysqli): ?array
{
    $query = "SELECT * FROM `view_tasks` where `project_id` = '$pid' and `active` > 0 order by `task_id` desc";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    if (isset($data)) return $data; else return NULL;

}

function bdGetProjectTeam(int $pid, object $mysqli): ?array {

    $query = "SELECT * FROM `view_project_team` where `project_id` = '$pid'";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    if (isset($data)) return $data; else return NULL;
}

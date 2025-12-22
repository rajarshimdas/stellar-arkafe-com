<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 20-Oct-10 				                |
| Updated On:                                           |
+-------------------------------------------------------+
| * getProjectArray                                     |
| * getProjectData                                      |
| * displayEditButton                                   |
| * addNewProject                                       |
+-------------------------------------------------------+
*/


/* projects;
+------------------------+-----------------------+------+-----+------------+----------------+
| Field                  | Type                  | Null | Key | Default    | Extra          |
+------------------------+-----------------------+------+-----+------------+----------------+
| id                     | mediumint(8) unsigned | NO   | PRI | NULL       | auto_increment |
| domain_id              | tinyint(3) unsigned   | NO   | MUL | NULL       |                |
| projectname            | varchar(100)          | NO   | UNI | NULL       |                |
| jobcode                | varchar(50)           | NO   | UNI | NULL       |                |
| branch_id              | smallint(5) unsigned  | NO   |     | 1          |                |
| handover_dt            | date                  | NO   |     | 0000-00-00 |                |
| projectstatus_id       | tinyint(3) unsigned   | NO   |     | 1          |                |
| teamleader             | varchar(100)          | NO   |     | NULL       |                |
| designmanager          | varchar(100)          | NO   |     | NULL       |                |
| currentdwglistver      | varchar(15)           | NO   |     | A          |                |
| dt                     | date                  | NO   |     | NULL       |                |
| active                 | tinyint(1)            | NO   |     | NULL       |                |
| projecttype_id         | smallint(5) unsigned  | NO   |     | 1          |                |
| size                   | smallint(5) unsigned  | NO   |     | 0          |                |
| sizeunit               | varchar(10)           | NO   |     | -          |                |
| blockflag              | tinyint(1)            | NO   |     | 0          |                |
| currentstage_id        | tinyint(3) unsigned   | NO   |     | 2          |                |
| signoffdt              | date                  | NO   |     | 0000-00-00 |                |
| contractdt             | date                  | NO   |     | 0000-00-00 |                |
| contract_period_years  | int(11)               | NO   |     | 0          |                |
| contract_period_months | int(11)               | NO   |     | 0          |                |
| escalation_kickoff     | tinyint(4)            | NO   |     | 0          |                |
| escalationdt_planned   | date                  | NO   |     | 0000-00-00 |                |
| escalationdt_start     | date                  | NO   |     | 0000-00-00 |                |
+------------------------+-----------------------+------+-----+------------+----------------+
*/

/*
+-------------------------------------------------------+
| addNewProject 					                    |
+-------------------------------------------------------+
*/
function addNewProject(
    $projectname,
    $jobcode,
    $pm_uid,
    $pc_uid,
    $branch_id,
    $domain_id,
    $contract_dt,
    $contract_period_years,
    $contract_period_months,
    $escalation_startdt,
    $kickoff,
    $erate,
    $enote,
    $mysqli
) {

    // Initiate default variables
    $error_flag     = 0;
    $pm_roleId      = 10;   // Project Manager
    $pc_roleId      = 12;   // Project Coordinator (Abhikalpan)

    // Validate the input data

    // Check Projectname is alphanumeric + dash + dot + &

    // Check Jobcode is numbers + dot combination

    // Check TeamLeader's User ID is an integer

    // Enter date in the database
    /*
    // Get the TL loginname for the id
    $query = "select `loginname` from `users` where id = $pm_uid";

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_row();
        $teamleader = $row[0];
        $result->close();
    } else {
        $error_flag = 1;
    }
    */

    $mysqli->autocommit(FALSE);

    // Insert info into projects table
    $query = "insert into `projects`
                (domain_id,projectname,jobcode,teamleader,designmanager,dt,active,branch_id,
                contractdt,
                contract_period_years,
                contract_period_months,
                escalation_kickoff,
                escalationdt_start,
                escalation_rate,
                escalation_note
            ) values (
                '$domain_id','$projectname','$jobcode','-','-',CURRENT_TIMESTAMP(),1,$branch_id,
                '$contract_dt',
                '$contract_period_years',
                '$contract_period_months',
                '$kickoff',
                '$escalation_startdt',
                '$erate',
                '$enote'
                )";
    //die("q2: $query<br>");

    if (!$mysqli->query($query)) {
        $error_flag = 1;
        die($mysqli->error);
    }

    // Get the project_id
    $this_project_id = $mysqli->insert_id;

    // Insert info in roleinproject table
    $values = "($this_project_id,$pm_uid,$pm_roleId,1)";
    if ($pc_uid > 0) {
        $values = "$values, ($this_project_id,$pc_uid,$pc_roleId,1)";
    }

    $query = "insert into roleinproject
               (`project_id`,`user_id`,`roles_id`,`active`)
            values
               $values";
    //echo "q3: $query<br>";

    if (!$mysqli->query($query)) {
        $error_flag = 1;
        die($mysqli->error);
    }

    // Create the default Blocks (Added on 19-Mar-2012)
    $query = "insert into blocks
                (project_id, blockno, blockname, phase, active)
            values
                ($this_project_id, 'MP', 'Masterplan', 1, 1)";

    if (!$mysqli->query($query)) {
        $error_flag = 1;
        die($mysqli->error);
    }


    // Projectscopemap
    $scope = bdProjectScope($mysqli);
    $ids = $scope[0]["id"];

    $i = 1;
    do {
        $ids = $ids . ',' . $scope[$i]["id"];
        $i++;
    } while ($i < count($scope));

    // die("ids csv: ".$ids);

    $query = "insert into `projectscopemap` 
                (`project_id`, `activescopeids`) 
            values 
                ('$this_project_id', '$ids')";
    // die($query);

    if (!$mysqli->query($query)) {
        $error_flag = 1;
        // die ("QE: ".$query);
        die($mysqli->error);
    }

    // Commit | Rollback
    if ($error_flag > 0) {
        $mysqli->rollback();
        return ["F", $mysqli->error];
    } else {
        $mysqli->commit();
        return ["T", $this_project_id];
    }
}

/*
+-------------------------------------------------------+
| getProjectData					                    |
+-------------------------------------------------------+
*/
function getProjectData(int $thisPID, object $mysqli): ?array
{

    // $pm_roleId = 10;        // This is coded in view_projects

    /*
    $query = "select
                t1.id as pid,
                t1.projectname,
                t1.jobcode,
                t1.branch_id,
                t2.branchname,
                t1.active,
                t3.user_id as projectleader_id,
                t4.fullname as projectleader,
                t4.emailid as projectleaderemail,
                t5.corporatename,
                t1.domain_id,
                t1.contractdt,
                t1.contract_period_years,
                t1.contract_period_months,
                t1.escalation_kickoff,
                t1.escalationdt_start,
                t1.escalation_rate,
                t1.escalation_note
            from
                projects as t1,
                branch as t2,
                roleinproject as t3,
                users as t4,
                domain as t5
            where
                t1.branch_id    = t2.id and
                t1.id           = t3.project_id and
                t3.roles_id     = $pm_roleId and
                t3.user_id      = t4.id and
                t1.domain_id    = t5.id and
                t1.id           = $thisPID";
    */

    $query = "select 
                *
            from
                view_projects
            where
                pid = $thisPID";

    /* Test
    echo "Q1: $query"; 
    */

    if ($result = $mysqli->query($query)) {

        $row = $result->fetch_assoc();

        $projectX = $row;

        $pc = getProjectCoordinater($row["pid"], $mysqli);

        $projectX["pc_uid"] = $pc[0];
        $projectX["pc_name"] = $pc[1];

        return $projectX;
    } else {
        return null;
    }
}

/*
+-------------------------------------------------------+
| displayEditButton					                    |
+-------------------------------------------------------+
*/
function displayEditButton($thisPID, $rootFolderName)
{

    $url = $_SERVER["HTTP_HOST"];
    $href = "sysadmin.cgi?a=Project%20Edit&pid=$thisPID";

?>
    <a href="<?php echo $href; ?>">
        <img src="/da/icons/edit.png" border="0">
    </a>
<?php
}

/*
+-------------------------------------------------------+
| tabulateThisProject                                   |
+-------------------------------------------------------+
*/
function tabulateThisProject($projectX, $co, $mysqli, $scope)
{

    if ($projectX[$co]["active"] < 1)
        $bgcolor = "RGB(220,220,220)";
    else
        $bgcolor = "cadetblue";
?>
    <table class="tabulation" style="font-size: 85%; border: 1px solid gray" width="100%" cellspacing="0">
        <tr>
            <td width="90%" style="background:<?php echo $bgcolor; ?>;font-weight:bold;color:white;">
                <?= $projectX[$co]["jobcode"] . ' - ' . $projectX[$co]["projectname"] ?>
            </td>
        </tr>
        <tr>
            <td>
                <table class="dataTbl">

                    <tr>
                        <td>Project Leader</td>
                        <td><?= $projectX[$co]["projectleader"] ?></td>
                    </tr>
                    <tr>
                        <td>Project Coordinator</td>
                        <td><?= $projectX[$co]["pc_name"] ?></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><?= $projectX[$co]["branchname"] ?></td>
                    </tr>
                    <tr>
                        <td>Contract Date & Period</td>
                        <td>
                            <?php
                            $cdt = ($projectX[$co]["contractdt"] == '0000-00-00') ? "Not set" : $projectX[$co]["contractdt"];
                            if ($projectX[$co]["contract_period_years"] < 1 && $projectX[$co]["contract_period_months"] < 1) {
                                $period = 'NA';
                            } else {
                                $period = $projectX[$co]["contract_period_years"] . ' year and ' . $projectX[$co]["contract_period_months"] . ' months';
                            }
                            ?>
                            <?= $cdt . ' | ' . $period ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Escalation Start Date</td>
                        <td>
                            <?php
                            $ek = ($projectX[$co]["escalation_kickoff"] > 0) ? "Yes" : "No";
                            $esdt = ($projectX[$co]["escalationdt_start"] == '0000-00-00') ? "Not set" : $projectX[$co]["escalationdt_start"];
                            ?>
                            <?= $esdt . ' | Kickoff: ' . $ek ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Escalation Rate</td>
                        <td>
                            <?php
                            $erate = ($projectX[$co]["escalation_rate"] > 0) ? $projectX[$co]["escalation_rate"] : 'NA';
                            $rnote = ($projectX[$co]["escalation_note"]) ? ' | ' . $projectX[$co]["escalation_note"] : '<!-- NA -->';
                            ?>
                            <?= $erate . $rnote ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Scope</td>
                        <td>
                            <table class="tblScope">
                                <tr>
                                    <?php

                                    $pscope = bdGetThisProjectScope($projectX[$co]["pid"], $scope, $mysqli);
                                    //var_dump($pscope);

                                    for ($i = 0; $i < count($scope); $i++) {
                                        echo '<td>' . $scope[$i]["scope"] . '</td>';
                                    }

                                    ?>
                                    <td rowspan="2" style="border:0px solid red; text-align: center;">
                                        <img id="save-<?= $projectX[$co]["pid"] ?>" class="fa5button" src="<?= BASE_URL ?>da/fa5/save.png" alt="Edit" onclick="setProjectScope(<?= $projectX[$co]["pid"] ?>)" style="display: none;">
                                    </td>
                                    <td rowspan="2" style="border:0px solid red; width:60px;">
                                        <div id="scope-<?= $projectX[$co]["pid"] ?>"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <?php
                                    for ($i = 0; $i < count($scope); $i++) {

                                        $scope_id = $scope[$i]['id'];
                                        // echo '<td>' . $pscope[$scope_id] . '</td>';

                                        $ckboxId = "ck-" . $projectX[$co]["pid"] . "-" . $scope_id;

                                        $flag = ($pscope[$scope_id] == "T") ? "checked" : "";
                                        echo '<td><input id="' . $ckboxId . '" type="checkbox" ' . $flag . ' onclick="saveBtnShow(' . $projectX[$co]["pid"] . ')"></td>';
                                    }

                                    ?>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

<?php

}

/*
+-------------------------------------------------------+
| getProjectsArray					                    |
+-------------------------------------------------------+
*/
function getProjectsArray($mysqli)
{

    /*
    $query = "select
                t1.id as pid,
                t1.projectname,
                t1.jobcode,
                t1.branch_id,
                t2.branchname,
                t1.active,
                t3.user_id as projectleader_id,
                t4.fullname as projectleader,
                t5.corporatename,
                t1.domain_id,
                t1.contractdt,
                t1.contract_period_years,
                t1.contract_period_months,
                t1.escalation_kickoff,
                t1.escalationdt_start,
                t1.escalation_rate,
                t1.escalation_note
            from
                projects as t1,
                branch as t2,
                roleinproject as t3,
                users as t4,
                domain as t5
            where
                t1.branch_id    = t2.id and
                t1.id           = t3.project_id and
                t3.roles_id     = 10 and
                t3.user_id      = t4.id and
                t1.domain_id    = t5.id and
                t1.domain_id    = 2
            order by
                t1.jobcode";
    */

    $query = "select * from view_projects";

    /* Test
    echo "Q1: $query"; die; 
    */

    $result = $mysqli->query($query);
    $no = 0;
    while ($row = $result->fetch_assoc()) {

        $projectX[$no] = $row;
        $pc = getProjectCoordinater($row["pid"], $mysqli);

        $projectX[$no]["pc_uid"] = $pc[0];
        $projectX[$no]["pc_name"] = $pc[1];

        $no++;
    }

    return $projectX;
}

// Get Project Coordinator
function getProjectCoordinater($project_id, $mysqli)
{
    $rx = [0, 'Not Assigned'];

    $query = "select 
                t1.id,
                t1.fullname,
                t2.roles_id
            from
                users as t1,
                roleinproject as t2
            where
                t1.id = t2.user_id and
                t2.roles_id = 12 and
                t2.project_id = $project_id";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_row()) {
            $rx = [$row[0], $row[1]];
        }
    }

    return $rx;
}
?>
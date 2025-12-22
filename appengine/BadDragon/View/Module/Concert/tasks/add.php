<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 08-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Projectscope & Stage/Milestone                        |
+-------------------------------------------------------+
*/
$projectstageX = bdGetProjectStageArray($mysqli);
$projectstageXcount = count($projectstageX);
$scopeX = bdGetProjectScopeArray($mysqli);

$pstage = json_encode($projectstageX);
$pscope = json_encode(bdGetProjectScopeArrayX($mysqli));
$pscopemap = json_encode(bdProjectScopeMap($mysqli));

?>
<!-- JSON Markups -->
<?= '<script type="application/json" id="pscope">' . $pscope . '</script>' ?>

<?= '<script type="application/json" id="pscopemap">' . $pscopemap . '</script>' ?>

<?= '<script type="application/json" id="pstage">' . $pstage . '</script>' ?>

<?php
$projx = bdGetUsersProjects($uid, $mysqli);
?>

<style>
    .task-table-form tr td {
        border: 0px solid red;
    }

    .task-table-form tr td:first-child {
        text-align: right;
        padding: 4px 10px;
    }

    input[type="submit"] {
        width: 100px;
    }
</style>

<div>
    <?php if ($pid > 500): ?>
        <form name="tmForm" method="POST">

            <input type="hidden" name="a" id="a" value="concert-api-addTask">
            <input type="hidden" name="pid" id="pid" value="<?= $pid ?>">


            <table id="formTS" class="task-table-form">

                <tr>
                    <td style="width: 100px;">Task</td>

                    <!-- Project Scope -->
                    <td style="width: 130px;">
                        <input type="hidden" id="form_scope_id" value="1">
                        <input type="hidden" id="form_scope_name" value="-- Scope --">

                        <select name="scope" id="scope" onchange="onScopeSelect()">
                            <option>-- Scope --</option>
                        </select>
                    </td>


                    <!-- Project Milestone -->
                    <td style="width: 130px;">

                        <input type="hidden" id="form_stage_id" value="1">
                        <input type="hidden" id="form_stage_name" value="-- Milestone --">

                        <select id="ps" name="ps">
                            <option value='0'>-- Milestone --</option>
                        </select>

                    </td>

                    <!-- Work -->
                    <td>
                        <input type="text" id="wk" name="wk" placeholder="Work Description">
                    </td>

                    <!-- Alloted Hours -->
                    <td style="width: 90px;">
                        <input type="number" id="hr" name="hr" placeholder="Hours">
                    </td>
                    <!-- Alloted Min -->
                    <td style="width: 90px;">
                        <input type="number" id="mn" name="mn" placeholder="Minutes">
                    </td>

                    <td style="width: 90px;">
                        <input type="number" id="tp" name="tp" placeholder="Target %">
                    </td>
                    <td>
                        <input class="button" type="submit" name="go" value="Add Task">
                    </td>
                    <td></td>
                </tr>

            </table>
        </form>
    <?php endif; ?>
</div>

<style>
    .task-table tr td:nth-child(4) {
        text-align: left;
        padding-left: 15px;
    }

    #taskBox tr {
        vertical-align: text-top;
        height: fit-content;

    }

    #taskBox tr td {
        line-height: 35px;
    }
</style>

<table class="task-table">
    <thead>
        <tr>
            <td style="width:30px;">No</td>
            <td style="width:80px;">Scope</td>
            <td style="width:80px;">Milestone</td>
            <td>Work Description</td>
            <td style="width:150px;">Alloted Manhours</td>
            <td style="width:150px;">Target %</td>
        </tr>
    </thead>
    <tbody id="taskBox"></tbody>
</table>

<script>
    window.onload = (event) => {
        onProjectSelect()

        <?php
        $x = isset($route->parts[4]) ? $route->parts[4] : 'x';

        if (substr($x, 0, -1) == 'e') {
            echo "dxAlertBox('Error', '$bdMessageTxt')";
        }

        ?>
    }

    function fetchProjectTasks(pid) {
        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-fetchProjectTasks")
        formData.append("pid", pid)

        bdPostData(apiUrl, formData).then((response) => {
            // console.log(response);
            displayProjectTasks(response)
        });
    }

    function displayProjectTasks(data) {

        document.getElementById("taskBox").innerHTML = "<!-- Flush -->"

        if (data[0]["rx"] == "T") {
            generateTaskRows(data[1])
        } else {
            console.log("Project not selected")
        }

    }

    function generateTaskRows(tasks) {

        console.log(tasks)

        let taskBox = document.getElementById("taskBox")
        let x = "<!-- Task Rows -->"
        let n = 1
        for (let e = 0; e < tasks.length; e++) {

            x = x + "<tr><td>" + n.toString() + "</td><td>" + tasks[e]["scope_sn"] + "</td><td>" + tasks[e]["stage_sn"] + "</td><td style='text-align:left;vertical-align:middle;'><div class='taskDiv'>" + tasks[e]["work"] + "</div></td><td>" + tasks[e]["manhours"] + ":" + tasks[e]["manminutes"] + "</td><td>" + tasks[e]["status_this_month_target"] + "</div></tr>"
            n++
        }
        // console.log(x)
        taskBox.innerHTML = x
    }

    function onProjectSelect() {

        // For Overheads
        const pid = document.getElementById("pid").value
        const scope = document.getElementById("scope")
        const stage = document.getElementById("ps")

        // JSON
        const pscope = JSON.parse(document.getElementById("pscope").innerHTML)
        const pscopemap = JSON.parse(document.getElementById("pscopemap").innerHTML)

        // Disable Milestone for Overheads
        if ((pid > 10 && pid < 100) || pid < 1) {

            console.log("Overheads")
            stage.disabled = true
            scope.disabled = true

        } else {

            console.log("Projects | " + pid);
            scope.disabled = false
            // console.log(pscopemap[pid])

            let px = pscopemap[pid].split(",")

            let form_scope_id = document.getElementById("form_scope_id").value
            let form_scope_name = document.getElementById("form_scope_name").value

            let option = '<option value="' + form_scope_id + '">' + form_scope_name + '</option>'
            for (let i = 1; i < px.length; i++) {
                option += "<option value='" + px[i] + "'>" + pscope[px[i]][1] + "</option>"
            }
            document.getElementById("scope").innerHTML = option

        }

        fetchProjectTasks(pid)
    }

    function onScopeSelect() {

        const pstage = JSON.parse(document.getElementById("pstage").innerHTML)
        // console.log(pstage)

        let scope_id = document.getElementById("scope").value

        let no = 0 // Start index
        let co = pstage.length // End index

        // If Masterplan selected
        if (scope_id < 11) {
            co = 2
        } else {
            no = 2
        }
        // console.log("Scope | " + scope_id + " | co: " + co)

        let form_stage_id = document.getElementById("form_stage_id").value
        let form_stage_name = document.getElementById("form_stage_name").value

        // Validate default stage
        if (form_stage_id > 1) {
            if (!isStageValid(scope_id, form_stage_id, pstage)) {
                form_stage_id = "1"
                form_stage_name = "-- Select Milestone --"
            }
        }

        let option = '<option value="' + form_stage_id + '">' + form_stage_name + '</option>'

        for (let i = no; i < co; i++) {

            //console.log()
            option += "<option value='" + pstage[i]["id"] + "'>" + pstage[i]["stage"] + "</option>"
        }
        document.getElementById("ps").innerHTML = option
        document.getElementById("ps").disabled = false

    }
</script>
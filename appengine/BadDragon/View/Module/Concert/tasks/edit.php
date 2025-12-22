<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 19-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$stagex = bdGetProjectStageArray($mysqli);
$scopex = bdGetProjectScopeArray($mysqli);
$tasks  = bdGetProjectTasks($pid, $mysqli);
$co     = 1;

//echo '<pre>'. var_dump($stagex). '</pre>';
?>
<style>
    .task-table tr {
        vertical-align: text-top;

    }

    .task-table tr td {
        padding: 0px 5px;
        margin: 0px;
        /* border: 0px solid green;*/
    }

    table.toolbar {
        width: 100%;
        border: 0px solid red;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    /*
    table.toolbar tr {
        height: fit-content;
    }
    */
    table.toolbar tr td {
        padding: 0px;
        border: 0px;
    }
</style>

<div style="background-color:aquamarine;padding:10px;">
This Edit page will be removed
</div>

<table class="task-table">
    <thead>
        <tr class="header">
            <td style="width: 80px;">No</td>
            <td style="width: 100px; text-align: left;">Milestone</td>
            <td style="text-align: left;">Task</td>
            <td style="width: 100px;">Completed %</td>
            <td style="width: 100px;">Target %</td>
            <td style="width: 100px;">Alloted</td>
            <td style="width: 80px;"></td>
        </tr>
    </thead>
    <tbody>
        <?= taskRow($tasks, $stagex, $mysqli) ?>
    </tbody>
</table>

<?php
//var_dump($tasks);

function taskRow($t, $s, $mysqli)
{
    $co = 1;
    $tr = '';
    for ($i = 0; $i < count($s); $i++) {

        $stage_id = $s[$i]["id"];
        $stage = $s[$i]["sname"];
        $no = 0;
        $flag = 0;

        // count
        for ($x = 0; $x < count($t); $x++) {
            if ($stage_id == $t[$x]["stage_id"]) $no++;
        }

        // display rows
        if ($no > 0) {
            for ($x = 0; $x < count($t); $x++) {

                $task_id = $t[$x]["task_id"];

                if ($stage_id == $t[$x]["stage_id"]) {

                    $tr = $tr . '<tr>';

                    if ($flag < 1) {
                        $tr = $tr . '<td rowspan="' . $no . '">' . $co++ . '</td>
                                <td rowspan="' . $no . '">' . $stage . '</td>';
                        $flag++;
                    }

                    $tr = $tr . '<td style="vertical-align:middle;">
                                    <table class="toolbar">
                                        <tr>
                                            <td style="text-align:left;">' . $t[$x]["scope"] . '</td>
                                        </tr>
                                        <tr style="font-weight:bold;">
                                            <td id="wk_' . $task_id . '" style="text-align:left;">' . $t[$x]["work"] . '</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>' . $t[$x]["status_last_month"] . '</td>
                                <td>' . $t[$x]["status_this_month_target"] . '</td>
                                <td>' . $t[$x]["manhours"] . ':' . $t[$x]["manminutes"] . '</td>
                                <td valign="top" style="padding-top:4px;">
                                    <table class="toolbar">
                                        <tr id="dx_' . $task_id . '">
                                            <td style="width:50%;">
                                                <img class="fa5button" src="' . BASE_URL . 'da/fa5/edit.png" alt="edit"
                                                    onclick=\'javascript:dxEditTask(' . $task_id . ',' . $t[$x]["stage_id"] . ',"' . $t[$x]["stage_with_id"] . '",' . $t[$x]["scope_id"] . ',"' . $t[$x]["scope_with_id"] . '",' . $t[$x]["status_last_month"] . ',' . $t[$x]["status_this_month_target"] . ',' . $t[$x]["manhours"] . ',' . $t[$x]["manminutes"] . ')\'>
                                            </td>
                                            <td>
                                                <img class="fa5button" src="' . BASE_URL . 'da/fa5/delete.png" alt="delete"
                                                    onclick="javascript:dxDeleteTask(' . $task_id . ')">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>';
                }
            }
        }
    }

    return $tr;
}

require __DIR__ . "/edit-dialog-box.php";


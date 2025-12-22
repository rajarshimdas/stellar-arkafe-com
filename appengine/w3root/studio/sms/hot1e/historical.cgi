<?php
require_once BD . 'Controller/Projects/Projects.php';
require_once W3PATH . 'appengine/w3root/studio/foo/dialog/alert.cgi';

//$mX = bdGetProjectStageArray($mysqli);
$mX =  bdGetProjectHistorical($pid, $mysqli);
// var_dump($mX);
$no = 1;
?>
<table style="width:100%;background-color:var(--rd-form-background);">
    <tr style="height: 45px;">
        <td>
            Time and Cost before 1st April 2024
        </td>
        <td style="width:10%;text-align:center;">
        </td>
    </tr>
</table>
<table class="tabulation" style="width:100%;">
    <tr class="headerRow">
        <td class="headerRowCell1" style="width:5%;text-align: center;height: 40px;">
            No
        </td>
        <td class="headerRowCell2">
            Milestone
        </td>
        <td class="headerRowCell2" style="width:15%;text-align: center;">
            Manhour
        </td>
        <td class="headerRowCell2" style="width:15%;text-align: center;">
            Cost
        </td>
        <td class="headerRowCell2" style="width:5%;text-align: center;">
            &nbsp;
        </td>
    </tr>

    <?php foreach ($mX as $m): ?>

        <tr class="dataRow">
            <td class='dataRowCell1' style="text-align: center;"><?= $no++ ?></td>
            <td class='dataRowCell2'><?= $m['stage'] ?></td>
            <td class='dataRowCell2' style="text-align: center;"><?= $m['mh'][0] ?></td>
            <td class='dataRowCell2' style="text-align: center;"><?= $m['cost'] ?></td>
            <td class='dataRowCell2' style="text-align: center;">
                <button onclick="dxEdit(<?= $pid . ',' . $m['stage_id'] . ',\'' . $m['stage'] . '\',' . $m['mh'][1] . ',' . $m['mh'][2] . ',' . $m['cost'] ?>)">Edit</button>
            </td>
        </tr>

    <?php endforeach ?>

</table>

<dialog id="dxEdit">
    <table class='dxTable' style="width:350px;">
        <tr>
            <td>
                Historical Data
            </td>
            <td style="width: 30px;">
                <img class="fa5button" src="<?= $base_url ?>da/fa5/window-close.png" onclick="dxClose('dxEdit')">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <style>
                    table.dxTableForm {
                        text-align: right;
                        border-spacing: 4px;
                    }
                </style>
                <table class='dxTableForm' style="width:100%">
                    <tr>
                        <td colspan="3" style="text-align: left; border-bottom: 1px solid gray;">
                            Milestone:&nbsp;<span id='dxMs'></span>
                            <input type="hidden" id="dxPId">
                            <input type="hidden" id="dxMsId">
                        </td>
                    </tr>
                    <tr>
                        <td>Manhours</td>
                        <td style="width:100px;">
                            <input type="number" id="h" name="h" style="width: 100%;" placeholder="Hours">
                        </td>
                        <td style="width:100px;">
                            <input type="number" id="m" name="m" style="width: 100%;" placeholder="Minutes">
                        </td>
                    </tr>
                    <tr>
                        <td>Cost</td>
                        <td colspan="2">
                            <input type="number" step=".01" id='cost' name="cost" style="width: 100%;" placeholder="<?= $currency ?>">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left;">
                            <button style="width: 100%;" onclick="saveData()">Save</button>
                        </td>
                        <td>
                            <button style="width: 100%;" onclick="dxClose('dxEdit')">Cancel</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</dialog>

<script>
    function dxEdit(pid, stage_id, stage, h, m, cost) {

        console.log(pid)
        console.log(stage_id)

        e$('dxMs').innerHTML = stage

        e$('dxPId').value = pid
        e$('dxMsId').value = stage_id
        e$('h').value = h
        e$('m').value = m
        e$('cost').value = cost

        dxShow("dxEdit")
    }

    function saveData() {

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-saveHistoricData")

        formData.append("pid", e$('dxPId').value)
        formData.append("sid", e$('dxMsId').value)
        formData.append("h", e$('h').value)
        formData.append("m", e$('m').value)
        formData.append("cost", e$('cost').value)

        bdPostData(apiUrl, formData).then((response) => {

            console.log(response);

            if (response[0] == "T") {
                console.log("Saved.")
                window.location.reload()
            } else {
                showAlert("Historical Data", response[1])
            }
        });
    }
</script>

<?php
function bdGetProjectHistorical($pid, $mysqli)
{
    // Milestones
    $mX = bdGetProjectStageArray($mysqli);

    $query = "select * from projecthistoric where project_id = '$pid'";

    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        //$rX = $row;
        $r[$row['stage_id']] = $row;
    }

    $co = isset($mX) ? count($mX) : 0;
    for ($i = 0; $i < $co; $i++) {
        $m = $mX[$i];
        $stage_id = $m['id'];

        $cost = 0;
        $mh = 0;

        if (isset($r[$stage_id])) {
            $cost = $r[$stage_id]['costinpaise'] / 100;
            $mh = $r[$stage_id]['manminutes'];
        }

        $rX[] = [
            'stage_id' => $stage_id,
            'stage' => $m['stage'],
            'cost' => $cost,
            'mh' => rdMinutes2Manhours($mh),
        ];
    }
    return $rX;
}

function rdMinutes2Manhours($minutes)
{

    $h = 0;
    $m = 0;

    if ($minutes > 0) {
        $h = floor($minutes / 60);
        $m = $minutes - ($h * 60);
    }

    return [$h . ':' . $m, $h, $m];
}

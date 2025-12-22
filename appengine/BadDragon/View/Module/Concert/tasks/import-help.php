<style>
    .help {
        padding: 15px;
    }
</style>
<div class="help">

    <p>The Task import CSV Template is shown below:</p>
    <img src="<?= BASE_URL ?>da/images/tasks.png" alt="Tasks CSV">

    <p><b>Column Descriptions</b></p>
    <table>
        <tr>
            <td style="width: 200px;">No</td>
            <td>Serial Number. Just for reference.</td>
        </tr>
        <tr>
            <td>Scope Id</td>
            <td>Shortcode for Scope</td>
        </tr>
        <tr>
            <td>Milestone Id</td>
            <td>Shortcode for Milestone</td>
        </tr>
        <tr>
            <td>Work Description</td>
            <td>Task to be performed</td>
        </tr>
        <tr>
            <td>Manhours</td>
            <td>Time alloted to perform the task in Hours</td>
        </tr>
        <tr>
            <td>Minutes</td>
            <td>Time alloted to perform the task in Minutes</td>
        </tr>
        <tr>
            <td>Target %</td>
            <td>Target for current month % task completion</td>
        </tr>
    </table>
    <p>Note: Do not change the sequence of columns in the upload file.</p>
    
    <p><b>Shortcodes</b></p>

    <p><b>Shortcodes for Scope</b></p>
    <?php
    $s = bdGetProjectScopeArray($mysqli);
    // var_dump($s);
    ?>

    <table>
        <tr style="font-weight: bold;">
            <td style="width: 200px;border-bottom: 1px solid gray;">Scope</td>
            <td style="border-bottom: 1px solid gray;">Shortcode</td>
        </tr>
        <?php foreach ($s as $x) : ?>
            <tr>
                <td><?= $x["description"] ?></td>
                <td><?= $x["scope"] ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <p><b>Shortcodes for Milestone</b></p>
    <?php
    $m = bdGetProjectStageArray($mysqli);
    // var_dump($m);
    ?>

    <table>
        <tr style="font-weight: bold;">
            <td style="width: 200px;border-bottom: 1px solid gray;">Milestone</td>
            <td style="border-bottom: 1px solid gray;">Shortcode</td>
        </tr>
        <?php foreach ($m as $x) : ?>
            <tr>
                <td><?= $x["stage"] ?></td>
                <td><?= $x["stage_sn"] ?></td>
            </tr>
        <?php endforeach ?>
    </table>


</div>
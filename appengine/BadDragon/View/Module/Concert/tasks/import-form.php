<?php if ($pid > 500): ?>
<style>
    table#import {
        width: 1000px;
        margin: auto;
        border-spacing: 5px;
    }

    table#import tr {
        height: 50px;
    }

    table#import tr td {
        border: 0px solid red;

    }
</style>

<div style="padding: 15px; background-color: #d4d5e9;">

    <table id="import">
        <tr>
            <td style="width: 60px;">
                Step 1
            </td>
            <td style="width: 280px;">
                Download Template:
            </td>
            <td></td>
            <td style="width: 80px;">
                <a class="button" href="<?= BASE_URL ?>studio/sms/download.php?f=tasks.csv">Template</a>
            </td>

        </tr>
        <tr>
            <td>Step 2</td>
            <td colspan="2">Fill up the tasks for <?= $pname ?> using Excel and save as CSV File [<?= $jobcode ?>.csv]</td>
            <td>
                <a class="button" href="<?= BASE_URL ?>concert/portal/tasks/import/help">Help</a>
            </td>
        </tr>

        <tr>
            <form name="form" action="<?= BASE_URL ?>index.cgi" method="post" enctype="multipart/form-data">
                <input type="hidden" name="a" value="concert-api-importtask1csv">

                <td>Step 3</td>
                <td>Import Tasks from CSV File [<?= $jobcode ?>.csv]:</td>
                <td>
                    <input type="hidden" name="maxRowsInCSVFile" value="1000">
                    <input class="button" type="file" name="file">
                </td>
                <td>
                    <input class="button" type="submit" name="submit" value="Next >>" style="width:100px">
                </td>
            </form>
        </tr>

    </table>

</div>
<?php endif; ?>
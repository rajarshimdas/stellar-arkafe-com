<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 11-Oct-24 (Astami)                        |
| Updated On:                                           |
+-------------------------------------------------------+
*/
if ($pid > 500): 
?>
<style>
    table#update {
        width: 1000px;
        margin: auto;
        border-spacing: 5px;
    }

    table#update tr {
        height: 50px;
    }

    table#update tr td {
        border: 0px solid red;

    }
</style>

<div style="padding: 15px; background-color: #d4d5e9;">

    <table id="update">
        <tr>
            <td style="width: 60px;">
                Step 1
            </td>
            <td style="width: 280px;" colspan="2">
                Export ongoing tasks [<?= "Tasks - " . bdProjectId2Name($pid, $mysqli) . ".csv" ?>]:
            </td>
            <td style="width: 80px;">
                <a class="button" href="<?= BASE_URL ?>concert/api/tasksExportCSV">Export CSV</a>
            </td>

        </tr>
        <tr>
            <td>Step 2</td>
            <td colspan="2">Update task info in Excel</td>
            <td>
                <a class="button" href="<?= BASE_URL ?>concert/portal/tasks/update/help">Help</a>
            </td>
        </tr>

        <tr>
            <form name="form" action="<?= BASE_URL ?>index.cgi" method="post" enctype="multipart/form-data">
                <input type="hidden" name="a" value="concert-api-tasksLoadCSV">

                <td>Step 3</td>
                <td>Load Updated Tasks from CSV File:</td>
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
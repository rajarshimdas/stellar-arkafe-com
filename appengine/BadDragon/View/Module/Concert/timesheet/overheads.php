<?php
/*
+-------------------------------------------------------+
| Rajarshi Das					                        |
+-------------------------------------------------------+
| Created On: 24-May-2024       			            |
| Updated On:                                           |
+-------------------------------------------------------+
*/
$dayManhours = bdGetDayManhoursWithoutLeave($timesheetDate, $user_id, $mysqli);
$dayTimesheet = bdGetDayTimesheet($timesheetDate, $user_id, $mysqli);



$displaySelectProject = 0;

// Caveat: domain_id changed to hide inactive 'Overhead | Allowed Time-off' since active flag of all Overheads is 0.
$query = "SELECT * FROM `projects` where `id` > 10 and `id` < 500 and `domain_id` = 2";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    $o[] = $row;
}

// var_dump($o);
// echo 'hrgroup_id: ' . $hrgroup_id;

?>

<form name="tmForm">

    <input type="hidden" id="a" value="concert-api-addTimesheet">

    <table id="formTS" class="task-table-form">

        <tr>
            <td style="text-align: right; width: 70px;">Date</td>
            <td style="width: 75px;">
                <input type="hidden" id="ts_date_cal" value="<?= $timesheetDateCal ?>">
                <input type="date" id="ts_date" value="<?= $timesheetDate ?>" max="<?= date("Y-m-d") ?>" min="<?= $lockdt ?>" onchange="javascript:setActiveDate()">
            </td>
            <td id="dayMh" style="width: 80px; text-align: center; border: 2px solid white; background-color:cadetblue; color: white;">
                <?= $dayManhours ?>
            </td>
            <td style="width: 40px; text-align:center;">
                <img class="fa5button" src="<?= BASE_URL ?>da/fa5/eye.png" alt="view" onclick="javascript:viewDay()">
            </td>

            <td style="width: 150px;text-align: right;">Add Overhead</td>
            <td style="width: 280px;">
                <select name="oid" id="oid" onclick="onOverheadSelect()">
                    <option value="0">-- Select Overhead --</option>
                    <?php
                    for ($i = 0; $i < count($o); $i++) {
                        # Jobcode stores hrgroup ids for Overheads | 30-May-2025
                        # eg: S<id>:12:13:14:15:16:17:18:19:20:21:26:25
                        #
                        $hrX = explode(':', $o[$i]['jobcode']);

                        if (in_array($hrgroup_id, $hrX)) {
                            echo '<option value="' . $o[$i]["id"] . '">' . $o[$i]["projectname"] . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>

            <td style="width: 60px;">
                <input type="number" id="ts_h" placeholder="hour" min="0" max="24">
            </td>
            <td style="width: 60px;">
                <input type="number" id="ts_m" placeholder="min" min="0" max="59">
            </td>
            <!-- Updated | 03-Apr-25 -->
            <td style="width: 180px;">
                <input type="text" id="desc" placeholder="Work Description" disabled>
            </td>
            <td style="width: 80px;">
                <input class="button" type="button" value="Add" onclick="javascript:addTimesheetOh()">
            </td>
            <td>
                <div id="rx"></div>
            </td>

        </tr>

    </table>
</form>

<?php require_once BD . 'View/Module/Concert/timesheet/dxViewDay.php'; ?>

<script>
    let activeDayTimesheet = <?= json_encode($dayTimesheet) ?>;

    let activeDate = '<?= $timesheetDate ?>';

    const hrgroup_id = <?= $hrgroup_id ?>;

    function addTimesheetOh() {

        let desc = (e$('desc').value) ? e$('desc').value : '-';
        let oid = e$('oid').value

        /* Data Validation - Overheads */
        if (oid == 80) {
            if (desc.length < 5) {
                dxAlertBox("Timesheet Add Error", 'Enter work description (min 5 characters).')
                return false
            }
        }

        /* Data Validation - HR */
        if (hrgroup_id == 22) {
            if (desc.length < 5) {
                dxAlertBox("Timesheet Add Error", 'Enter work description (min 5 characters).')
                return false
            }
        }
        // Form Variables
        let dt = (e$("ts_date").value) ? e$("ts_date").value : '0000-00-00';
        let h = (e$("ts_h").value) ? e$("ts_h").value : 0;
        let m = (e$("ts_m").value) ? e$("ts_m").value : 0;

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-addTimesheet")
        formData.append("task_id", 1)
        formData.append("date", dt)
        formData.append("h", h)
        formData.append("m", m)
        formData.append("work", desc)
        formData.append("percent", 0)

        formData.append("project_id", e$("oid").value);
        formData.append("scope_id", '1')
        formData.append("stage_id", '1')

        bdPostData(apiUrl, formData).then((response) => {

            console.log(response);

            if (response[0] == "T") {
                console.log("Added.")

                // window.location.reload()
                e$("rx").innerHTML = "Saved"
                e$("ts_h").value = ''
                e$("ts_m").value = ''
                // e$("oidOp").innerHTML = oidOp
                e$("dayMh").innerHTML = response[3]
                // e$("desc").disabled = true
                e$("desc").value = ''

                activeDayTimesheet = response[4]

                $('#rx').delay(5000).fadeOut('slow');
            } else {
                dxAlertBox("Timesheet Add Error", response[1])
            }
        });
    }

    function onOverheadSelect() {

        let oid = e$('oid').value
        rx('oid: ' + oid)

        if (oid == 80) {
            e$('desc').disabled = false
        } else {
            e$('desc').disabled = true
        }

        if (hrgroup_id == 22) {
            e$('desc').disabled = false
        }
    }
</script>
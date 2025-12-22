<table class="formTBL" width="100%" style="background:#ccf255" cellspacing="2px" border="0">
    <tr>
        <td colspan="2" style="background:RGB(150,150,150);color:white;font-weight:bold;font-size:110%">
            Holidays
        </td>
        <td colspan="2" style="background:RGB(150,150,150);">
            <select name="activeYear" id="activeYear" onchange="setActiveYear()">
                <option value="<?= $activeYear ?>"><?= $activeYear ?></option>
                <option value="<?= $activeYear - 1 ?>"><?= $activeYear - 1 ?></option>
                <option value="<?= $activeYear + 1 ?>"><?= $activeYear + 1 ?></option>
            </select>
        </td>
    </tr>

    <tr>
        <td align="right">
            Date & Type:
        </td>
        <td style="width: 140px;">
            <input type="hidden" id="dtYear" value="<?= date($activeYear . "-01-01") ?>">
            <input id="dt" type="date" min="<?= date($activeYear . "-01-01") ?>" max="<?= date($activeYear . "-12-31") ?>">
        </td>
        <td>
            <select name="sat" id="sat" onchange="satmode()">
                <option value="0">Holiday</option>
                <option value="1">Saturday</option>
            </select>
        </td>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td align="right">
            Holiday:
        </td>
        <td colspan="2">
            <input id="holiday" type="text">
        </td>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>
            <button class="button" style="width:150px" onclick="javascript:addHoliday();">Add</button>
        </td>
        <td></td>
        <td></td>
    </tr>
</table>



<table class="tabulation" style="width:100%" border="1">
    <thead>
        <tr class="headerRow">
            <td class="headerRowCell2" style="width:50px">No</td>
            <td class="headerRowCell2" style="width:150px">Date</td>
            <td class="headerRowCell2" colspan="2">Holidays in <?= $activeYear ?></td>
        </tr>
    </thead>
    <tbody id="trHolidays"></tbody>
</table>


<script>
    window.onload = (event) => {
        getHolidayList()
    };

    function getHolidayList() {

        const dtYear = document.getElementById("dtYear").value
        // Fetch API
        const apiUrl = "<?= BASE_URL ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "sysadmin-api-holidayList")
        formData.append("dtYear", dtYear)


        bdPostData(apiUrl, formData).then((response) => {
            // console.log(response)
            if (response[0] == "T") {
                document.getElementById("trHolidays").innerHTML = response[1];
            } else {
                console.log(response[1])
            }
        });

    }

    function setActiveYear() {

        const activeYear = document.getElementById("activeYear").value
        // console.log('setActiveYear: ' + activeYear)

        // Fetch API
        const apiUrl = "<?= BASE_URL ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "sysadmin-api-setActiveYear")
        formData.append("activeYear", activeYear)

        bdPostData(apiUrl, formData).then((response) => {
            location.reload()
        });

    }


    function addHoliday() {

        const dt = document.getElementById("dt").value
        const sat = document.getElementById("sat").value
        const holiday = document.getElementById("holiday").value

        // Fetch API
        const apiUrl = "<?= BASE_URL ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "sysadmin-api-holidayAdd")
        formData.append("dt", dt)
        formData.append("sat", sat)
        formData.append("holiday", holiday)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response)
            
            if (response[0] == "T") {

                // Reset Form
                document.getElementById("dt").value = ""
                document.getElementById("holiday").value = ""
                // Update Holiday Table
                document.getElementById("trHolidays").innerHTML = response[1];
            } else {

                showAlert("Error", response[1])
            }
        });

    }

    function deleteHoliday(hid) {

        const dtYear = document.getElementById("dtYear").value
        console.log(dtYear)

        // Fetch API
        const apiUrl = "<?= BASE_URL ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "sysadmin-api-holidayDelete")
        formData.append("hid", hid)
        formData.append('dtYear', dtYear)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response)
            if (response[0] == "T") {
                getHolidayList()
            } else {
                showAlert("Error", response[1])
            }

        });

    }

    function satmode() {

        console.log('satmode')

        const sat = document.getElementById("sat").value
        if (sat > 0) {
            document.getElementById("holiday").value = 'Saturday'
            document.getElementById("holiday").disabled = true 

        } else {
            document.getElementById("holiday").value = ''
            document.getElementById("holiday").disabled = false

        }
    }
</script>
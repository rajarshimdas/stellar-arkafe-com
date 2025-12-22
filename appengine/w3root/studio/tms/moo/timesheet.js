/* timesheet.js
+-----------------------------------------------------------+
| Rajarshi Das						                        |
+-----------------------------------------------------------+
| Created On:   13-Aug-2010                                 |
| Updated On:   23-Mar-2012 DA-Urbis                        |
|               16-Mar-2024 Abhikalpan requirements         |
+-----------------------------------------------------------+
| Note:                                                     |
| requires /matchbox/plugins/phpFunctionsInJavascript.js    |
| to be loaded seperately                                   |
+-----------------------------------------------------------+
*/

function onProjectSelect() {

    // For Overheads
    const pid = document.getElementById("pj").value
    const stage = document.getElementById("ps")
    const percent = document.getElementById("percent")
    const scope = document.getElementById("scope")

    // JSON
    const pscope = JSON.parse(document.getElementById("pscope").innerHTML)
    const pscopemap = JSON.parse(document.getElementById("pscopemap").innerHTML)

    // Disable Milestone for Overheads
    if ((pid > 10 && pid < 100) || pid < 1) {

        console.log("Overheads")
        stage.disabled = true
        percent.disabled = true
        scope.disabled = true

    } else {

        console.log("Projects | " + pid);
        scope.disabled = false
        percent.disabled = false
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

}

function onScopeSelect() {

    const pstage = JSON.parse(document.getElementById("pstage").innerHTML)
    // console.log(pstage)

    let scope_id = document.getElementById("scope").value

    let no = 0                  // Start index
    let co = pstage.length      // End index

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


function formValidation() {

    // Validation error flag
    var flag = 0

    // Update: 25-Jan-2024
    const statusBox = document.querySelector("#statusBox")
    statusBox.innerHTML = "<!-- Reset -->"
    statusBox.classList.remove("statusBox")

    // Update: 08-May-2013
    // Timesheets can be entered for 3 working days only.
    var lockdtTS = $('#lockdtTS').val()
    var lockdtCal = $('#lockdtCal').val()
    var caldt = $('#dt').val()

    var shortMonthsToNumber = []
    shortMonthsToNumber["Jan"] = "01"
    shortMonthsToNumber["Feb"] = "02"
    shortMonthsToNumber["Mar"] = "03"
    shortMonthsToNumber["Apr"] = "04"
    shortMonthsToNumber["May"] = "05"
    shortMonthsToNumber["Jun"] = "06"
    shortMonthsToNumber["Jul"] = "07"
    shortMonthsToNumber["Aug"] = "08"
    shortMonthsToNumber["Sep"] = "09"
    shortMonthsToNumber["Oct"] = "10"
    shortMonthsToNumber["Nov"] = "11"
    shortMonthsToNumber["Dec"] = "12"

    var caldtX = caldt.split('-')

    // Note :: This function requires /matchbox/plugins/phpFunctionsInJavascript.js to be loaded seperately
    var caldtTS = mktime(0, 0, 0, shortMonthsToNumber[caldtX[1]], caldtX[0], '20' + caldtX[2])
    /*
    console.log("caldtTS: " + caldtTS)
    return false
    */

    var x = caldtTS - lockdtTS
    // Check the date
    //console.log('lockdtTS: ' + lockdtTS + ' | caldt: 20' + caldtX[2] + '-' + shortMonthsToNumber[caldtX[1]] + '-' + caldtX[0] + ' || caldtTS: ' + caldtTS + ' | x = ' + x)
    // Date selected from Calendar

    if (x < 0) {
        alertStatusBox("Error :: You do not have permission to add timesheet before " + lockdtCal)
        flag = 1
        return false
    }

    // Make sure that the date selected is not a future date
    var currentTSx = new Date().getTime() // milliseconds
    var currentTS = currentTSx / 1000

    var x = currentTS - caldtTS
    if (x < 0) {
        alertStatusBox("Error :: The date (" + caldt + ") you selected is a day in the future.")
        flag = 1
        return false
    }



    // Project Selection
    if (document.getElementById("pj").value < 1) {
        alertStatusBox("Error :: Select a Project and Try again...")
        flag = 1
        return false
    }

    // Project Special case
    const project_id = document.getElementById("pj").value
    console.log("project_id: " + project_id)

    if ((project_id < 2) || (project_id > 100)) {

        // Stage
        const pstage = document.getElementById("ps").value
        console.log("pstage: " + pstage)

        if (pstage < 2) {
            alertStatusBox("Error :: Select Milestone...")
            flag = 1
            return false
        }

    }

    // Validate Project Scope
    const scope = document.getElementById("scope").value

    if ((project_id < 2) || (project_id > 100)) {

        // For projects
        if (scope < 2) {
            alertStatusBox("Error :: Select Scope...")
            flag = 1
            return false
        }

    } else {

        // Set value for NA
        scope.value = 1

    }

    // Hour and Minutes
    if ((document.getElementById("hr").value < 1) && (document.getElementById("mn").value < 1)) {
        alertStatusBox("Error :: Time is Zero. Select Hour and/or Minutes and try again...")
        flag = 1
        return false
    }

    // Work Description
    const textareaWk = document.getElementById('wk').value.length;

    if (textareaWk < 5) {
        alertStatusBox("Work description cannot be empty (min 5 characters). Try again...")
        flag = 1
        return false
    }

    // Percent is an integer between 1 to 100
    const percentComplete = document.getElementById("percent").value

    if ((project_id < 2) || (project_id > 100)) {

        // For projects
        if (isNumeric(percentComplete) != true) {
            alertStatusBox("Percent Complete must be a Number.")
            flag = 1
            return false

        } else {

            if (percentComplete < 0 || percentComplete > 100) {
                alertStatusBox("Percent Complete must be between 0 to 100")
                flag = 1
                return false
            }
        }
    } else {

        // Set value to 0 for overheads
        percentComplete.value = 0

    }

    if (flag < 1) {
        return true
    }
    return false

}

function alertStatusBox(message) {
    statusBox.innerHTML = message
    statusBox.classList.add("statusBox")
}

function isNumeric(str) {
    if (typeof str != "string") return false // we only process strings!  
    return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
        !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}

function isStageValid(scope_id, stage_id, pstage) {
    console.log("isStageValid")

    let no = 0                  // Start index
    let co = pstage.length      // End index
    let flag = 0                // Match flag

    // If Masterplan selected
    if (scope_id < 11) { co = 2 } else { no = 2 }

    for (let i = no; i < co; i++) {
        if (pstage[i]["id"] == stage_id) flag++
    }

    if (flag > 0) { return true } else { return false }
}

function fnMouseOver(tmRowId) {
    // console.log('mouse over: ' + tmRowId)

    const tableRow = document.getElementById(tmRowId)
    tableRow.classList.add("manageTableHover")
}

function fnMouseOut(tmRowId) {
    // console.log('mouse out: ' + tmRowId)

    const tableRow = document.getElementById(tmRowId)
    tableRow.classList.remove("manageTableHover")
}


function dxClose() {
    document.getElementById("dx").close()
}

/*
+-------------------------------------------------------+
| Accept Button - onclick				                |
+-------------------------------------------------------+
*/
function buttonAccept(timesheet_id) {

    console.log('Accept tsid: ' + timesheet_id)

    const apiUrl = "buttonAccept.cgi";
    var formData = new FormData()
    formData.append("tsid", timesheet_id)
    // console.log(formData)

    // Make a POST request using the Fetch API
    fetch(apiUrl, {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // console.log('ok')
            return response.json();
        })
        .then(serverResData => {
            if (serverResData.rx == 'T') {
                // Success
                console.log("buttonAccept done.")
                document.getElementById("tm-row-" + timesheet_id).style.display = "none"
            } else {
                // Error
            }

        })
        .catch(error => {
            console.error('Network Error: ', error);
        });
}
/*
+-------------------------------------------------------+
| Reject Button - onclick				                |
+-------------------------------------------------------+
*/
function buttonReject(timesheet_id) {

    console.log('Reject tsid: ' + timesheet_id)


    const apiUrl = "buttonReject.cgi";
    var formData = new FormData()
    formData.append("tsid", timesheet_id)
    // console.log(formData)

    // Make a POST request using the Fetch API
    fetch(apiUrl, {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // console.log('ok')
            return response.json();
        })
        .then(serverResData => {
            if (serverResData.rx == 'T') {
                // Success
                console.log("buttonReject done.")
                document.getElementById("tm-row-" + timesheet_id).style.display = "none"
                document.getElementById("tr-date-mh-" + timesheet_id).innerHTML = serverResData.mh
            } else {
                // Error
            }

        })
        .catch(error => {
            console.error('Network Error: ', error);
        });
}


/*
+-------------------------------------------------------+
| Remark Button - onclick				                |
+-------------------------------------------------------+
*/

function buttonAddRemark(timesheet_id) {

    const apiUrl = "tm/form-timesheet-data.cgi";

    var formData = new FormData()
    formData.append("tsid", timesheet_id)
    // console.log(formData)

    // Make a POST request using the Fetch API
    fetch(apiUrl, {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            console.log('ok', response)
            return response.json();
        })
        .then(serverResData => {
            // console.log('serverResData: ' + serverResData) 
            document.getElementById("dxTsid").value = timesheet_id
            dxFillData(serverResData)
        })
        .catch(error => {
            console.error('Network Error: ', error);
        });


}

function dxFillData(rx) {
    /*
    document.getElementById("dxName").innerHTML = rx.fullname
    document.getElementById("dxDate").innerHTML = rx.date
    document.getElementById("dxHour").innerHTML = rx.hour + ' hour'
    document.getElementById("dxMin").innerHTML = rx.min + ' min'
    document.getElementById("dxMilestone").innerHTML = rx.stage
    document.getElementById("dxProject").innerHTML = rx.jobcode + ' - ' + rx.projectname
    document.getElementById("dxWork").innerHTML = rx.work
    document.getElementById("dxWorkFrom").innerHTML = rx.worked_from
    document.getElementById("dxPercent").innerHTML = rx.percent + '%'
    */
   
    // Open dialog box after fetching data
    const dx = document.getElementById("dx");
    dx.showModal()

    document.getElementById("dxRemark").focus()

}

function dxAddRemarkButton() {

    const dxTsid = document.getElementById("dxTsid").value
    const dxRemark = document.getElementById("dxRemark").value
    console.log("dxTsid: " + dxTsid + ' | dxRemark: ' + dxRemark)

    const apiUrl = "buttonEditByPM.cgi"

    var formData = new FormData()
    formData.append("tsid", dxTsid)
    formData.append("remark", dxRemark)
    // console.log(formData)

    // Make a POST request using the Fetch API
    fetch(apiUrl, {
        method: 'POST',
        mode: "same-origin",
        credentials: "same-origin",
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            console.log('ok', response)
            return response.json();
        })
        .then(serverResData => {
            rx = serverResData.rx

            // Close dialog
            dxClose()

            // Remove table row
            document.getElementById("tm-row-" + dxTsid).style.display = "none"
        })
        .catch(error => {
            console.error('Network Error: ', error);
        });


}

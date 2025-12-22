/*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   11-May-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/


/*
+---------------------------------------------------------------------------+
| https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch    |
+---------------------------------------------------------------------------+
| Example POST method implementation:                                       |
+---------------------------------------------------------------------------+
*/
async function bdPostData(url = "", formData = {}) {
    // Default options are marked with *
    const response = await fetch(url, {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, *cors, same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        // headers: {
        // "Content-Type": "application/json",
        // 'Content-Type': 'application/x-www-form-urlencoded',
        // },
        redirect: "error", // manual, *follow, error
        referrerPolicy: "no-referrer", // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        //body: JSON.stringify(data), // body data type must match "Content-Type" header
        body: formData, // RD - use FormData
    });
    return response.json(); // parses JSON response into native JavaScript objects
}

/* Howto use bdPostData function
+---------------------------------------------------------------------------+
| https://developer.mozilla.org/en-US/docs/Web/API/FormData                 |
+---------------------------------------------------------------------------+

const apiUrl = "<?= $base_url ?>index.cgi";
var formData = new FormData()
formData.append("a", "tasks-api-fetchProjectData")

bdPostData(apiUrl,formData).then((response) => {
    console.log(response);
});
*/


function e$(eid) {
    return document.getElementById(eid)
}

function rx(log) {
    console.log(log)
}

function setActiveProject() {

    const pid = document.getElementById("pid").value

    // Fetch API
    const apiUrl = "/index.cgi";
    var formData = new FormData()
    formData.append("a", "concert-api-setActiveProject")
    formData.append("pid", pid)

    bdPostData(apiUrl, formData).then((response) => {
        console.log(response);
        window.location.reload()
    });

}

/*
+-------------------------------------------------------+
| TM: Timesheets                                        |
+-------------------------------------------------------+
*/
function setActiveDate() {

    const dt = document.getElementById("ts_date").value
    //console.log(dt)
    const apiUrl = "/index.cgi";

    var formData = new FormData()
    formData.append("a", "concert-api-setActiveDate")
    formData.append("date", dt)

    bdPostData(apiUrl, formData).then((response) => {

        console.log(response);
        if (response[0] == "T") {
            document.getElementById("dayMh").innerHTML = response[1]
            activeDayTimesheet = response[2]
            activeDate = dt
            document.getElementById("ts_date_cal").value = response[3]
        } else {
            dxAlertBox("setActiveDate Error", response[1])
        }
    });

}


function viewDay() {

    const dt = document.getElementById("ts_date").value
    const dtCal = document.getElementById("ts_date_cal").value
    //console.log(activeDayTimesheet)

    document.getElementById("dxDate").innerHTML = dtCal

    const ts = activeDayTimesheet
    let r = ''
    let mh = ''

    for (let i = 0; i < ts.length; i++) {
        mh = (ts[i]["project_id"] < 10)? '': ts[i]["no_of_hours"] + ":" + ts[i]["no_of_min"];
        r += "<tr><td>" + ts[i]["projectname"] + "</td><td>" + ts[i]["stage"] + "</td><td><div>" + ts[i]["scope_name"] + "</div><div>" + ts[i]["work"] + "</div></td><td>" + ts[i]["percent"] + "</td><td>" + mh + "</td></tr>"
    }
    //console.log(r)
    document.getElementById("dxViewDayTs").innerHTML = r

    dxShow("dxViewDay", 'NA')
}


/*
# Chatgpt
#
*/
function lockPage() {
    document.getElementById('page-lock-overlay').style.display = 'flex';
}

function unlockPage() {
    document.getElementById('page-lock-overlay').style.display = 'none';
}

function formatIsoDateToHuman(isoDateStr) {
  const date = new Date(isoDateStr);
  
  const day = String(date.getDate()).padStart(2, '0');
  const monthShort = date.toLocaleString('en-US', { month: 'short' }); // "Jun"
  const year = String(date.getFullYear()).slice(-2); // "25"

  return `${day}-${monthShort}-${year}`;
}

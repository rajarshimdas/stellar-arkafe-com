/*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 31-Mar-24                                 |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function saveBtnShow(pid) {
    document.getElementById("save-" + pid).style.display = "block"
}

function hideElement(element) {
    element.style.display = "none";
}

function setProjectScope(pid) {
    // console.log(pid)

    const scopeIds = document.getElementById("scopeIds").value
    // console.log(scopeIds)
    var id = scopeIds.split(",")
    var rx = 'T'

    for (let i = 0; i < id.length; i++) {

        var ckboxId = 'ck-' + pid + "-" + id[i]
        // console.log(ckboxId)

        var flag = document.getElementById(ckboxId).checked
        // console.log(ckboxId + ": " + flag)

        if (flag == true) {
            rx = rx + "," + id[i]
        }
    }

    console.log(rx)
    setProjectScopeSave(pid, rx)
}

function setProjectScopeSave(pid, rx) {

    const messagebox = document.getElementById("scope-" + pid)
    const saveBtn = document.getElementById("save-" + pid)

    var actionProg = 'project-scope-ids';

    var dataString = 'a=' + actionProg + '&pid=' + pid + '&rx=' + rx;
    // console.log('function: dxSignOffSave?' + dataString);

    if (dataString) {
        /* Save */
        $.ajax({
            type: "GET",
            url: "engine.cgi",
            data: dataString,
            cache: false,
            success: function (r) {
                // Hide button
                saveBtn.style.display = "none"
                // Display response
                messagebox.style.display = "block"
                messagebox.innerHTML = r;
                // Hide message
                setTimeout(function () {
                    hideElement(messagebox);
                }, 5000);

            }
        });
    }

}

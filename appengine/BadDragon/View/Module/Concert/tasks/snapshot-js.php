<script>
    function getTask(tid, work) {

        console.log('tid: ' + tid)

        const dxTask = document.getElementById("dxTask")
        const dxTaskTable = document.getElementById("dxTaskTable")
        const dxWork = document.getElementById("dxWork")

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi";
        var formData = new FormData()
        formData.append("a", "concert-api-getTaskTimesheets")
        formData.append("tid", tid)

        bdPostData(apiUrl, formData).then((response) => {
            //console.log(response);
            if (response[0] == "T") {
                // console.log(response[1])

                dxWork.innerHTML = work
                dxTaskTable.innerHTML = response[1]
                dxTask.showModal()

            } else
                dxAlertBox('Task Edit Error', response[1])
        });
    }

    function dxCloseTask(dx) {

        const dxTask = document.getElementById("dxTask")
        const dxTaskTable = document.getElementById("dxTaskTable")
        const dxWork = document.getElementById("dxWork")

        // Reset
        dxTaskTable.innerHTML = ""
        dxWork.innerHTML = ""

        // Close dialog
        dxTask.close()
    }

    function go2a() {
        let fx = document.getElementById("fx").value
        let uri = "<?= BASE_URL ?>concert/portal/tasks/snapshot/fx/" + fx

        location.href = uri
    }

    function taskOnhold(tid, flag) {
        console.log('taskOnhold :: tid: ' + tid)

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi"
        var formData = new FormData()
        formData.append("a", "concert-api-taskOnhold")
        formData.append("tid", tid)
        formData.append("flag", flag)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] == "T") {
                console.log(response[1])

                e$("taskHold-" + tid).innerHTML = response[1]
                e$("wk_" + tid).style.color = 'red'

            } else
                dxAlertBox('Task Edit Error', response[1])
        });

    }

    function taskCompleted(tid) {

        console.log('taskCompleted :: tid: ' + tid)

        // Fetch API
        const apiUrl = "<?= $base_url ?>index.cgi"
        var formData = new FormData()
        formData.append("a", "concert-api-taskCompleted")
        formData.append("tid", tid)

        bdPostData(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] == "T") {
                e$("taskStatus-" + tid).innerHTML = "100%"
                e$("wk_" + tid).style.color = 'green'

            } else
                dxAlertBox('Task Edit Error', response[1])
        });

    }
</script>


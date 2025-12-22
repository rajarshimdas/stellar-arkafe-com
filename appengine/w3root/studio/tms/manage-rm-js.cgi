<script>
    const apiUrl = "<?= BASE_URL ?>index.cgi";
    const thisUId = <?= $this_uid ?>;

    function btnApprove(thisDt) {
        rx('btnApprove: ' + thisUId + " | " + thisDt)

        var formData = new FormData()
        formData.append("a", "concert-api-tsBtnApprove")
        formData.append("thisUId", thisUId)
        formData.append("thisDt", thisDt)

        bdPostData(apiUrl, formData).then((res) => {
            rx(res);
            if (res[0] == "T") {
                    e$('tr-day-' + thisDt).style.display = 'none'
            } else
                dxAlertBox('Error', res[1])
        });
    }

    function btnReject(tsId, thisDt) {
        rx('btnReject: ' + tsId + ' | ' + thisDt + ' | ' + thisUId)

        var formData = new FormData()
        formData.append("a", "concert-api-tsBtnReject")
        formData.append("tsId", tsId)
        formData.append("thisUId", thisUId)
        formData.append("thisDt", thisDt)

        bdPostData(apiUrl, formData).then((res) => {
            rx(res);
            if (res[0] == "T") {
                if (res[2] == '0:0') {
                    e$('tr-day-' + res[1]).style.display = 'none'
                } else {
                    // Hide deleted
                    e$('tm-row-' + tsId).style.display = 'none'
                    // Day total
                    e$('day-mh-' + res[1]).innerHTML = res[2]
                }
            } else
                dxAlertBox('Error', res[1])
        });
    }

    function moEnter(thisDt) {
        rx('moEnter')
        e$('tr-day-' + thisDt).style.backgroundColor = 'rgb(203, 228, 248)'
    }

    function moLeave(thisDt) {
        rx('moLeave')
        e$('tr-day-' + thisDt).style.backgroundColor = 'white'
    }
</script>
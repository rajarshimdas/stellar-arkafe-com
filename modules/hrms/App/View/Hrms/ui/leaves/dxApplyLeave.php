<dialog id="dxApplyLeave">
    <table class="dxTable" style="width: 420px;">
        <thead>
            <tr>
                <td colspan="3">Apply Leave</td>
                <td>
                    <img class="fa5button" src="<?= BASE_URL ?>aec/public/fa5/window-close-w.png" alt="Close" onclick="dxClose('dxApplyLeave')">
                </td>
            </tr>
        </thead>
        <!-- Only Earned Leaves at Abhikalpan | Do not delete
        <tr>
            <td>Type</td>
            <td colspan="2">
                <select name="ltype" id="ltype">
                    <option value="0">-- Select --</option>
                    <?php
                    foreach ($leaveTypes as $t) {
                        if ($t['is_normal'] > 0 && $t['active']):
                    ?>
                            <option value="<?= $t['id'] ?>"><?= $t['type'] ?></option>
                    <?php
                        endif;
                    }
                    ?>
                </select>
            </td>
            <td></td>
        </tr>
        -->
        <tr>
            <td style="width: 60px;">
                Start
            </td>
            <td style="width: 150px;">
                <input type="date" name="sdt" id="sdt" onchange="setStartDt()"
                    min="<?= $dateRangeStart ?>"
                    max="<?= $dateRangeEnd ?>">
            </td>
            <td style="width: 150px;">
                <select name="sx" id="sx" onchange="setStartDtX()">
                    <option value="F">Full day</option>
                    <option value="FH">First Half day</option>
                    <option value="SH">Second Half day</option>
                </select>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>End</td>
            <td>
                <input type="date" name="edt" id="edt" onchange="setEndDt()"
                    min="<?= $dateRangeStart ?>"
                    max="<?= $dateRangeEnd ?>">
            </td>
            <td>
                <select name="ex" id="ex" onchange="setEndDtX()">
                    <option value="F">Full day</option>
                    <option value="FH">First Half day</option>
                    <option value="SH">Second Half day</option>
                </select>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Days</td>
            <td colspan="2">
                <input type="text" name="nod" id="nod" placeholder="Number of Days" disabled>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Reason</td>
            <td colspan="2">
                <input type="text" name="rx" id="rx">
            </td>
            <td></td>
        </tr>
        <tfoot>
            <tr>
                <td colspan="4">
                    <button class="button-18" style="width: 80px;" onclick="applyLeave()">Apply</button>
                    <button class="button-18" style="width: 80px;" onclick="dxClose('dxApplyLeave')">Cancel</button>
                </td>
            </tr>
        </tfoot>
    </table>
</dialog>
<script>
    let nodTotal = 0 // Total NOD
    let sdt = '0000-00-00' // Start Date
    let sx = 'F' // F | FH | SH
    let edt = '0000-00-00' // End Date
    let ex = 'F' // F | FH | SHs

    const dxApplyLeaveBox = e$("dxApplyLeave")

    function dxApplyLeave() {

        // Reset the dialog box
        // e$('ltype').selectedIndex = 0
        e$('sdt').value = ''
        e$('sx').selectedIndex = 0
        e$('edt').value = ''
        e$('ex').selectedIndex = 0
        e$('nod').value = ''
        e$('rx').value = ''

        // Reset Vars
        nodTotal = 0
        sdt = '0000-00-00'
        edt = '0000-00-00'

        // Ready. Fire the dialog box...
        dxApplyLeaveBox.showModal()
    }

    function calNoOfDays() {

        // Ensure start date has a value
        if (e$('sdt').value) {

            sdt = e$('sdt').value

            // Is end date set?
            if (e$('edt').value) {
                // Multiple days
                edt = e$('edt').value
                //rx('edt: ' + edt)

                let date1 = new Date(sdt)
                let date2 = new Date(edt)

                // Calculating the time difference
                // of two dates
                let Difference_In_Time = date2.getTime() - date1.getTime();

                // Calculating the no. of days between
                // two dates
                let Difference_In_Days = Math.round(Difference_In_Time / (1000 * 3600 * 24));

                nodTotal = Difference_In_Days + 1

                // Half Day?
                if (e$('sx').value != 'F') nodTotal = nodTotal - 0.5
                if (e$('ex').value != 'F') nodTotal = nodTotal - 0.5

                sx = e$('sx').value
                ex = e$('ex').value

                // Sandwich leave exception for half day | Updated: 13-Aug-25
                let holidayDeducted = []    // Stop double deduction

                // Start Date is half day
                if (sx != 'F') {
                    let startFlag = 0
                    let startDeduct = 0
                    let nextDay = sdt
                    while (startFlag < 1) {
                        nextDay = getISODateNext(nextDay);
                        if (holidays.includes(nextDay)) {
                            startDeduct++
                            holidayDeducted.push(nextDay)
                        } else {
                            startFlag++
                        }
                    }
                    nodTotal = nodTotal - startDeduct
                }

                // End Date is half day
                if (ex != 'F') {
                    let endFlag = 0
                    let endDeduct = 0
                    let prevDay = edt
                    while (endFlag < 1) {
                        prevDay = getISODatePrevious(prevDay);
                        if (holidays.includes(prevDay)) {
                            if (!holidayDeducted.includes(prevDay)) {
                                endDeduct++
                            }
                        } else {
                            endFlag++
                        }
                    }
                    nodTotal = nodTotal - endDeduct
                }

            } else {
                // Single day
                //rx('single day')
                edt = sdt

                sx = e$('sx').value
                ex = sx

                nodTotal = 1
                // Half Day?
                if (e$('sx').value != 'F') nodTotal = nodTotal - 0.5

            }
        }

        // Chain leaves
        if (chainLeaveLookupBackward(sdt)) nodTotal += nodChainBackward
        if (chainLeaveLookupForward(edt)) nodTotal += nodChainForward

    }



    function setStartDt() {

        sdt = e$('sdt').value
        rx('setStartDt:' + sdt)

        const startdt = new Date(sdt)
        //rx(startdt)

        // Check if this date is a holiday
        if (holidays.includes(sdt)) {

            e$('sdt').value = ''
            e$('sdt').focus()
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = formatIsoDateToHuman(sdt) + ' is a holiday.'
            e$("dxMessageBox").showModal()
            return false

        }

        // Check if this date exists in leaves
        if (checkDateInLeaves(sdt)) {

            e$('sdt').value = ''
            e$('sdt').focus()
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = 'There is an existing leave application for date ' + formatIsoDateToHuman(sdt)
            e$("dxMessageBox").showModal()
            return false
        }

        const nextDate = new Date(sdt); // Instantiate by creating a copy
        nextDate.setDate(startdt.getDate() + 1); // Time travel

        const nextDateFormatted = nextDate.toISOString().split('T')[0];
        //rx(nextDateFormatted)

        e$('edt').setAttribute('min', nextDateFormatted)

        calNoOfDays()

        e$('nod').value = nodTotal
    }

    function setStartDtX() {
        rx('setStartDtX')
        if (e$('sdt').value) setStartDt()
    }

    function setEndDt() {
        rx('setEndDt: ' + e$('edt').value)

        if (e$('sdt').value) {
            let sdt = e$('sdt').value
            let edt = e$('edt').value

            // Check if this date is a holiday
            if (holidays.includes(edt)) {

                e$('edt').value = ''
                e$('edt').focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = formatIsoDateToHuman(edt) + ' is a holiday.'
                e$("dxMessageBox").showModal()
                return false

            }

            if (checkDateRangeInLeaves(sdt, edt)) {

                let m = 'Leave application exists for dates:<br>'

                dateLeaveExists.forEach((dt) => {
                    m += formatIsoDateToHuman(dt) + '<br>'
                })

                e$('edt').value = ''
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = m
                e$("dxMessageBox").showModal()

            }

            calNoOfDays()

            e$('nod').value = nodTotal
        } else {

            e$('edt').value = ''
            e$('dxMessageBoxTitle').innerHTML = 'Error'
            e$('dxMessageBoxBody').innerHTML = 'Select start date first.'
            e$("dxMessageBox").showModal()

        }
    }

    function setEndDtX() {
        rx('setEndDtX')
        if (e$('edt').value) setEndDt()
    }

    const applyLeave = () => {
        console.log('applyLeave | uid: ' + uid)

        var formData = new FormData()
        formData.append("a", "aec-hrms-api-leaveApply")
        formData.append("ltype", /* e$("ltype").value */ 2); // Only Earned Leaves  
        formData.append("sdt", sdt)
        formData.append('sx', sx)
        formData.append('edt', edt)
        formData.append('ex', ex)
        formData.append('nod', nodTotal)
        formData.append('rx', e$('rx').value)

        bdFetchAPI(apiUrl, formData).then((response) => {
            console.log(response);
            if (response[0] != "T") {
                // Error
                if (response[2] != 'x') e$(response[2]).focus()
                e$('dxMessageBoxTitle').innerHTML = 'Error'
                e$('dxMessageBoxBody').innerHTML = response[1]
                e$("dxMessageBox").showModal()

            } else {
                // Success
                window.location.reload()
            }
        });

    }

    function getAllDatesBetween(startDate, endDate) {
        const dates = [];
        let current = new Date(startDate);

        const last = new Date(endDate);

        while (current <= last) {
            dates.push(current.toISOString().split('T')[0]); // Push YYYY-MM-DD
            current.setDate(current.getDate() + 1);
        }

        // console.log(dates)
        return dates;
    }

    function checkDateInLeaves(dt) {
        if (fullDayLeaves.includes(dt) || halfDayLeaves.includes(dt))
            return true; // exists
        else
            return false; // does not exist
    }


    let dateLeaveExists = []

    function checkDateRangeInLeaves(startDate, endDate) {

        const dtRange = getAllDatesBetween(startDate, endDate)
        let flag = 0
        // Reset array
        dateLeaveExists.length = 0

        // console.log(dtRange)
        dtRange.forEach((dt, i) => {
            console.log('dt: ' + dt)

            if (checkDateInLeaves(dt)) {
                dateLeaveExists.push(dt)
                flag += 1
            }
        })

        console.log('dateLeaveExists: ' + dateLeaveExists)

        if (flag > 0)
            return true;
        else
            return false;
    }

    function getISODatePrevious(dt) {
        const date = new Date(dt);
        date.setDate(date.getDate() - 1);
        return date.toISOString().split('T')[0];
    }

    function getISODateNext(dt) {
        const date = new Date(dt);
        date.setDate(date.getDate() + 1);
        return date.toISOString().split('T')[0];
    }

    let nodChainForward = 0
    let nodChainBackward = 0

    function chainLeaveLookupBackward(startDt) {

        rx('chainLeaveLookupBackward :: startDt: ' + startDt)
        // rx(holidays)

        // Reset Var
        nodChainBackward = 0

        let thisDate = startDt
        let stopFlag = 0

        while (stopFlag < 1) {

            prevDate = getISODatePrevious(thisDate)

            if (holidays.includes(prevDate)) {
                rx('Date: ' + prevDate + ' | Holiday')
                nodChainBackward += 1
            } else {
                rx('Date: ' + prevDate + ' | Working')
                stopFlag = 1
            }

            thisDate = prevDate
        }

        // Is chain applicable?
        if (fullDayLeaves.includes(thisDate) && sx == 'F') {
            rx('check date: ' + thisDate + ' is full day leave')
            return true
        } else {
            rx('check date: ' + thisDate + ' is not full day leave')
            return false
        }
    }

    function chainLeaveLookupForward(endDt) {

        rx('chainLeaveLookupForward :: endDt: ' + endDt)
        // rx(holidays)

        // Reset Var
        nodChainForward = 0

        let thisDate = endDt
        let stopFlag = 0

        while (stopFlag < 1) {

            nextDate = getISODateNext(thisDate)

            if (holidays.includes(nextDate)) {
                rx('Date: ' + nextDate + ' | Holiday')
                nodChainForward += 1
            } else {
                rx('Date: ' + nextDate + ' | Working')
                stopFlag = 1
            }

            thisDate = nextDate
        }

        // Is chain applicable?
        if (fullDayLeaves.includes(thisDate) && ex == 'F') {
            rx('check date: ' + thisDate + ' is full day leave')
            return true
        } else {
            rx('check date: ' + thisDate + ' is not full day leave')
            return false
        }
    }
</script>
/*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function dxTimesheetList(user_id) {
    console.log('user_id: ' + user_id)
    dxViewTSopen()
}


function dxViewTSopen() {

    const dx = document.getElementById('dxViewTS')
    dx.showModal()
    document.getElementById("dxCal1").blur()
    
}

function dxViewTSclose() {
    document.getElementById('dxViewTS').close()
}

// Validate data and submit form
function dxGetTS(){

    const dxFormTS = document.getElementById("dxFormTS")

    dxFormTS.submit()
}


// dxUserTS
function dxUserTSopen(){
    document.getElementById("dxUserTS").showModal()
}
function dxUserTSclose(){
    document.getElementById("dxUserTS").close()
}
/*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 31-Jan-2012				|
| Updated On:           				|
+-------------------------------------------------------+
| www.rajarshi.in                                       |
| raj@rajarshi.in                                       |
+-------------------------------------------------------+
*/
var cal1a, cal1b;   // Project Summary
var cal2a, cal2b;   // Teammate Summary

window.onload = function () {
    if ($('#fdt1')) {
        cal1a  = new Epoch('epoch_popup','popup',document.getElementById('fdt1'));
        cal1b  = new Epoch('epoch_popup','popup',document.getElementById('tdt1'));
    }
    if ($('#fdt2')) {
        cal2a  = new Epoch('epoch_popup','popup',document.getElementById('fdt2'));
        cal2b  = new Epoch('epoch_popup','popup',document.getElementById('tdt2'));
    }
};

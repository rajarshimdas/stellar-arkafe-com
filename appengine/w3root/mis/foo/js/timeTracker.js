/*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 02-Feb-2012				|
| Updated On:           				|
+-------------------------------------------------------+
| www.rajarshi.in                                       |
| raj@rajarshi.in                                       |
+-------------------------------------------------------+
*/

function changeVersion(){    

    var ver = document.getElementById('f5ver').value;
    var pid = document.getElementById('f5pid').value;
    //console.log('Version: ' + ver);
    //console.log('Projectid: ' + pid);

    window.location = "timeTracker.cgi?pid=" + pid + "&ver=" + ver;
    
}

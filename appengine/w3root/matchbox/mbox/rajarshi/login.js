/*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 01-Jan-2007				|
| Updated On: 31-Jul-2012     				|
+-------------------------------------------------------+
| www.rajarshi.in                                       |
| raj@rajarshi.in                                       |
+-------------------------------------------------------+
| Require jquery                                        |
+-------------------------------------------------------+
*/

// Set Focus
$(document).ready(function() {
    $('#uname').focus();
});

// Form Validation and Encryption
function formValidate(){

    if ($('#uname').val() === "" && $('#passwd').val() === ""){
        $('#uname').focus();
        return false;
    }

    if ($('#uname').val() === ""){
        $('#uname').focus();
        alert("Enter username.");
        return false;
    }

    if ($('#passwd').val() === ""){
        $('#passwd').focus();
        alert ("Enter Password.");
        return false;
    }
    
    var sid     = $('#sx').val();
    var passwd  = $('#passwd').val();

    // Hash the password
    var md5pw   = hex_md5(sid + passwd);

    // Set the hashed password value
    $('#pw').val(md5pw);

    // Mask the passwd field
    var co      = passwd.length;
    var passwdX = '';
    for (var i = 0; i < co; i++){
        passwdX = (passwdX + 'X');
    }
    $('#passwd').val(passwdX);

    // All done, send to server for login...
    return true;
    
};

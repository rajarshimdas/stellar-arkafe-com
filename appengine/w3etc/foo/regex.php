<?php  /*
+-------------------------------------------------------+
| Rajarshi Das						|
+-------------------------------------------------------+
| Created On: 25-Aug-08					|
| Updated On: 21-Feb-12					|
+-------------------------------------------------------+
| REGEX	Functions					|			
+-------------------------------------------------------+
*/

// Alphanumeric string validation
function check_alphanumeric ($string) {
    return (preg_match ('/^([A-Za-z0-9.]+)+$/', $string));
}

// Numeric data validation
function check_numeric ($string) {
    return (preg_match ('/^[0-9]+/', $string));     
}

// Domain names validation
function check_domain ($domain) {
    return (preg_match ('/([-0-9A-Z]+\.)+' . '([0-9A-Z]){2,6}$/i', trim ($domain)));
}


// Email Address validation
function check_email ($email) {

    // First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }

    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);

    // Check username
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }

    // Check domain
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false;
            // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}

?>
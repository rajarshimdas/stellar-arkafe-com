<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 15-May-2008				                |
| Updated On: 17-Dec-2012				                |
+-------------------------------------------------------+
| Client Configurations                                 |
+-------------------------------------------------------+
*/
if (is_file($_SERVER["DOCUMENT_ROOT"] . '/clientPaths.cgi')) {
    require_once $_SERVER["DOCUMENT_ROOT"] . '/clientPaths.cgi';
} else {
    die("Paths not defined.");
}

// Global Vars
if (is_file($w3etc . '/LocalSettings.php')) {
    require_once $w3etc . '/LocalSettings.php';
} else {
    die("LocalSettings not found.");
}
require_once $w3etc . '/env.php';

// Session Authentication & Setup
require_once $w3etc . '/bootstrap.php';

// Add w3etc folder to include path
set_include_path($new_include_path);

/*
+-------------------------------------------------------+
| Log this request - csv                                |
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Helper Functions                                      |
+-------------------------------------------------------+
*/

// WIP Flag
function rdWorkInProgress()
{
    return '<div class="work-in-progress">
                <h2>Work in progress</h2>
            </div>';
}

// Return JSON Response
function rdReturnJsonHttpResponse($httpCode, $data)
{
    // remove any string that could create an invalid JSON 
    // such as PHP Notice, Warning, logs...
    ob_start();
    ob_clean();

    // this will clean up any previously added headers, to start clean
    header_remove();

    // Set the content type to JSON and charset 
    // (charset can be set to something else)
    // add any other header you may need, gzip, auth...
    header("Content-type: application/json; charset=utf-8");

    // Set your HTTP response code, refer to HTTP documentation
    http_response_code($httpCode);

    // encode your PHP Object or Array into a JSON string.
    // stdClass or array
    echo json_encode($data);

    // making sure nothing is added
    die();
}

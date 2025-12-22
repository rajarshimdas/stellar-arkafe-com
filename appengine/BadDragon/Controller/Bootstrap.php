<?php /* BadDragon 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   29-Jan-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

// Fetch BadDragon config
require_once BD . "Config.php";

// Autoload
if (!defined('BADDRAGON')) {
    require_once BD . 'Autoload.php';
}

// Invoke BadDragon
use BadDragon\Controller;
use BadDragon\Router;

$dragon = new Controller;
$route = new Router;
# var_dump($route);

// Common Functions
require_once BD."Controller/Common.php";

// Get Appengine for this request
$app = $dragon->fire($route);

// Load Controllers | Method | Script/Action
for ($i = 0; $i < count($app); $i++) {
    # echo "Loaded: ".$cn[$i]."<br>";
    require_once $app[$i];
}

// Thats all

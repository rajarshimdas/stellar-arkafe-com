<?php /* 
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   02-Jan-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
require_once W3APP . "/View/Hrms/genResponse.php";

function bdActiveTab(string $thisTab, string $activeTab): string
{
    if ($thisTab == $activeTab)
        return 'class="activeTab"';
    else
        return 'class="normalTab"';
}


function bdActiveLink(string $activeTab, array $navTabLinks, array $routeParts): string
{
    if (!empty($routeParts[4])) {
        // From URI
        $activeLink = $routeParts[4];
        $_SESSION['activelink-' . $activeTab] = $activeLink;
    } elseif (isset($_SESSION['activelink-' . $activeTab])) {
        // From Session Variable
        $activeLink = $_SESSION['activelink-' . $activeTab];
    } else {
        // First Link
        $activeLink = $navTabLinks[0][0];
    }
    
    return $activeLink;
}


function bdNavTabLinks(string $activeTab, string $activeLink, array $navTabLinks, array $routeParts): string
{

    // Generate Links
    $links = "";
    foreach ($navTabLinks as $n):
        $uri = BASE_URL . 'aec/hrms/ui/' . $activeTab . '/' . $n[0];

        $x = explode('-', $activeLink);
        //var_dump($x[0]);
        //echo "( ".$n[0]." | ".$x[0]." )";

        $class = bdActiveTab($n[0], $x[0]);

        $links = $links . '<a href="' . $uri . '" ' . $class . '>' . $n[1] . '</a>';

    endforeach;

    return $links;
}

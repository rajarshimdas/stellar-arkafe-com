<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   01-Jul-2024                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/
# Scope
$s = bdGetProjectScopeArray($mysqli);
$ps = bdGetThisProjectScope($pid, $s, $mysqli);
// var_dump($s);

for ($i = 0; $i < count($s); $i++) {
    //echo $s[$i]["scope"] . ' | ' . $s[$i]["id"] . ' | ' . $ps[$s[$i]["id"]] . '<br>';
    $scope[$s[$i]["scope"]] = [$s[$i]["id"], $ps[$s[$i]["id"]]];
}
// var_dump($scope);

function checkProjectScope(string $shortcode, array $scope): array
{
    if (isset($scope[$shortcode])) {
        if ($scope[$shortcode][1] != "T") {
            return ["F", "Scope not active for this project"];
        }
    } else {
        return ["F", "Scope shortcode is invalid"];
    }

    return ["T", "ok"];
}

# Milestone
$m = bdGetProjectStageArray($mysqli);
// var_dump($m);
for ($i = 0; $i < count($m); $i++) {
    $stage[$m[$i]["stage_sn"]] = $m[$i]["id"];
}
// var_dump($stage);

/*
+-------------------------------------------------------+
| Router                                                |
+-------------------------------------------------------+
*/

// var_dump($route->parts[4]);
$x = isset($route->parts[4]) ? $route->parts[4] : 'form';

if (is_file(__DIR__ . '/import-' . $x . '.php')) {
    require_once __DIR__ . '/import-' . $x . '.php';
} else {
    echo "URI error";
}

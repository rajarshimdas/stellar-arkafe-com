<?php /*
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On: 25-Dec-2024                               |
| Updated On:                                           |
+-------------------------------------------------------+
| LocalSettings.php > $bdModuleAccess['cost']           |
+-------------------------------------------------------+
*/
$moduleName = "Cost MIS";
// Session Vars

// activeFinancialYear
$activeFinancialYear = isset($_SESSION["activeFinancialYear"]) ? $_SESSION["activeFinancialYear"] : bdSetActiveFinancialYear();

// sysShowDeletedUser (Show: 1 | Hide: 0)
$sysShowDeletedUser = isset($_SESSION["sysShowDeletedUser"]) ? $_SESSION["sysShowDeletedUser"] : 0;


/*
+-------------------------------------------------------+
| Fn                                                    |
+-------------------------------------------------------+
*/
function bdSetActiveFinancialYear()
{
    $fy = json_encode(bdFinancialYear());
    $_SESSION["activeFinancialYear"] = $fy;
    return $fy;
}


function bdCostAuth(string $loginname, array $bdModuleAccess): bool
{
    // Can use in_array function instead of loop
    foreach ($bdModuleAccess['cost'] as $user) {
        if (strtolower($loginname) == strtolower($user)) return true;
    }
    return false;
}

function bdShowCostButton(string $loginname, array $bdModuleAccess): bool
{
    if (bdCostAuth($loginname, $bdModuleAccess)) {
        echo '<a class="button" href="' . BASE_URL . 'cost/ui/snapshot/firmwide">Cost MIS</a>';
    }

    return true;
}

function bdManhourCost(object $mysqli): ?array
{
    $query = "select * from manhourcost";

    if ($result = $mysqli->query($query)) {

        while ($row = $result->fetch_assoc()) {

            $costperhour = $row['costperhour']; // In Paise
            $costRs = number_format(($costperhour / 100), 2, '.', '');
            /*
            $intpart = floor($costRs);                  // Rs
            $fraction = $costRs - $intpart;     // Paise as integer
            */

            $x = (string)$costRs;
            $e = explode(".", $x);
            $intpart = $e[0];
            $fraction = empty($e[1]) ? '0' : $e[1];

            $mhCost[$row['finyear']][$row['user_id']] = [
                "costPsPerHour" => $costperhour,
                "costRs"        => $costRs,         # Per Hour
                "rs"            => $intpart,
                "ps"            => $fraction,
            ];
        };

        $result->close();
    }
    return $mhCost;
}


function bdFinCalculateMHCost4User(int $uid, array $fyManhourCost, int $hour, int $minutes): ?array
{
    if (empty($fyManhourCost[$uid])) return null;
    if ($hour < 1 && $minutes < 1) return null;

    $costPsPerHour = $fyManhourCost[$uid]['costPsPerHour'];
    $totalMin = ($hour * 60) + $minutes;

    $costx = (($costPsPerHour / 60) * $totalMin) / 100;

    $cost = number_format($costx, 2, '.', ',');

    return [
        'cost' => $cost,
        'manhours' => bdAddHourMin($hour, $minutes),
        'costFloat' => $costx,
    ];
}


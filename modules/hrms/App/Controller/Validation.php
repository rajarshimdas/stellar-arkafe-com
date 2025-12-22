<?php /* Data Validation Library
+-------------------------------------------------------+
| Rajarshi Das						                    |
+-------------------------------------------------------+
| Created On:   09-Dec-2025                             |
| Updated On:                                           |
+-------------------------------------------------------+
*/

function isAlphaNum(string $str): bool
{
    return preg_match('/^[a-zA-Z0-9 ]+$/', $str) === 1;
}
function isAlphaNumDotAndEmpty(string $str): bool
{
    if (empty($str)) return true;
    return preg_match('/^[a-zA-Z0-9. ]+$/', $str) === 1;
}

function isAlphaNumDot(string $str): bool
{
    return preg_match('/^[a-zA-Z0-9.]+$/', $str) === 1;
}

function isAlphaNumAndSafe(string $str): bool
{
    if (empty($str)) return true;
    return preg_match('/^[a-zA-Z0-9 .,?<>:!@#$%^&*()_+-|\']+$/', $str) === 1;
}

function isValidISODate(string $date): bool
{
    // Format check
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return false;
    }

    // Date validity check
    [$year, $month, $day] = array_map('intval', explode('-', $date));
    return checkdate($month, $day, $year);
}

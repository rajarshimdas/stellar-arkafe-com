<?php

// Used by Holidays
if (isset($_SESSION["activeYear"])) {
    $activeYear = $_SESSION["activeYear"];
} else {
    $activeYear = date("Y");
    $_SESSION["activeYear"] = $activeYear;
}

// Show|Hide deleted Users (default Hidden)
$sysShowDeletedUser = (isset($_SESSION["sysShowDeletedUser"])) ? $_SESSION["sysShowDeletedUser"] : 0;
// $sysShowDeletedUser = 1;

// Show|Hide deleted Projects (default Hidden)
$sysShowDeletedProj = (isset($_SESSION["sysShowDeletedProj"])) ? $_SESSION["sysShowDeletedProj"] : 0;
// $sysShowDeletedProj = 0;

// activeUID
$activeUID = isset($_SESSION["activeUID"]) ? $_SESSION["activeUID"] : 0;

<?php

$pid = $_GET["pid"];
$scopeIds = $_GET["rx"];
// echo 'ids: '.$scopeIds;

// Data Validation
if (!alpha_numeric_comma($scopeIds)){
    die("Data validation failed");
}

// echo 'ids: '.$scopeIds;

$mysqli = cn2();

$query = "update 
            `projectscopemap`
        set 
            `activescopeids` = '$scopeIds'
        where
            `project_id` = $pid";

if ($mysqli->query($query)){
    echo "Saved";
} else {
    echo "Failed";
}

$mysqli->close();


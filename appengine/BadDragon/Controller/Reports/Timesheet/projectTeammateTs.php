<?php

// Get User Inputs
$pid = $_POST["pid"];
$fdt = $_POST["fdt"];
$tdt = $_POST["tdt"];


// Generate Page
$title          = "MIS | Project Team Manhours";
$moduleName     = "MIS";
$returnURL      = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view           = 'Module/mis/projectTeammateTs';

require BD . 'View/Module/mis/Template/generatePage.php';

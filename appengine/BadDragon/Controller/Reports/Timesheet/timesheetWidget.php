<?php 









// Generate Page
$title = "MIS | Timesheet Status";
$moduleName = "MIS";
$returnURL = ["MIS", BASE_URL . 'mis/concert.cgi'];
$view = 'Module/mis/timesheetWidget';

require BD . 'View/Module/mis/Template/generatePage.php';

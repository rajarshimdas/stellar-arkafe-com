<?php /*
+-------------------------------------------------------+
| Rajarshi Das                                          |
+-------------------------------------------------------+
| Created On: 17-Aug-2009       			            |
| Updated On: 						                    |
+-------------------------------------------------------+
| Timesheet :: Edit                                 	|
+-------------------------------------------------------+
*/


/*
+-------------------------------------------------------+
| Updated On: 14-Aug-09                                 |
+-------------------------------------------------------+
| Workaround: To make timesheet module to work using   	|
| using this same code                                  |
+-------------------------------------------------------+
| Debugging Tip                                         |
| Try to get the code working in SMS and then put the   |
| workaround to make it work thourgh TimeSheets         |
+-------------------------------------------------------+
*/

$tsid       = $_GET["tsid"];
$editmode   = "T";

// Get all data regarding this timesheet
require_once 'hot7e/tsid2data.cgi';
// var_dump($timesheetX);

// Cache the number of days to display in Tabulation
$display_no_of_days = $_GET["no"];

// Display the timesheet data entry form
$FormBackgroundColor    = "#e5e599";
$FormButtonName         = "Edit";
$Form_hidden_a          = "timesheet-edit-action";
$Form_show_Cancel       = "Yes";

$form_projectname       = $timesheetX["projectname"];
$form_projectid         = $timesheetX["projectid"];
$form_date              = $timesheetX["date"];
$form_hour              = $timesheetX["no_of_hours"];
$form_min               = $timesheetX["no_of_min"];
$form_task_id           = $timesheetX["task_id"];
$form_task              = $timesheetX["task"];
$form_work              = $timesheetX["work"];
$form_worked_from       = $timesheetX["worked_from"];
$form_subtask           = $timesheetX["subtask"];
$form_projectstage_id   = $timesheetX["projectstage_id"];
$form_projectstage_name = $timesheetX["projectstage_name"];
$dept_name              = $timesheetX["dept_name"];
$form_percent           = $timesheetX["percent"];
$form_scope             = $timesheetX["scope"];     // Project scope added on 08-Feb-24
$form_scope_name        = $timesheetX["scope_name"];
$form_scope_id          = $timesheetX["scope_id"];

// Display the form
include 'hot7e/timesheet-form.cgi';
$mysqli->close();

// Error Prompts if any
$mc = $_GET["mc"];
if ($mc) echo "<br><span style='color:red;font-weight:bold;'>Error :: ".$mc." Please Re-do.";


<?php
/* 
-------------------------------------------------------------------------------
Rajarshi Das
Created:        1:12 PM 13-Feb-07
Last Updated:   1:12 PM 13-Feb-07
--------------------------------------------------------------------------------
Functions for:
--------------------------------------------------------------------------------
id2priority
priority2id
dc2discipline
discipline2dc
id2roles
roles2id
--------------------------------------------------------------------------------
Need:
Valid database connection object: $mysqli
--------------------------------------------------------------------------------
*/

/* -------------------------------------------------------------------------- */
function id2priority($priorityid){
/* -------------------------------------------------------------------------- */
$query = "SELECT flagname FROM priority WHERE id = $priorityid";

if ($result=$mysqli->query($query)) {   
   $row=$result->fetch_row();
   $priority = $row[0];
   $result->close();     
   }
   else {
   printf("Error: %s\n", $mysqli->error);   
} 
return $priority;
}


/* -------------------------------------------------------------------------- */
function priority2id ($priority){
/* -------------------------------------------------------------------------- */
$query = "SELECT id FROM priority WHERE flagname = '$priority'";

if ($result=$mysqli->query($query)) {   
   $row=$result->fetch_row();
   $priorityid = $row[0];
   $result->close();     
   }
   else {
   printf("Error: %s\n", $mysqli->error);   
} 
return $priorityid;
}

?>

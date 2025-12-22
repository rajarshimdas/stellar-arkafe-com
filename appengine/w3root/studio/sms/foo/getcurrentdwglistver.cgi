<?php

if (!$dbincludepath) {
  include 'foo/arnav/dblogin.cgi';
  } else {
  include '$dbincludepath';}

$sql = "SELECT currentdwglistver 
        FROM projects 
        WHERE projectname='$projectname'";

if ($result=$mysqli->query($sql)) {   
   $row=$result->fetch_row();
   $currentdwglistver=$row[0];    
   $result->close();  
   }else {
   printf("Error: %s\n", $mysqli->error);   
} 

$mysqli->close();

?>

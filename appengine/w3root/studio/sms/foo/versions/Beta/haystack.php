<?php /* Haystack */

/* Haystack for compairing drawing identity, unit etc
CREATE TABLE haystack (
  f1integer smallint NOT NULL,
  f2string varchar(4) not null,
  f3blocks varchar(5) NOT NULL,
  f4gfc varchar (5) NOT NULL  
) TYPE=MyISAM;
*/

/*  ENABLE THE NEXT LINE BEFORE USING THIS SCRIPT  
include 'dblogin.cgi'; 

$sql69 = "insert into haystack values (0, '1000', 'MP', 'R0')";
echo "<br>SQL: $sql69";
if (!$mysqli->query($sql69)) printf("<br>Error69: %s\n", $mysqli->error);

$i2 = 1001;
for ($i = 1; $i <= 8999; $i++) {        
    $sql69 = "insert into haystack values ($i, '$i2', 'B$i', 'R$i')";
    echo "<br>SQL: $sql69";
    if (!$mysqli->query($sql69)) printf("<br>Error69: %s\n", $mysqli->error);   
    $i2 = $i2 + 1;
}

$mysqli->close();

*/
?>

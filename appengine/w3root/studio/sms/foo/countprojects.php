<?php 

$db = odbc_connect("vaprojects", "", "") or die("connection Failed");
echo "Connection established";
$sql = "select Jobcode, ProjName from ProjMast";
$rs = odbc_prepare($db, $sql);
odbc_execute($rs) or die("Execuion of sql failed");
$co = 0;
while ($row = odbc_fetch_array($rs)) {
echo "<br>Projectname: $row[ProjName] Jobcode: $row[Jobcode]";
$co++;
}
echo "<br>Total number of Projects: $co";

?>

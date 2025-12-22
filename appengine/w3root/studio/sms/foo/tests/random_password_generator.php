Random Password gentrator:

<?php 

$un = 'rajarshi';

$md5 			= md5($un); // 32 character digest of the username
$random_no 		= rand(5,20);
$random_no_6 	= $random_no + 6;
$md5_array 		= str_split($md5);

for ($i = $random_no; $i <= $random_no_6; $i++) {
    $passwd = $passwd . $md5_array[$i];
}

echo "<br> $md5<br>random: $random_no passwd: $passwd";

?>


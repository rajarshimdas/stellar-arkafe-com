<?php
require_once "Mail.php";

$from = "Rajarshi Das <rajarshi@vagroup.com>";
$to = "hi5 <raj@rajarshi.in>";
$subject = "Hi!";
$body = "Hi,\n\n Test Mail from pear-mail. Testing 123...";

$host = "192.168.0.162";
$username = "rajarshi";
$password = "r24in";

$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
 } else {
  echo("<p>Message successfully sent!</p>");
 }
?>

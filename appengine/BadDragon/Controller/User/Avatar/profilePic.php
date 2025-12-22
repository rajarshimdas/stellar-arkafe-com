<?php /* Upload Avatar */
// https://www.php.net/manual/en/features.file-upload.post-method.php


// var_dump($_FILES);
$extn = [
    "image/jpeg"    => ".jpg",
    "image/png"     => ".png",
];

$e = (strlen($extn[$_FILES["avatarFile"]["type"]]) == 4) ? $extn[$_FILES["avatarFile"]["type"]] : "F";
// die("e: ".$e);

// File format not valid
if ($e == "F") {
    header("Location:".$base_url."studio/home.cgi?e=file-format-invalid");
    die;
}

$uploaddir = $w3root . '/login/box/avatar/';
$filename = $sessionid . '-' . $userid . '-' . rand(100, 999) . $e;
$uploadfile = $uploaddir . $filename;
// echo $uploadfile;

if (!move_uploaded_file($_FILES["avatarFile"]['tmp_name'], $uploadfile)) {
    // echo "Possible file upload attack!\n";
    header("Location:".$base_url."studio/home.cgi?e=file-upload-error");
    die;
}

$mysqli = cn2();
$query = "update `users_a` set `avatar` = '".$filename."' where `user_id` = ".$userid;
// die($query);

$mysqli->query($query);

header("Location:".$base_url."studio/home.cgi");

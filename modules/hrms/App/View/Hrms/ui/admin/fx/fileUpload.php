<?php
if (!empty($_FILES['file']['name'])) {

    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filename  = basename($_FILES["file"]["name"]);
    $filepath  = $uploadDir . $filename;
    $mime      = $_FILES['file']['type'];

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) {
        // Save path into database
        $pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");

        $stmt = $pdo->prepare("INSERT INTO documents (filename, filepath, mime_type) VALUES (?, ?, ?)");
        $stmt->execute([$filename, $filepath, $mime]);

        echo "File uploaded and saved.";
    }
}

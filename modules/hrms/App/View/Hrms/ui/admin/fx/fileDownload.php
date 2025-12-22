<?php
$id = $_GET['id'];

$pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
$stmt = $pdo->prepare("SELECT * FROM documents WHERE id = ?");
$stmt->execute([$id]);
$doc = $stmt->fetch();

header("Content-Type: " . $doc['mime_type']);
header("Content-Disposition: attachment; filename=\"" . $doc['filename'] . "\"");
readfile($doc['filepath']);
exit;

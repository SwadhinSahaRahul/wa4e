<?php
require_once "pdo.php";

$stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
$stmt->execute(array(':prefix' => $_REQUEST['term'] . "%"));
$institutions = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $institutions[] = $row['name'];
}

echo(json_encode($institutions, JSON_PRETTY_PRINT));
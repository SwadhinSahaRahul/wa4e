<?php

session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

require_once('pdo.php');

$sql = $pdo->query("select * from autos");
$autos = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Swadhin Saha's Automobile Tracker</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>

    <?php
    if ($_SESSION['success']) {
        // Look closely at the use of single and double quotes
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
    }
    unset($_SESSION['success']);
    ?>

    <h2>Automobiles</h2>
    <ul>
        <?php
        foreach ($autos as $auto) {
            echo "<li>" . $auto['year'] . " " . htmlentities($auto['make']) . " / " . $auto['mileage'] . "</li>";
        }
        ?>
    </ul>
    <p>
        <a href="add.php">Add New</a> |
        <a href="logout.php">Logout</a>
    </p>
</div>
</body>
</html>


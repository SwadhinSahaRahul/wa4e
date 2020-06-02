<?php
session_start();
require_once 'pdo.php';
require_once "utils.php";

if (isset($_GET['profile_id'])) {
    $sql = $pdo->prepare('select * from Profile where profile_id = :profile_id');
    $sql->execute([
        ':profile_id' => $_GET['profile_id']
    ]);

    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = "Could not load profile";
        header("Location:index.php");
        return;
    }

    $positions = loadPos($pdo, $_GET['profile_id']);
    $educations = loadEdu($pdo, $_GET['profile_id']);

} else {
    $_SESSION['error'] = "Missing profile_id";
    header("Location:index.php");
    return;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swadhin Saha</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Profile information</h1>
    <p>First Name:
        <?= htmlentities($row['first_name']) ?></p>
    <p>Last Name:
        <?= htmlentities($row['last_name']) ?></p>
    <p>Email:
        <?= htmlentities($row['email']) ?></p>
    <p>Headline:<br/>
        <?= htmlentities($row['headline']) ?></p>
    <p>Summary:<br/>
    <?= htmlentities($row['summary']) ?><p>
    </p>
    <p>Position
    <ul>
        <?php
        foreach ($positions as $position) {
            echo "<li>" . htmlentities($position['year'])
                . ": " . htmlentities($position['description'])
                . "</li>";
        }
        ?>
    </ul>

    <p>Education
    <ul>
        <?php
        foreach ($educations as $education) {
            echo "<li>" . htmlentities($education['year'])
                . ": " . htmlentities($education['name'])
                . "</li>";
        }
        ?>
    </ul>

    <a href="index.php">Done</a>
</div>
</body>
</html>
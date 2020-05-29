<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header('Location:index.php');
    return;
}

if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM Profile WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':profile_id' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header('Location: index.php');
    return;
}

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}


$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :profile_id and user_id=:user_id");
$stmt->execute(array(
    ":profile_id" => $_GET['profile_id'],
    ":user_id" => $_SESSION['user_id']
));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Could not load profile';
    header('Location: index.php');
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
    <h1>Deleting Profile</h1>
    <p>First Name: <?= htmlentities($row['first_name']) ?></p>
    <p>Last Name: <?= htmlentities($row['last_name']) ?></p>

    <form method="post">
        <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
        <input type="submit" value="Delete" name="delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
</html>


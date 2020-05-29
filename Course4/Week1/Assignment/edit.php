<?php
require_once "pdo.php";
session_start();

require_once "pdo.php";
session_start();

if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header('Location:index.php');
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) {

    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    $sql = "UPDATE Profile SET 
                 first_name = :first_name,
                 last_name = :last_name,
                 email = :email,    
                 headline = :headline,    
                 summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':profile_id' => $_POST['profile_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary']));
    $_SESSION['success'] = 'Record updated';
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

$profile_id = $row['profile_id'];
$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = $row['summary'];
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
    <h1>Editing Profile for <?= $_SESSION['name'] ?>></h1>

    <?php
    // Flash pattern
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    ?>
    <form method="post">
        <p>First Name:
            <input type="text" name="first_name" size="60" value="<?= $first_name ?>"></p>
        <p>Last Name:
            <input type="text" name="last_name" size="60" value="<?= $last_name ?>"></p>
        <p>Email:
            <input type="text" name="email" size="30" value="<?= $email ?>"></p>
        <p>Headline:<br>
            <input type="text" name="headline" size="80" value="<?= $headline ?>"></p>
        <p>Summary:<br>
            <textarea name="summary" rows="8" cols="80"><?= $summary ?></textarea>
        </p>
        <p>
            <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>
</body>
</html>
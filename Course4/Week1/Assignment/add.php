<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header("Location:index.php");
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: add.php");
        return;
    }

    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary)
              VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':user_id' => $_SESSION['user_id'],
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary']));
    $_SESSION['success'] = 'Record Added';
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
    <h1>Adding Profile for <?= $_SESSION['name'] ?></h1>

    <?php
    // Flash pattern
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    ?>

    <form method="post">
        <p>First Name:
            <input type="text" name="first_name" size="60"/></p>
        <p>Last Name:
            <input type="text" name="last_name" size="60"/></p>
        <p>Email:
            <input type="text" name="email" size="30"/></p>
        <p>Headline:<br/>
            <input type="text" name="headline" size="80"/></p>
        <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"></textarea>
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>
</body>
</html>


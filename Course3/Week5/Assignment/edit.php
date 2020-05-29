<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['email'])) {
    die("ACCESS DENIED");
}

if (isset($_POST['make']) && isset($_POST['model'])
    && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['id'])) {

    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1
        || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?id=" . $_POST['id']);
        return;
    }

    if (!is_numeric($_POST['year'])) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: edit.php?id=" . $_POST['id']);
        return;
    }

    if (!is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage must be numeric';
        header("Location: edit.php?id=" . $_POST['id']);
        return;
    }

    $sql = "UPDATE autos SET 
                 make = :make,
                 model = :model,
                 year = :year,    
                 mileage = :mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['id']));
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Missing id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: index.php');
    return;
}

// Flash pattern
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
?>
<p>Edit User</p>
<form method="post">
    <p>Make:
        <input type="text" name="make" value="<?= $make ?>"></p>
    <p>Model:
        <input type="text" name="model" value="<?= $model ?>"></p>
    <p>Year:
        <input type="text" name="year" value="<?= $year ?>"></p>
    <p>Mileage:
        <input type="text" name="mileage" value="<?= $mileage ?>"></p>
    <input type="hidden" name="id" value="<?= $auto_id ?>">
    <p><input type="submit" value="Save"/>
        <a href="index.php">Cancel</a></p>
</form>

<?php
session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

require_once('pdo.php');

if (isset($_POST['add'])) {
    if (strlen($_POST['make']) < 1) {
        $v_error = 'Make is required';
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['year'])) {
        $v_error = 'Mileage and year must be numeric';
    } else {
        $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
                ':mk' => $_POST['make'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = 'Record inserted';
        header("Location:view.php");
        return;
    }

    $_SESSION['error'] = $v_error;
    header("Location:add.php");
    return;
}

if (isset($_POST['cancel'])) {
    header("Location:view.php");
    return;
}

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
    <form method="post">

        <?php
        if ($_SESSION['error']) {
            // Look closely at the use of single and double quotes
            echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        }
        unset($_SESSION['error']);
        ?>

        <p><label for="make">Make:</label>
            <input type="text" id="make" name="make">
        </p>
        <p><label for="year">Year:</label>
            <input type="text" id="year" name="year"></p>
        <p>
            <label for="mileage">Mileage:</label>
            <input type="text" id="mileage" name="mileage">
        </p>
        <p>
            <input type="submit" name="add" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>
</body>
</html>

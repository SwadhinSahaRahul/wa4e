<?php

require_once('pdo.php');

// Demand a GET parameter
if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

$v_error = false;
$success = false;

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
        $success = 'Record inserted';
    }
}

$autos_sql = $pdo->query("select * from autos");
$autos = $autos_sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Swadhin Saha</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Tracking Autos for</h1>
    <?php
    if (isset($_REQUEST['name'])) {
        echo "<p>Welcome: ";
        echo htmlentities($_REQUEST['name']);
        echo "</p>\n";
    }
    ?>
    <form method="post">

        <?php
        // Note triple not equals and think how badly double
        // not equals would work here...
        if ($v_error !== false) {
            // Look closely at the use of single and double quotes
            echo('<p style="color: red;">' . htmlentities($v_error) . "</p>\n");
        }

        if ($success !== false) {
            // Look closely at the use of single and double quotes
            echo('<p style="color: green;">' . htmlentities($success) . "</p>\n");
        }
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
            <input type="submit" name="logout" value="Logout">
        </p>
    </form>

    <h1>Automobiles</h1>
    <ul>
        <?php
        foreach ($autos as $auto) {
            echo "<li>" . $auto['year'] . " " . htmlentities($auto['make']) . " / " . $auto['mileage'] . "</li>";
        }
        ?>

    </ul>

</div>
</body>
</html>

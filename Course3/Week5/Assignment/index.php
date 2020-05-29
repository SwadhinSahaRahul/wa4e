<?php
require_once "pdo.php";
session_start();
?>
<html lang="en">
<head>
    <title>Swadhin Saha</title>
</head>
<body>
<?php

if (isset($_SESSION['email'])) {

    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
        unset($_SESSION['success']);
    }

    $stmt = $pdo->query("SELECT * FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (sizeof($rows)) {
        echo('<table border="1">' . "\n");
        foreach ($rows as $row) {
            echo "<tr><td>";
            echo(htmlentities($row['make']));
            echo("</td><td>");
            echo(htmlentities($row['model']));
            echo("</td><td>");
            echo(htmlentities($row['year']));
            echo("</td><td>");
            echo(htmlentities($row['mileage']));
            echo("</td><td>");
            echo('<a href="edit.php?id=' . $row['auto_id'] . '">Edit</a> / ');
            echo('<a href="delete.php?id=' . $row['auto_id'] . '">Delete</a>');
            echo("</td></tr>\n");
        }
        echo('</table>');
    } else {
        echo('<p>No rows found</p>');
    }

    echo('<a href="add.php">Add New Entry</a>' . "\n");
    echo('<a href="logout.php">Logout</a>');
} else {
    echo('<h1>Welcome to the Automobiles Database</h1>');
    echo('<a href="login.php">Please log in</a>');
}

?>
</body>

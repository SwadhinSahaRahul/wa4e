<?php
session_start();
require_once('pdo.php');

$stmt = $pdo->query("SELECT * FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html lang="en">
<head>
    <title>Swadhin Saha</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <?php

    echo('<h1>Swadhin Saha\'s Resume Registry</h1>');

    if (isset($_SESSION['name']) && isset($_SESSION['user_id'])) {

        echo('<a href="logout.php">Logout</a>');

        if (isset($_SESSION['error'])) {
            echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
            unset($_SESSION['success']);
        }

        if (sizeof($rows)) {
            echo('<table>' . "\n");
            echo('<thead>
                <tr>
                    <td>Name</td>
                    <td>Headline</td>
                    <td>Action</td>
                </tr>
              </thead>');

            foreach ($rows as $row) {
                echo "<tr><td>";
                echo '<a href="view.php?profile_id=' . $row['profile_id'] . '">' . htmlentities($row['first_name'] . ' ' . $row['last_name']) . '</a>';
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                echo("</td><td>");
                echo('<a href="edit.php?profile_id=' . $row['profile_id'] . '">Edit</a> / ');
                echo('<a href="delete.php?profile_id=' . $row['profile_id'] . '">Delete</a>');
                echo("</td></tr>\n");
            }
            echo('</table>');
        } else {
            echo('<p>No rows found</p>');
        }

        echo('<a href="add.php">Add New Entry</a>' . "\n");
    } else {
        echo('<a href="login.php">Please log in</a>');
        if (sizeof($rows)) {
            echo('<table border="1">' . "\n");
            echo('<thead>
                <tr>
                    <td>Name</td>
                    <td>Headline</td>
                </tr>
              </thead>');

            echo '<tbody>';
            foreach ($rows as $row) {
                echo "<tr><td>";
                echo '<a href="view.php?profile_id=' . $row['profile_id'] . '">' . htmlentities($row['first_name'] . ' ' . $row['last_name']) . '</a>';
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                echo("</td><tr>");
            }
            echo '</tbody>';
            echo('</table>');
        } else {
            echo('<p>No rows found</p>');
        }
    }

    ?>
</div>
</body>

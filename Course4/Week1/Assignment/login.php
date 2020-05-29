<?php // Do not put any HTML above this line

session_start();

require_once('pdo.php');

$salt = 'XyZzy12*_';

$failure = false;  // If we have no POST data

if (isset($_POST['cancel'])) {
    header("Location:index.php");
    return;
}
// Check to see if we have some POST data, if we do process it
if (isset($_POST['email']) && isset($_POST['pass'])) {

    unset($_SESSION["name"]);
    unset($_SESSION["user_id"]);

    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $failure = "User name and password are required";
    } elseif (strpos($_POST['email'], '@') === false) {
        $failure = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
                                        WHERE email = :em AND password = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
    $_SESSION['error'] = $failure;
    header("Location: login.php");
    return;
}


// Fall through into the View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Swadhin Saha's Login Page</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
    }
    unset($_SESSION['error']);
    ?>
    <form method="POST">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="text" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find a password hint
        in the HTML comments.
        <!-- Hint: The password is the language we have been learning now followed by 123. -->
    </p>
</div>
<script>
    function doValidate() {
        try {
            email = document.getElementById('email').value;
            pw = document.getElementById('id_1723').value;

            console.log('validating');
            if (pw == null || pw === "" || email == null || email === "") {
                alert("Both fields must be filled out");
                console.log('f a');
                return false;
            } else if (!email.includes('@')) {
                alert("Invalid email address");
                console.log('f @');
                return false;
            } else {
                console.log('t');
                return true;
            }

        } catch (e) {
            console.log(e);
            return false;
        }
    }
</script>
</body>
</html>

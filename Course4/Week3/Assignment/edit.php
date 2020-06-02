<?php
session_start();
require_once "pdo.php";
require_once "utils.php";

require_once "utils.php";

if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header('Location:index.php');
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) {

    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
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

    $sql = "delete from Position where profile_id=:profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':profile_id' => $_POST['profile_id'],
    ]);

    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        $stmt = $pdo->prepare('INSERT INTO Position (profile_id, `rank`, year, description) VALUES ( :pid, :rank, :year, :desc)');

        $stmt->execute(array(
                ':pid' => $_POST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
        );

        $rank++;
    }


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

$positions = loadPos($pdo, $_GET['profile_id']);

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
    flashMessage();
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

        <p>Position:
            <button type="button" id="addPos" value="+"></button>
        </p>
        <div id="position_fields">
            <?php
            foreach ($positions as $index => $position_row) {
                $position = $index + 1;
                echo "<div id=\"position" . $position . "\">
  <p>Year: <input type=\"text\" name=\"year" . $position . "\" value=\"" . htmlentities($position_row['year']) . "\">
  <input type=\"button\" value=\"-\" onclick=\"$('#position" . $position . "').remove();return false;\"></p>
  <textarea name=\"desc" . $position . "\" rows=\"8\" cols=\"80\">" . htmlentities($position_row['description']) . "</textarea></div>";
            }
            $position++;
            ?>
        </div>
        <p>
            <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>
<script type="text/javascript">
    position = <?= sizeof($positions) + 1 ?>;

    $('#addPos').click(function (event) {
        event.preventDefault();
        if (position <= 9) {
            console.log(position);
            form =
                '<div id="position' + position + '"> \
                <p>Year: <input type="text" name="year' + position + '" value=""> \
                <input type="button" value="-" onclick="$(\'#position' + position + '\').remove(); return false;"></p> \
                <textarea name="desc' + position + '" rows="8" cols="80"></textarea></div>';
            $('#position_fields').append(form);
            position++;
        } else {
            alert("Maximum of nine position entries exceeded");
            return false;
        }
    });
</script>

</body>
</html>
<?php
require_once "pdo.php";
session_start();

require_once "utils.php";

if (!isset($_SESSION['name']) || !isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (isset($_POST['cancel'])) {
    header("Location:index.php");
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name'])
    && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: add.php");
        return;
    }

    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: add.php");
        return;
    }

    $msg = validateEdu();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
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
    $profile_id = $pdo->lastInsertId();

    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        $stmt = $pdo->prepare('INSERT INTO Position (profile_id, `rank`, year, description) VALUES ( :pid, :rank, :year, :desc)');

        $stmt->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
        );

        $rank++;

    }

    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['edu_year' . $i])) continue;
        if (!isset($_POST['edu_school' . $i])) continue;

        $edu_year = $_POST['edu_year' . $i];
        $edu_school = $_POST['edu_school' . $i];

        $existing_institution = $pdo->prepare("select * from Institution where name=:edu_school");
        $existing_institution->execute([
            ':edu_school' => $edu_school
        ]);

        if ($row = $existing_institution->fetch(PDO::FETCH_ASSOC)) {
            $institution_id = $row['institution_id'];
        } else {
            $stmt = $pdo->prepare("Insert into Institution (name) values (:edu_school)");
            $stmt->execute([':edu_school' => $edu_school]);
            $institution_id = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare('INSERT INTO Education (profile_id, institution_id, `rank`, year) VALUES ( :pid, :institution_id, :rank, :year)');

        $stmt->execute(array(
                ':pid' => $profile_id,
                ':institution_id' => $institution_id,
                ':rank' => $rank,
                ':year' => $edu_year)
        );

        $rank++;
    }

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
    flashMessage();
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

        <p>Position:
            <button type="button" id="addPos">+</button>
        </p>
        <div id="position_fields">

        </div>

        <p>Education:
            <button type="button" id="addEdu">+</button>
        </p>
        <div id="education_fields">

        </div>

        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>

    </form>

    <script type="text" id="edu_form">
    <div id="edu@POS@">
        <p>Year: <input type="text" name="edu_year@POS@" value="">
                <input type="button" value="-" onclick="$('#edu@POS@').remove();return false;">
        </p>
        <p>School: <input type="text" size="80" name="edu_school@POS@" class="school ui-autocomplete-input" value="" autocomplete="off">
        </p>
    </div>
    </script>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        position = 1;
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

        edu_position = 1;
        $('#addEdu').click(function (event) {
            event.preventDefault();
            if (edu_position <= 9) {
                var edu_form = $('#edu_form').html();
                $('#education_fields').append(edu_form.replace(/@POS@/g, edu_position));
                edu_position++;
                $('.ui-autocomplete-input').autocomplete({
                    source: 'school.php'
                })
            } else {
                alert("Maximum of nine education entries exceeded");
                return false;
            }
        });
    });

</script>
</body>
</html>


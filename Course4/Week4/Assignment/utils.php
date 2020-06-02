<?php
function flashMessage()
{
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
        unset($_SESSION['success']);
    }
}

function validatePos()
{
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }

        if (!is_numeric($year)) {
            return "Position year must be numeric";
        }
    }
    return true;
}

function validateEdu()
{
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['edu_year' . $i])) continue;
        if (!isset($_POST['edu_school' . $i])) continue;

        $year = $_POST['edu_year' . $i];
        $desc = $_POST['edu_school' . $i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }

        if (!is_numeric($year)) {
            return "Education year must be numeric";
        }
    }
    return true;
}


function validateProfile()
{
    // Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
        || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        return 'All fields are required';
    }

    if (strpos($_POST['email'], '@') === false) {
        return 'Email address must contain @';
    }

    return true;
}

function loadPos($pdo, $profile_id)
{
    $position_sql = "select * from Position where profile_id=:profile_id";
    $prepared = $pdo->prepare($position_sql);
    $prepared->execute([
        ':profile_id' => $profile_id
    ]);
    return $prepared->fetchAll(PDO::FETCH_ASSOC);
}

function loadEdu($pdo, $profile_id)
{
    $position_sql = "select * from Education 
                            join Institution I on Education.institution_id = I.institution_id 
                            where profile_id=:profile_id";
    $prepared = $pdo->prepare($position_sql);
    $prepared->execute([
        ':profile_id' => $profile_id
    ]);
    return $prepared->fetchAll(PDO::FETCH_ASSOC);
}
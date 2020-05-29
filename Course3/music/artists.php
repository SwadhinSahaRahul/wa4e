<?php

require_once('../pdo.php');

$artists = $pdo->query('select * from Artist');

echo "<pre>\n";

echo
"<table>
<thead>
    <th>
        <td>Id</td>
        <td>Name</td>
    </th>
</thead>

</table>";

try {

    $sql = $pdo->prepare("select * from Track where album_id=:album_id");
    $sql->execute([
        ':album_id' => 1
    ]);

    $tracks = $sql->fetchAll(PDO::FETCH_ASSOC);
    print_r($tracks);
    while ($row = $artists->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $exception) {
    echo "Something Went Wrong.";
    error_log($exception->getMessage());
}

echo "</pre>\n";
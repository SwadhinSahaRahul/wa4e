<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'root', '1');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
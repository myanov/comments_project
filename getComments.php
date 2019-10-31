<?php
require_once 'db.php';
$sql = "SELECT * FROM user u JOIN user_register r ON u.user_id = r.id WHERE visible=1 ORDER BY date DESC";

$statement = $pdo->prepare($sql);
$statement->execute();

$comments = $statement->fetchAll();

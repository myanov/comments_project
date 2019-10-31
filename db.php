<?php
$driver = 'mysql';
$host = 'localhost';
$db_name = 'marlin1';
$charset = 'utf8';
$user = 'root';
$password = 'root';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

$dsn = "$driver:host=$host;dbname=$db_name;charset=$charset";

$pdo = new PDO($dsn, $user, $password, $options);

session_start();
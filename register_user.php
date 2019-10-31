<?php
require_once 'db.php';
if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $sql = "SELECT * FROM `user_register` WHERE email='{$_COOKIE['email']}' AND password1='{$_COOKIE['password']}'";
    if($result = $pdo->query($sql)->fetch()) {
        $_SESSION['login_user'] = $result;
    }
}
$sql = "INSERT INTO `user_register` (name, email, password1, password2) VALUES (:username, :email, :password, :password_confirmation)";
$statement = $pdo->prepare($sql);
$sql_email = "SELECT * FROM `user_register` WHERE email='{$_POST['email']}'";
$statement_email = $pdo->query($sql_email);
$email = $statement_email->fetchAll();

if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirmation'])) {
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        if(!$email) {
            if(strlen($_POST['password']) >= 6 && strlen($_POST['password_confirmation']) >=6) {
                if($_POST['password'] === $_POST['password_confirmation']) {
                    $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $_POST['password_confirmation'] = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
                    $statement->execute($_POST);
                } else {
                    $_SESSION['reg_error5'] = '<span style="color: red;" "><strong>Пароли должны быть одинаковы</strong></span>';
//        unset($_SESSION['reg_error5']);
                }
            } else {
                $_SESSION['reg_error7'] = '<span style="color: red;" "><strong>Минимальная длина пароля 6 символов</strong></span>';
//            unset($_SESSION['reg_error7']);
            }
        } else {
            $_SESSION['reg_error8'] = '<span style="color: red;" "><strong>Пользователь с таким email уже зарегестрирован</strong></span>';
//            unset($_SESSION['reg_error8']);
        }
    } else {
        $_SESSION['reg_error6'] = '<span style="color: red;" "><strong>Неправильное написание email</strong></span>';
//        unset($_SESSION['reg_error5']);
    }
}

if(empty($_POST['username'])) {
    $_SESSION['reg_error1'] = '<span style="color: red;" "><strong>Ошибка валидации</strong></span>';
//    unset($_SESSION['reg_error1']);
}
if(empty($_POST['email'])) {
    $_SESSION['reg_error2'] = '<span style="color: red;" "><strong>Ошибка валидации</strong></span>';
//    unset($_SESSION['reg_error2']);
}
if(empty($_POST['password'])) {
    $_SESSION['reg_error3'] = '<span style="color: red;" "><strong>Ошибка валидации</strong></span>';
//    unset($_SESSION['reg_error3']);
}
if(empty($_POST['password_confirmation'])) {
    $_SESSION['reg_error4'] = '<span style="color: red;" "><strong>Ошибка валидации</strong></span>';
//    unset($_SESSION['reg_error4']);
}

header("Location: register.php");
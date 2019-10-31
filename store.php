<?php
require_once 'db.php';
$sql = "INSERT INTO `user` (comment, user_id) VALUES (:text, '{$_SESSION['login_user']['id']}')";
$statement = $pdo->prepare($sql);

if($_POST['text'] != '') {
    if($statement->bindParam(':text', $_POST['text'])) {
        $statement->execute();
        $_SESSION['message'] = 'Комментарий успешно добавлен';
    }
}

//if($_POST['name'] == '') {
//    $_SESSION['error1'] = '<p style="color: red; font-size: 15px;">Это поле обязательно для заполнения!</p>';
//}
if($_POST['text'] == '') {
    $_SESSION['error2'] = '<p style="color: red; font-size: 15px;">Это поле обязательно для заполнения!</p>';
}

header("Location: index.php");
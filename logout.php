<?php
require_once 'db.php';
if(isset($_SESSION['login_user'])) {
    unset($_SESSION['login_user']);
}
setcookie('email', 0, time());
setcookie('password', 0, time());


header("Location: index.php");
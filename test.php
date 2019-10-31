<?php
require_once 'db.php';

$sql = "SELECT * FROM user u JOIN user_register r ON u.user_id = r.id";
//$sql = "SELECT * FROM user u JOIN user_register r ON u.user_id = r.id ORDER BY date DESC";
//$sql = "INSERT INTO user (name, comment, user_id) VALUES (:name, :comment, '{$_SESSION['name']}')";
//$sql = "SELECT * FROM user_register WHERE id = 25";
$statement = $pdo->prepare($sql);
$statement->execute();
echo '<pre>';
print_r($statement->fetchAll());
echo '</pre>';

//if(isset($_POST['btn'])) {
//    if(empty($_FILES['file']['error'])) {
//        echo 'Upload';
//    } else {
//        echo 'Not upload';
//    }
//    echo '<pre>';
//    print_r($_FILES);
//    echo '</pre>';
//}
//
//?>
<!---->
<!--<form action="--><?php //echo $_SERVER['PHP_SELF'] ?><!--" enctype="multipart/form-data" method="post">-->
<!--    <input type="file" name="file">-->
<!--    <button type="submit" name="btn">Upload</button>-->
<!--</form>-->

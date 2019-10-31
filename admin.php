<?php
require_once 'db.php';

$sql = "SELECT * FROM user u JOIN user_register r ON u.user_id = r.id ORDER BY date DESC";
$statement = $pdo->prepare($sql);
$statement->execute();
$comments = $statement->fetchAll();

if(isset($_GET['id']) && isset($_GET['ban'])) {
    if ($_GET['ban'] == 0) {
        $sql = "UPDATE `user` SET visible=1 WHERE comment_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $_GET['id']);
        $statement->execute();
    }
    if ($_GET['ban'] == 1) {
        $sql = "UPDATE `user` SET visible = 0 WHERE comment_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $_GET['id']);
        $statement->execute();
    }
    header("Location: {$_SERVER['PHP_SELF']}");
}
if(isset($_GET['id']) && isset($_GET['del'])) {
    if($_GET['del'] == true) {
        $sql = "DELETE FROM `user` WHERE comment_id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $_GET['id']);
        $statement->execute();
        header("Location: {$_SERVER['PHP_SELF']}");
    }
}
//  Нужно еще дописать условие при ктором эта страница будет доступна,
//  это ведь админка))
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Админ панель</h3></div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Аватар</th>
                                            <th>Имя</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($comments as $comment): ?>
                                        <tr>
                                                <td>
                                                    <img src="<?php echo (!empty($comment['img']) ? $comment['img'] : 'img/no-user.jpg') ?>" alt="" class="img-fluid" width="64" height="64">
                                                </td>
                                                <td><?php echo $comment['name'] ?></td>
                                                <td><?php echo $comment['date'] ?></td>
                                                <td><?php echo $comment['comment'] ?></td>
                                                <td>
                                                    <?php if($comment['visible'] == 0): ?>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $comment['comment_id'] ?>&ban=0" class="btn btn-success">Разрешить</a>
                                                    <?php else: ?>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $comment['comment_id'] ?>&ban=1" class="btn btn-warning">Запретить</a>
                                                    <?php endif; ?>
                                                    <a href="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $comment['comment_id'] ?>&del=true" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                                                </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

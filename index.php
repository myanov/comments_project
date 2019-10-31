<?php
require_once 'db.php';
if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $sql = "SELECT * FROM `user_register` WHERE email='{$_COOKIE['email']}' AND password1='{$_COOKIE['password']}'";
    if($result = $pdo->query($sql)->fetch()) {
        $_SESSION['login_user'] = $result;
    }
}
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
                        <?php if(isset($_SESSION['login_user']) && !empty($_SESSION['login_user'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php">Профиль</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><h3>Комментарии</h3></div>

                            <div class="card-body">
                                <?php
                                    if(isset($_SESSION['message'])):?>
                                        <div class="alert alert-success" role="alert">
                                        <?php
                                        echo $_SESSION['message'];
                                        unset($_SESSION['message']);
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                                <?php
                                    require_once 'getComments.php';
                                    foreach ($comments as $comment):
                                        $comment['date'] = date('d/m/Y', strtotime($comment['date']));
                                ?>
                                <div class="media">
                                  <img src="<?= $comment['img'] ?>" class="mr-3" alt="..." width="64" height="64">
                                  <div class="media-body">
                                    <h5 class="mt-0"><?= htmlspecialchars($comment['name'], ENT_QUOTES) ?></h5>
                                    <span><small><?= $comment['date'] ?></small></span>
                                    <p>
                                        <?= htmlspecialchars($comment['comment'], ENT_QUOTES) ?>
                                    </p>
                                  </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($_SESSION['login_user'])): ?>
                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="store.php" method="post">
<!--                                    <div class="form-group">-->
<!--                                    --><?php //if(isset($_SESSION['error1'])) {
//                                        echo $_SESSION['error1'];
//                                        unset($_SESSION['error1']);
//                                    } ?>
<!--                                    <label for="exampleFormControlTextarea1">Имя</label>-->
<!--                                    <input name="name" class="form-control" id="exampleFormControlTextarea1" />-->
<!--                                  </div>-->
                                  <div class="form-group">
                                      <?php if(isset($_SESSION['error2'])) {
                                          echo $_SESSION['error2'];
                                          unset($_SESSION['error2']);
                                      } ?>
                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                  </div>
                                  <button type="submit" class="btn btn-success">Отправить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-md-6 text-center alert-primary" style="margin-top: 30px; border-radius: 10px; padding: 10px;">
                        <p style="margin: 0;">Чтобы оставлять комментарии вы должны <a href="login.php">авторизоваться</a></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

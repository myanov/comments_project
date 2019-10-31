<?php
require_once 'db.php';

if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $sql = "SELECT * FROM `user_register` WHERE email='{$_COOKIE['email']}' AND password1='{$_COOKIE['password']}'";
    if($result = $pdo->query($sql)->fetch()) {
        $_SESSION['login_user'] = $result;
    }
}

$data = $_POST;
$errors = [];
if(isset($data['log_in'])) {
    $data['email'] = trim($data['email']);
    $data['password'] = trim($data['password']);
    if(strlen($data['email']) != 0 && strlen($data['password']) != 0) {
        if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT * FROM `user_register` WHERE email='{$data['email']}'";
            if($result = $pdo->query($sql)->fetch()) {
                if(password_verify($data['password'], $result['password1'])) {
                    if(isset($data['remember']) && $data['remember'] == 1) {
                        setcookie('email', $result['email'], time() + 60*60*24*30);
                        setcookie('password', $result['password1'], time() + 60*60*24*30);
                        $_SESSION['login_user'] = $result;
                        $message = "Здравствуйте, {$_SESSION['login_user']['name']} <br> Вы успешно авторизованы! <br> <a href='index.php'>Перейти на главную страницу</a>";
                    } else {
                        setcookie('email', $result['email'], time());
                        setcookie('password', $result['password1'], time());
                        $_SESSION['login_user'] = $result;
                        $message = "Здравствуйте, {$_SESSION['login_user']['name']} <br> Вы успешно авторизованы! <br> <a href='index.php'>Перейти на главную страницу</a>";
                    }
                } else {
                    $errors[] = "Неверный пароль";
                }
            } else {
                $errors[] = "Пользователя с таким email не существует";
            }
        } else {
            $errors[] = "Неверный формат email";
        }
    } else {
        $errors[] = "Все поля должны быть заполнены";
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
                    <div class="col-md-8">
                        <div class="card">
                            <?php
                            if(isset($_SESSION['login_user'])) {
                                echo $message;
                            }
                            ?>
                            <div class="card-header">Login</div>
                            <?php
                            if(isset($errors) && !empty($errors)) {
                                echo $errors[0];
                            }
                            ?>
                            <div class="card-body">
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"  autocomplete="email" autofocus >
<!--                                                <span class="invalid-feedback" role="alert">-->
<!--                                                    <strong>Ошибка валидации</strong>-->
<!--                                                </span>-->
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password"  autocomplete="current-password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember" >

                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" name="log_in" class="btn btn-primary">
                                               Login
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

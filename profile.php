<?php
require_once 'db.php';

if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $sql = "SELECT * FROM `user_register` WHERE email='{$_COOKIE['email']}' AND password1='{$_COOKIE['password']}'";
    if($result = $pdo->query($sql)->fetch()) {
        $_SESSION['login_user'] = $result;
    }
}

if(isset($_POST['edit'])) {
    $data = $_POST;
    $data['name'] = trim($data['name']);
    $data['email'] = trim($data['email']);
    $errors = [];
    if(strlen($data['name']) > 0 && strlen($data['email']) > 0) {
        if($data['name'] != $_SESSION['login_user']['name']) {
            $sql = "UPDATE user_register SET name=:name WHERE id=:id";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':name', $data['name']);
            $statement->bindParam(':id', $_SESSION['login_user']['id']);
            $statement->execute();
            $_SESSION['login_user']['name'] = $data['name'];
            $message = "Профиль успешно обновлен";
        }
        if ($data['email'] != $_SESSION['login_user']['email']) {
            if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM user_register WHERE email=:email";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':email', $data['email']);
                $statement->execute();
                if(!($statement->fetchAll())) {
                    $sql = "UPDATE user_register SET email=:email WHERE id=:id";
                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(':email', $data['email']);
                    $statement->bindParam(':id', $_SESSION['login_user']['id']);
                    $statement->execute();
                    $message = "Профиль успешно обновлен";
                    if(isset($_COOKIE['email'])) {
                        $_COOKIE['email'] = $data['email'];
                        $_SESSION['login_user']['email'] = $data['email'];
                    } else {
                        $_SESSION['login_user']['email'] = $data['email'];
                    }
                } else {
                    $errors[] = "Такой email уже зарегестрирован";
                }
            } else {
                $errors[] = "Введите email правильно";
            }
        }
    } else {
        $errors[] = "Поля имя и email обязательны для ввода";
    }
    if(empty($_FILES['image']['error'])) {
        $file = $_FILES['image'];
        $sql = "UPDATE `user_register` SET `img`=:img WHERE id=:id";
        $path = 'img/' . uniqid() . '.jpg';
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':img', $path);
        $statement->bindParam(':id', $_SESSION['login_user']['id']);
        $statement->execute();
        move_uploaded_file($file['tmp_name'], $path);
        $sql = "SELECT * FROM `user_register` WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $_SESSION['login_user']['id']);
        $statement->execute();
        $result = $statement->fetch();
        $_SESSION['login_user'] = $result;
    } elseif($_FILES['image']['error'] == 4) {

    } else {
        $errors[] = "Произошла ошибка при загрузке картинки";
    }
}

//    Пароль
if(isset($_POST['btn_submit'])) {
    $error_pass = [];
    $data = $_POST;
    $data['current'] = trim($data['current']);
    $data['password'] = trim($data['password']);
    $data['password_confirmation'] = trim($data['password_confirmation']);
    if(strlen($data['current']) > 5 && strlen($data['password']) > 5 && strlen($data['password_confirmation']) > 5) {
        if(password_verify($data['current'], $_SESSION['login_user']['password1'])) {
            if($data['password'] == $data['password_confirmation']) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $data['password_confirmation'] = password_hash($data['password_confirmation'], PASSWORD_DEFAULT);
                $sql = "UPDATE `user_register` SET password1=:pass1, password2=:pass2";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':pass1', $data['password']);
                $statement->bindParam(':pass2', $data['password_confirmation']);
                $statement->execute();
                $_SESSION['login_user']['password1'] = $data['password'];
                $_SESSION['login_user']['password2'] = $data['password_confirmation'];
                $message_pass = "Пароль успешно обновлен";
            } else {
                $error_pass[] = "Пароли различаются";
            }
        } else {
            $error_pass[] = "Неверный текущий пароль";
        }
    } else {
        $error_pass[] = "Все поля должны быть заполнены, минимальная длина пароля 6 символов";
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
                        <?php if($_SESSION['login_user']): ?>
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
            <?php if($_SESSION['login_user']): ?>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                            <?php if(isset($message)): ?>
                              <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                              </div>
                            <?php endif; ?>
                            <?php if(isset($errors) && !empty($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errors[0]; ?>
                                </div>
                            <?php endif; ?>

                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?php echo $_SESSION['login_user']['name'] ?>">
                                           
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $_SESSION['login_user']['email'] ?>">
<!--                                            <span class="text text-danger">-->
<!--                                                Ошибка валидации-->
<!--                                            </span>-->
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <img src="<?php echo (!empty($_SESSION['login_user']['img'])) ? $_SESSION['login_user']['img'] : 'img/no-user.jpg' ?>" alt="" class="img-fluid">
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" name="edit" class="btn btn-warning">Edit profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                            <?php if(isset($message_pass)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message_pass; ?>
                            </div>
                            <?php endif; ?>
                            <?php if(isset($error_pass) && !empty($error_pass)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_pass[0]; ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <button class="btn btn-success" type="submit" name="btn_submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
              <div class="row justify-content-center">
                  <div class="col-md-12">
                      <div class="card">
                          <div class="card-header"><h3>Профиль пользователя</h3></div>

                          <div class="card-body">
                              <p>У вас нет доступа к этой странице, пожалуйста авторизуйтесь, чтобы получить доступ!</p>
                              <a href="login.php">Авторизоваться</a>
                          </div>
                      </div>
                  </div>
              </div>
            <?php endif; ?>
        </div>
        </main>
    </div>
</body>
</html>

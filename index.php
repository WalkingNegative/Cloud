<?php
header("Content-Type: text/html; charset=utf-8");

session_start();

$referer = getenv("HTTP_REFERER");

if ($referer === 'users/registration.php') {
    unset($_SESSION["error"]);
}

if (!empty($_SESSION["id_user"])) {
    header('location: /files/myfiles.php');
}

if (!file_exists("disc")) {
    mkdir("disc");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Авторизация</title>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
        <script type="text/javascript" src="js/sha1.js"></script>
        <script type="text/javascript" src="js/handler.js"></script>
    </head>
    <body>
        <form action="actions/user/auth.php" method="post" enctype="multipart/form-data" name="login_form" id="form" role="form" style="width: 20%; margin: auto; margin-top: 15%;" onsubmit="onSubmit()">
            <div class="form-group">
                <label for="email">Эл. почта</label>
                <input type="text" name="email"  placeholder="Введите эл. почту" id="email" class="form-control" maxlength="30">
                <br>
                <label for="pas">Пароль</label>
                <input type="password"  name="password" placeholder="Введите пароль" class="form-control" maxlength="35">
                <hr>
                <div id="error">
                    <?php
                    if (!empty($_SESSION["error"])) {
                        echo "<div class=\"alert alert-danger\">" . $_SESSION["error"] . "</div>";
                    }
                    ?>
                </div>
                <input type="submit" value="Войти" class="btn btn-primary">
                <a href="users/registration.php" class="btn btn-success">Регистрация</a>
            </div>
        </form>
    </body>
</html>
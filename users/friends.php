<?php
require "../config.php.ini";
require "../classes/file.class.php";
require "../classes/user.class.php";

session_start();

if (empty($_SESSION["id_user"])) {
    header("location: " . PAGE_START);
    exit;
}

User::checkUsersOnline();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Подписки</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    </head>
    <body>
        <?php
        require DIR_RESOURSES . "navbar.php";
        require DIR_RESOURSES . "menu.php";
        ?>
        <div style="margin: auto; width:40%;">
            <!--<form method="get" accept-charset="utf-8">
                <input type="text" name="search" value="<= sset($_GET["search"]) ? $_GET["search"] : "" >" placeholder="Начните вводить любое имя" class="form-control">
            </form>
            <br>-->
            <?php
            $user = new User();
            $id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["id_user"];
            $subscribe = $user->getSubscribers($id);
            while ($row = $subscribe->fetch_array(MYSQLI_NUM)):
                ?>
                <div style="font-size: 14px; font-weight: 700;">
                    <img src="<?= isset($user->getInfo($row[0])[4]) ? $user->getInfo($row[0])[4] : '../disc/defaultImage.jpg' ?>" style="border-radius: 100%; box-shadow: 0 0 7px #666; width: 50px; height: 50px; margin: auto; margin-right: 10px">
                    <a href="<?= PAGE_PROFILE . $row[0] ?>">
                        <?= $user->getInfo($row[0])[1] . " " . $user->getInfo($row[0])[2]; ?>
                        <sup style="color: #ADADAD"><?= User::isOnline($row[0]) ? "online" : "" ?></sup>
                        <form action="subscription.php" method="post" accept-charset="utf-8"  style="position: relative; float: right; margin-left: 10px;">
                            <input type="hidden" name="id" value="<?= $row[0] ?>">
                            <input type="submit" class="btn btn-default" value="Отписаться">
                        </form>
                        <form action="mail.php" method="get" accept-charset="utf-8"  style="position: relative; float: right;">
                            <input type="hidden" name="id" value="<?= $row[0] ?>">
                            <button type="submit" class="btn btn-info" ><div class="glyphicon glyphicon-envelope"></div></button>
                        </form>
                </div>
                <hr>
            <?php endwhile; ?>
        </div>
    </body>
</html>
<?php
require "../config.php.ini";
require "../classes/file.class.php";
require "../classes/user.class.php";
header("Content-Type: text/html; charset=utf-8");
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
        <title>Профиль</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    </head>
    <body>
        <?php
        require DIR_RESOURSES . "navbar.php";
        require DIR_RESOURSES . "menu.php";
        ?>
        <div style="margin: auto; margin-top: 1%; width: 60%">
            <?php
            $name = $user->getInfo($_GET["id"])[1];
            $surname = $user->getInfo($_GET["id"])[2];
            $photo = $user->getInfo($_GET["id"])[4];
            ?>
            <div style="width: 225px; position: relative; float: left; margin-right: 10px;">
                <h3><?= $name . " " . $surname ?></h3>
                <img src="<?= isset($photo) ? $photo : '../disc/defaultImage.jpg' ?>" style="width: 225px; margin: auto;">
                <?php if ($_SESSION["id_user"] == $_GET["id"]) : ?>
                <form action="uploadphoto.php" method="post" enctype="multipart/form-data" id="upload" style="margin-top: 10px;">
                    <div style="font-size: 16px; text-align: center;">
                        <label for="uploadbtn">
                            Загрузить фото
                            <span class="glyphicon glyphicon-save"></span>
                        </label>
                    </div>
                    <input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="uploadphoto">
                    <form>
                        <?php endif; ?>
                        <?php if ($_SESSION["id_user"] != $_GET["id"]) : ?>
                        <form action="mail.php" method="get" accept-charset="utf-8" style="margin: 5px 0px 5px 0px">
                            <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
                            <button type="submit" class="btn btn-primary" style="width: 225px">Отправить сообщение</button>
                        </form>
                        <form action="subscription.php" method="post" accept-charset="utf-8" style="margin: 5px 0px 5px 0px">
                            <input type="hidden" name="id" value="<?= $_GET["id"] ?>">
                            <?php
                            $subscriptionExist = User::isSubscriptionExists($_SESSION["id_user"], $_GET["id"]);
                            ?>
                            <input type="submit" class="btn btn-<?= $subscriptionExist ? "default" : "primary" ?>" value="<?= $subscriptionExist ? "Отписаться" : "Подписаться" ?>" style="width: 225px;">
                        </form>
                        <?php endif; ?>
                        <?php if ($user->getCountSubscribers($_GET["id"]) > 0) : ?>
                        <hr>
                        <div class="panel panel-primary" style="margin-top: 20px;">
                            <div class="panel-heading">
                                <a href="<?= PAGE_FRIENDS."?id=".$_GET["id"] ?>" style="color: white;">
                                Подписки
                                <span style="position: relative; float: right;"><?= $user->getCountSubscribers($_GET["id"]) ?></span>
                                </a>
                            </div>
                            <div class="panel-body">
                                <?php $users = $user->getRandomSubscribers(isset($_GET["id"]) ? $_GET["id"] : $_SESSION["id_user"]);
                                while ($row = $users->fetch_array(MYSQLI_NUM)):
                                ?>
                                <div style="font-size: 14px; font-weight: 700; margin-bottom: 10px;">
                                    <a href="<?= PAGE_PROFILE . $row[0] ?>">
                                        <img src="<?= isset($user->getInfo($row[0])[4]) ? $user->getInfo($row[0])[4] : '../disc/defaultImage.jpg' ?>" style="border-radius: 100%; box-shadow: 0 0 7px #666; width: 50px; height: 50px; margin: auto; margin-right: 10px">
                                        <?= $user->getInfo($row[0])[1]; ?>
                                        <sup style="color: #ADADAD"><?= User::isOnline($row[0]) ? "online" : "" ?></sup>
                                    </a>
                                </div>
                                <?php endwhile ?>
                            </div>
                        </div>
                        <?php endif ?>
                    </div>
                    <div style="width: 60%; position: relative; float: left; margin-left: 10px; margin-top: 3%">
                        <?php
                        $file = new File();
                        if ($file->countFiles($_GET["id"], 'public') > 0) :
                        ?>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="alert alert-info">
                                    <th>Имя файла</th>
                                    <th>Размер</th>
                                    <th><span class="glyphicon glyphicon-download-alt"></span></th>
                                </tr>
                            </thead>
                            <?php
                            $files = $file->getFiles($_GET["id"], 'public');
                            while ($row = $files->fetch_array(MYSQLI_NUM)):
                            ?>
                            <tr>
                                <td><?= $row[1] ?></td>
                                <td><?= $row[2] ?></td>
                                <td><a href="<?= PAGE_DOWNLOAD . $row[0] ?>">Скачать </a></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <div class="alert alert-success">Нет файлов</div>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </body>
        </html>
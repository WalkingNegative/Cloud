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
            <div style="width: 30%; position: relative; float: left;">
                <h3><?= $name . " " . $surname ?></h3>
                <img src="<?= isset($photo) ? $photo : '../disc/defaultImage.jpg' ?>" >
                <?php if ($_SESSION["id_user"] == $_GET["id"]) : ?>
                    <form action="uploadphoto.php" method="post" enctype="multipart/form-data" id="upload" style="margin-top: 20px;">
                        <label for="uploadbtn" class="label label-primary" style="font-size: 16px;">
                            Загрузить фото
                            <span class="glyphicon glyphicon-save"></span>
                        </label>
                        <input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="uploadphoto">
                        <form>
                        <?php endif; ?>
                        <?php $CountSubscribers = $user->getCountSubscribers($_GET["id"]);
                        if ($CountSubscribers > 0) :
                            ?>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        Подписки
                                        <span style="position: relative; float: right;"><?= $CountSubscribers ?></span>   
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $subscribe = $user->getRandomSubscribers($_GET["id"]);
                                    while ($row = $subscribe->fetch_array(MYSQLI_NUM)) :
                                        ?>
                                        <div style="margin: 15px 15px 15px 10px; position: relative; float: left;">
                                            <a href="<?= PAGE_PROFILE . $row[0] ?>" title="<?= $user->getInfo($row[0])[1] . " " . $user->getInfo($row[0])[2] ?>" class="navbar-brand" style="padding: 0">
                                                <img src="<?= isset($user->getInfo($row[0])[4]) ? $user->getInfo($row[0])[4] : "../disc/defaultImage.jpg" ?>" style="border-radius: 150%; width: 50px; height: 50px; margin: auto;">
                                                <span style="font-size: 12px; font-weight: bold; margin: auto;">
        <?= $user->getInfo($row[0])[1] ?>
                                                </span>
                                            </a>
                                        </div>
                            <?php endwhile; ?>
                                </div>
                            </div>
                            <?php endif; ?>    
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
<?php $user = new User() ?>
<nav class="navbar navbar-light" style="background-color: #e3f2fd;" style="padding-left: 3">
    <a href="<?= PAGE_MYFILES ?>" title="Мои файлы" class="navbar-brand" style="padding: 0">
        <img src="<?= isset($user->getInfo($_SESSION["id_user"])[4]) ? $user->getInfo($_SESSION["id_user"])[4] : "../disc/defaultImage.jpg" ?>" style="border-radius: 150%; width: 50px; height: 50px; margin: auto;">
    </a>
    <a href="<?= PAGE_MYFILES ?>" title="Мои файлы" class="navbar-brand">
        <?= $user->getInfo($_SESSION["id_user"])[1] . " " . $user->getInfo($_SESSION["id_user"])[2] ?>
    </a>
    <a href="<?= PAGE_LOGOUT ?>" class="navbar-brand" style="position: relative; float: right;">Выйти</a>
</nav>
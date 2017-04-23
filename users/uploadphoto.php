<?php

require "../config.php.ini";
require "../classes/file.class.php";
require "../classes/user.class.php";
require "../classes/AcImage.php";

session_start();

$user = new User();
$file = new File();

$referer = getenv("HTTP_REFERER");

$uploaddir = "../disc/" . $user->getInfo($_SESSION["id_user"])[3] . "/";

$user->deletePhoto($_SESSION["id_user"]);
$user->addPhoto($uploaddir, $_SESSION["id_user"]);

$path = $uploaddir . $_FILES["filename"]["name"];
$img = AcImage::createImage($path);
$img->resizeByHeight(350);
$img->cropCenter(225, 350);

unlink($path);
$img->save($path);

header("location: " . $referer);
?>
<?php

require "../config.php.ini";
require "../classes/file.class.php";

header("Content-Type: text/html; charset=utf-8");

$file = new File();
$referer = getenv("HTTP_REFERER");

session_start();

$path = $file->getPath($_GET["id_file"]);
if ($file->isOwner($_SESSION["id_user"], $_GET["id_file"])) {
    $file->deleteFile($path);
}
header("location: " . $referer);

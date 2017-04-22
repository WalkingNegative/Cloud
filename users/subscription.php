<?php

require "../config.php.ini";
require "../classes/file.class.php";
require "../classes/user.class.php";

session_start();

$refer = $_SERVER['HTTP_REFERER'];

$user = new User();
$file = new File();

$user->subscribe($_SESSION["id_user"], $_POST["id"]);

header("location: " . $refer);

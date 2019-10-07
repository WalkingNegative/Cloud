<?php

require '../../classes/file.class.php';
require '../../classes/user.class.php';

File::checkNavigation('index.php');

session_start();

$user = new User();
$file = new File();

$email = $_POST['email'];
$password = $_POST['password'];

if ($user->authorization($email, $password)) {
    $_SESSION['id_user'] = $user->getId($email);
    unset($_SESSION['error']);
    header('location: /pages/file/myfiles.php');
} else {
    $_SESSION['error'] = 'Неверный логин или пароль';
    header('location: /index.php');
}

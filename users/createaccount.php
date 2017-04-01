<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	File::checkNavigation(PAGE_REGISTRATION);

	session_start();
	$user = new User();
	$file = new File();
	$name = $_POST["name"];
	$surname = $_POST["surname"];
	$email = $_POST["email"];
	$password = $_POST["password"];
			
	if (!$user->newUser($email, $password, $name, $surname))
	{
		$_SESSION["error"] = "Неверные данные";
		header("location: ".PAGE_REGISTRATION);
		exit;
	}

	$_SESSION["id_user"] = $user->getId($email);
	header("location: ".PAGE_MYFILES);

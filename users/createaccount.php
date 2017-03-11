<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	session_start();

	$referer = getenv("HTTP_REFERER");
	if ($referer != PAGE_REGISTRATION) {
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ".PAGE_START);
		exit;
	}
	
	$user = new User();
	$file = new File();
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	if ($user->isExistEmail($email)) {
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location: registration.php");
		exit;
	}
			
	if ($user->newUser($email, $password)) {
		$_SESSION["error"] = "Ошибка!";
		exit;
	}

	if (!file_exists(DIR_DISC.$email)) {
		$file->deleteAllFiles($user->getId($email));
		mkdir(DIR_DISC.$email);
	}

	$_SESSION["id_user"] = $user->getId($email);
	unset($_SESSION["error"]);
	header("location: ".PAGE_FILES);

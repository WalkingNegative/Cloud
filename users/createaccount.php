<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	File::checkNavigation(PAGE_REGISTRATION	);

	session_start();
	$user = new User();
	$file = new File();
	$email = $user->clearText($_POST["email"]);
	$password = $user->clearText($_POST["password"]);
	
	if ($user->isExistEmail($email)) {
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location:".PAGE_REGISTRATION);
		exit;
	}
			
	$user->newUser($email, $password);

	$_SESSION["id_user"] = $user->getId($email);
	header("location: ".PAGE_FILES);

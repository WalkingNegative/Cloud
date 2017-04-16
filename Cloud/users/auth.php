<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	File::checkNavigation(PAGE_START);

	session_start();

	$user = new User();
	$file = new File();

	$email = $_POST["email"];
	$password = $_POST["password"];

	if ($user->authorization($email, $password)) {
		$_SESSION["id_user"] = $user->getId($email);
		unset($_SESSION["error"]);
		header("location: ".PAGE_MYFILES);
	} else {
		$_SESSION["error"] = "Неверный логин или пароль";
		header("location: ".PAGE_START);
	}

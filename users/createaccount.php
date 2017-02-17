<?php
	include_once("user.class.php");

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/users/registration.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}
	
	$user = new User();

	$email = $user->clear_text($_POST["email"]);
	$password = $user->clear_text($_POST["password"]);

	if (!$user->check_email($email))
	{
		$_SESSION["error"] = "Неверный формат электронной почты!";
		header("location: registration.php");
		exit;
	}
	if ($user->is_exist_email($email))
	{
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location: registration.php");
		exit;
	}
	if (strlen($password) < 8)
	{
		$_SESSION["error"] = "Пароль должен содержать минимум 8 символов!";
		header("location: registration.php");
		exit;
	}
				
	$user->new_user($email, $password);
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	unset($_SESSION["error"]);
	header("location: ../files/files.php");
?>
<?php
	include_once("user.class.php");

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}

	$user = new User();
	$email = $user->clear_text($_POST["email"]);
	$password = $user->clear_text($_POST["password"]);

	if ((!$user->check_email($email)) || (strlen($password) < 8))
 	{
 		$_SESSION["error"] = "Неверный формат ввода";
 		header("location: ../index.php");
 		exit;
 	}

	if (!$user->authorization($email, $password))
	{
		$_SESSION["error"] = "Неверный логин или пароль";
		header("location: ../index.php");
	}
	else
	{
		$_SESSION["email"] = $email;
		$_SESSION["password"] = $password;
		header("location: ../files/files.php");	
	}
?>
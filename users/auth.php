<?php
	include_once("user.class.php");
	include_once("../files/file.class.php");

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}

	$user = new User();
	$file = new File();
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
		if (!file_exists("../disc/".$email))
		{
			$file->delete_all_files($user->get_id($email));
			mkdir("../disc/".$email);
		}
		header("location: ../files/files.php");	
	}
?>
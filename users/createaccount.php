<?php
	header("Content-Type: text/html; charset=utf-8");

	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer != PAGE_REGISTRATION)
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ".PAGE_START);
		exit;
	}
	
	$user = new User();
	$file = new File();
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	if ($user->is_exist_email($email))
	{
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location: registration.php");
		exit;
	}
			
	if ($user->new_user($email, $password))
	{
		$_SESSION["error"] = "Ошибка!";
		exit;
	}

	if (!file_exists(DIR_DISC.$email))
	{
		$file->delete_all_files($user->get_id($email));
		mkdir(DIR_DISC.$email);
	}
	$_SESSION["id_user"] = $user->get_id($email);
	unset($_SESSION["error"]);
	header("location: ".PAGE_FILES);
?>
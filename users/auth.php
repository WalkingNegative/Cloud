<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	session_start();

	/*$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}*/

	$user = new User();
	$file = new File();

	$email = $_POST["email"];
	$password = $_POST["password"];

	if ($user->authorization($email, $password))
		{
			$_SESSION["id_user"] = $user->get_id($email);
			unset($_SESSION["error"]);
			if (!file_exists(DIR_DISC.$email))
			{
				$file->delete_all_files($user->get_id($email));
				mkdir(DIR_DISC.$email);
			}
			header("location: ".PAGE_FILES);
		}
		else
		{
			$_SESSION["error"] = "Неверный логин или пароль";
			header("location: ".PAGE_START);
		}
?>
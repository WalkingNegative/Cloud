<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

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
	$email = $_POST["email"];
	$password = $_POST["password"];
	$hash = $_POST["SecurityHash"];
	$ip = $_SERVER['REMOTE_ADDR'];

	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$db->query('delete from `loginhash` where `Deadline` < NOW()');

	$result = $db->query("Select * from `Loginhash` Where ip = '".$ip."' and  hash = '".$hash."';");	
	if($result->num_rows != 1)
	{
		sleep(5);
		header("location:../index.php");
		exit;
	}

	if ($user->authorization($email, $password))
		{
			$_SESSION["email"] = $email;
			$_SESSION["password"] = $password;
			unset($_SESSION["error"]);
			if (!file_exists("../disc/".$email))
			{
				$file->delete_all_files($user->get_id($email));
				mkdir("../disc/".$email);
			}
			header("location: ../files/files.php");
		}
		else
		{
			$_SESSION["error"] = "Неверный логин или пароль";
			header("location: ../index.php");
		}
		
	$db->query("delete from `loginhash` where Hash='".$hash."'");
	$db->close();
	
?>
<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/users/registration.php")
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

	if ($user->is_exist_email($email))
	{
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location: registration.php");
		exit;
	}

	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$db->query('delete from `loginhash` where `Deadline` < NOW()');

	$result = $db->query("Select * from `Loginhash` Where ip = '".$ip."' and  hash = '".$hash."';");	
	if($result->num_rows != 1)
	{
		sleep(5);
		header("location: registration.php");
	}
				
	$user->new_user($email, $password);
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	unset($_SESSION["error"]);
	$db->query("delete from `loginhash` where Hash='".$hash."'");
	$db->close();
	header("location: ../files/files.php");
?>
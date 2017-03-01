<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";
	$user  = new User();
	$file = new File();

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/files/files.php")
	{
		header("location: ../files.php");
		exit;
	}

	session_start();
	$email = $user->clear_text($_SESSION["email"]);
	$path = $user->clear_text($_GET["path"]);
	if ($file->is_owner($path, $user->get_id($email)))
	{
		$file->file_download($path);
		header("location: ../files.php");
	}
	else
	{
		header("location: ../files.php");
	}
?>
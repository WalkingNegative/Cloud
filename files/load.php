<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	$user  = new User();
	$file = new File();

	session_start();
	$uploaddir = "../disc/".$_SESSION["email"]."/";
	if($_FILES["filename"]["size"] > 209715200)
	{
		echo ("Размер файла превышает 200 мегабайт");
		exit;
	}

	if (!$file->check_type($_FILES["filename"]["name"]))
		$file->add_file($uploaddir,$user->get_id($_SESSION["email"]));
	else
		$_SESSION["error"] = "Неподдерживаемый формат!"; 
	header("location: files.php");
?>
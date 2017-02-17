<?php
	header("Content-Type: text/html; charset=utf-8");

	include_once("../users/user.class.php");
	include_once("file.class.php");

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
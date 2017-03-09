<?php
	header("Content-Type: text/html; charset=utf-8");

	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	$user  = new User();
	$file = new File();

	session_start();
	$uploaddir = DIR_DISC.$user->get_email($_SESSION["id_user"])."/";
	if($_FILES["filename"]["size"] > 85899345920)
	{
		$_SESSION["error"] = ("Размер файла превышает 10 ГБ");
		exit;
	}

	if (!$file->check_type($_FILES["filename"]["name"]))
		$file->add_file($uploaddir, $_SESSION["id_user"]);
	else
		$_SESSION["error"] = "Неподдерживаемый формат!"; 
	header("location: ".PAGE_FILES);
?>
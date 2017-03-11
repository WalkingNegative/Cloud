<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	session_start();

	$user  = new User();
	$file = new File();

	$uploaddir = DIR_DISC.$user->getEmail($_SESSION["id_user"])."/";
	if($_FILES["filename"]["size"] > 85899345920) {
		$_SESSION["error"] = ("Размер файла превышает 10 ГБ");
		exit;
	}

	if (!$file->checkType($_FILES["filename"]["name"])) {
		$file->addFile($uploaddir, $_SESSION["id_user"]);
	} else {
		$_SESSION["error"] = "Неподдерживаемый формат!"; 
	}
	header("location: ".PAGE_FILES);

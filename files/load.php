<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	session_start();

	$user  = new User();
	$file = new File();

	$referer = getenv("HTTP_REFERER");

	$uploaddir = DIR_DISC.$user->getInfo($_SESSION["id_user"])[2]."/";
	if($_FILES["filename"]["size"] > 85899345920) {
		$_SESSION["error"] = ("Размер файла превышает 10 ГБ");
		exit;
	}

	if (!$file->checkType($_FILES["filename"]["name"])) {
		$file->addFile($uploaddir, $_SESSION["id_user"]);
	} 
	
	header("location: ".$referer);

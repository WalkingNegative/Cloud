<?php
	require "../config.php.ini";
	require "../classes/file.class.php";

	header("Content-Type: text/html; charset=utf-8");

	$file = new File();
	session_start();
	$referer = getenv("HTTP_REFERER");
	if ($referer != PAGE_FILES) {
		header("location: ".PAGE_FILES);
		exit;
	}
	
	$path = $file->getPath($_GET["id_file"]);

	if ($file->isOwner($_GET["id_file"], $_SESSION["id_user"])) {
		$file->fileDownload($path);
	}
	
	header("location: ".PAGE_FILES);

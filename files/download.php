<?php
	require "../config.php.ini";
	require "../classes/file.class.php";

	header("Content-Type: text/html; charset=utf-8");

	File::checkNavigation(PAGE_FILES);

	$file = new File();
	session_start();
	
	$path = $file->getPath($_GET["id_file"]);
	if ($file->isOwner($_SESSION["id_user"], $_GET["id_file"])) {
		$file->fileDownload($path);
	}
	
	header("location: ".PAGE_FILES);

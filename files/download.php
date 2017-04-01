<?php
	require "../config.php.ini";
	require "../classes/file.class.php";

	header("Content-Type: text/html; charset=utf-8");

	$file = new File();
	$referer = getenv("HTTP_REFERER");
	$path = $file->getPath($_GET["id_file"]);

	session_start();
	
	if ($referer == PAGE_MYFILES) {
		if ($file->isOwner($_SESSION["id_user"], $_GET["id_file"])) {
			$file->fileDownload($path);
		}
	} elseif ($referer == PAGE_ALLFILES) {
		$file->fileDownload($path);
	}
	header("location: ".PAGE_MYFILES);

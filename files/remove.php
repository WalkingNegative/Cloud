<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";

	$file = new File();

	session_start();

	$path = $file->getPath($_GET["id_file"]);
		
	if ($file->isOwner($_GET["id_file"], $_SESSION["id_user"]))
		$file->deleteFile($path);
	header("location: ".PAGE_FILES);
?>
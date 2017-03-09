<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";

	$file = new File();

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != PAGE_FILES)
	{
		header("location: ".PAGE_FILES);
		exit;
	}

	$path = $file->get_path($_GET["id_file"]);

	if ($file->is_owner($_GET["id_file"], $_SESSION["id_user"]))
		$file->file_download($path);
	
	header("location: ".PAGE_FILES);
?>
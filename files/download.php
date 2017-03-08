<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	$user  = new User();
	$file = new File();

	session_start();
	
	$referer=getenv("HTTP_REFERER");
	if ($referer != PAGE_FILES)
	{
		header("location: ".PAGE_FILES);
		exit;
	}

	$path = $file->get_path($_GET["id_file"]);
	echo $path;

	if ($file->is_owner($path, $_SESSION["id_user"]))
		$file->file_download($path);
	
	header("location: ".PAGE_FILES);
?>
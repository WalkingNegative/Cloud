<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	$user  = new User();
	$file = new File();

	session_start();

	$path = $file->get_path($_GET["id_file"]);
		
	if ($file->is_owner($path, $_SESSION["id_user"]))
		$file->delete_file($path);
	header("location: ".PAGE_FILES);
?>
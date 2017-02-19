<?php
	header("Content-Type: text/html; charset=utf-8");
	include_once("../users/user.class.php");
	include_once("file.class.php");
	$user  = new User();
	$file = new File();
	session_start();
	$email = $user->clear_text($_SESSION["email"]);
	$path = $user->clear_text($_GET["path"]);	
	if ($file->is_owner($path, $user->get_id($email)))
	{
		$file->delete_file($path);
		header("location: ../files.php");
	}
	else
	{
		header("location: ../files.php");
	}
?>
<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	session_start();

	$user  = new User();
	$file = new File();

	$referer = getenv("HTTP_REFERER");

	$uploaddir = "../disc/".$user->getInfo($_SESSION["id_user"])[3]."/";

	$user->deletePhoto($_SESSION["id_user"]);
	$user->addPhoto($uploaddir, $_SESSION["id_user"]);
	
	header("location: ".$referer);
?>
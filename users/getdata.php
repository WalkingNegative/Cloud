<?php
	include_once("../GetDetails.php");
	session_start();
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}

	$email = htmlentities($_POST["email"], ENT_QUOTES, "UTF-8");
	$password = htmlentities($_POST["password"], ENT_QUOTES, "UTF-8");

	if ((CheckEmail($email) == true) && (strlen($password) > 7))
	{
 		$_SESSION["email"] = $email;
 		$_SESSION["password"] = $password;
 		unset($_SESSION["error"]);
 		header("location: auth.php");
 	}
 	else 
 	{
 		$_SESSION["error"] = "Неверный формат ввода";
 		header("location: ../index.php");
 	}
?>
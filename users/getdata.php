<?php
	session_start();
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}
	
	function CheckEmail($email){
		$regex = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
		preg_match($regex, $email, $result);
		if (empty($result) == false)
			return true;
		else
			return false;
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
 		session_start();
 		$_SESSION["error"] = "Неверный формат ввода";
 		header("location: ../index.php");
 	}
?>
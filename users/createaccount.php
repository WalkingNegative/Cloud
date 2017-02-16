<?php
	include_once("../GetDetails.php");

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/users/registration.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}

	function IsExistEmail($email)
	{
		$db = new mysqli("localhost", "root", "20021", "Cloud");
		$query = $db->query("Select * From Users;");
		while($users = $query->fetch_assoc())
			{
				if (($users['email'] == $email))
					{
						return true;
					};
			}
		$db->close();
		return false;
	}

	function NewUser($email, $password)
	{
		$db = new mysqli("localhost", "root", "20021", "Cloud");
		$db->query("Insert into Users (email, pas) values ('".$email."', '".$password."');");
		$db->close();
		mkdir("../disc/".$_POST["email"]);
	}
			
	$email = htmlentities($_POST["email"], ENT_QUOTES, "UTF-8");
	$password = htmlentities($_POST["password"], ENT_QUOTES, "UTF-8");

	if (!CheckEmail($email))
	{
		$_SESSION["error"] = "Неверный формат электронной почты!";
		header("location: registration.php");
		exit;
	}
	if (IsExistEmail($_POST["email"]))
	{
		$_SESSION["error"] = "Такой пользователь уже существует!";
		header("location: registration.php");
		exit;
	}
	if (strlen($_POST["password"]) < 8)
	{
		$_SESSION["error"] = "Пароль должен содержать минимум 8 символов!";
		header("location: registration.php");
		exit;
	}
				
	NewUser($email, $password);
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	unset($_SESSION["error"]);
	header("location: ../files/files.php");
?>
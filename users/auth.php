<?php
	session_start();
	$referer=getenv("HTTP_REFERER");
	if ($referer != "http://localhost/cloud/index.php")
	{
		$_SESSION["error"] = "Неверный формат ввода";
		header("location: ../index.php");
		exit;
	}
	$db = new mysqli("localhost", "root", "20021", "Cloud");
	$query = $db->query("Select * From Users;");
	while($users = $query->fetch_assoc())
	{
		if (($users["email"] == $_SESSION["email"]) && ($users["pas"] == $_SESSION["password"]))
	 	{
	 		header("location: ../files/files.php");
	 		unset($_SESSION["error"]);
	 		$db->close();
	 		exit;
	 	};
	}
	$db->close();
	unset($_SESSION["email"]);
	unset($_SESSION["password"]);
	$_SESSION["error"] = "Неверный логин или пароль";
	header("location: ../index.php");
?>
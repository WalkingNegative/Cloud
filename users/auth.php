<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Auth</title>
	<link rel="stylesheet" href="st.css">
</head>
<body>
	<?php 
		session_start();
		$db = @mysql_connect("localhost", "root", "20021");
		if (!($db))
			echo "Не удалось подключиться!";
		mysql_select_db("Cloud", $db);
		$query = mysql_query("Select * From Users;");
		while($users = mysql_fetch_array($query))
  		{
   			 if (($users["email"] == $_SESSION["email"]) && ($users["pas"] == $_SESSION["password"]))
   			 	{
   			 		header("location: ../files/files.php");
   			 		mysql_close($db);
   			 		exit;
   			 	};
  		}
		mysql_close($db);
		unset($_SESSION["email"]);
		unset($_SESSION["password"]);
		$_SESSION["error"] = "Неверный логин или пароль";
		header("location: ../index.php");
	?>
</body>
</html>
<?php
	header("Content-Type: text/html; charset=utf-8");

	require "/config.php.ini";

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer == "http://localhost/cloud/users/registration.php")
	{
		unset($_SESSION["error"]);
		exit;
	}

	if (!empty($_SESSION["email"]))
		header("location: files/files.php");

	if (!file_exists("disc"))
	{
		mkdir("disc");
	}

	$ip = $_SERVER['REMOTE_ADDR'];

	$SecurityHash = sha1(time() . rand() . $ip);

	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$db->query('delete from `loginhash` where `deadline` < now()');

	sleep(1);

	$db->query("insert into `loginhash` (`ip`,`hash`,`deadline`) VALUES('$ip', '$SecurityHash', ADDTIME(NOW(), '0:10:0'))");
	$db->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Авторизация</title>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="sha1.js"></script>
	</head>
	<body> 
		<form action="users/auth.php"  method="post" enctype="multipart/form-data" name="login_form" role="form" style="width: 20%; margin: auto; margin-top: 15%;" onsubmit="Submit()"> 
			<div class="form-group">
				<label for="email">Эл. почта</label>
				<input type="text" name="email" value="" placeholder="Введите эл. почту" class="form-control" maxlength=30>
				<br>
				<label for="password">Пароль</label>
				<input type="password"  id="pas" value="" placeholder="Введите пароль" class="form-control" maxlength=35>
				<input type="hidden" name="password" id="password" value="">
				<div class="error">
			</div>
				<hr>
				<?php 
					if (!empty($_SESSION["error"]))
					{
						echo "<div class=\"alert alert-danger\">".$_SESSION["error"]."</div>";
					}
					echo "<input type=\"hidden\" name=\"SecurityHash\" value=\"". $SecurityHash ."\">"
				?>
				<input type="submit" name="" value="Войти" class="btn btn-primary">
				<a href="users/registration.php" title=""><input type="button" name="" value="Регистрация" class="btn btn-success"></a>
			</div>
		</form>
	</body>
</html>
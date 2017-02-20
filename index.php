<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start();
	if (!empty($_SESSION["email"]))
		header("location: files/files.php");

	if (!file_exists("disc"))
	{
		mkdir("disc");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Авторизация</title>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
	</head>
	<body>
		<form action="users/auth.php" method="post" accept-charset="utf-8" role="form" style="width: 20%; margin: auto; margin-top: 15%;"> 
			<div class="form-group">
				<label for="email">Эл. почта</label>
				<input type="text" name="email" value="" placeholder="Введите эл. почту" class="form-control" maxlength=35>
				<br>
				<label for="password">Пароль</label>
				<input type="password" name="password" value="" placeholder="Введите пароль" class="form-control" maxlength=35>
				<div class="error">
			</div>
				<hr>
				<?php 
					if (!empty($_SESSION["error"]))
					{
						echo "<div class=\"alert alert-danger\">".$_SESSION["error"]."</div>";
					}
				?>
				<input type="submit" name="" value="Войти" class="btn btn-primary">
				<a href="users/registration.php" title=""><input type="button" name="" value="Регистрация" class="btn btn-success"></a>
			</div>
		</form>
	</body>
</html>
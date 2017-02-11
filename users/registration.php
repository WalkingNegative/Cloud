<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Регистрация</title>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<form action="createaccount.php" method="post" accept-charset="utf-8" role="form" style="width: 20%; margin: auto; margin-top: 15%;">
			<div class="form-group">
				<label for="email">Эл. почта</label>
				<input type="text" name="email" value="" placeholder="Введите эл. почту" class="form-control" maxlength=35>
				<br>
				<label for="password">Пароль</label>
				<input type="password" name="password" value="" placeholder="Введите пароль" class="form-control" maxlength=35>
			</div>
			<?php
				session_start();
				if (!empty($_SESSION["error"]))
				{
					echo "<div class=\"alert alert-danger\">".$_SESSION["error"]."</div>";
				}
			?>
			<input type="submit" name="" value="Регистрация" class="btn btn-primary" style="position: relative; float: left;">
			<h1><a href="../index.php" title="Авторизация" class="navbar-brand glyphicon glyphicon-log-out" style="position: relative; float: right; margin-top: -5%;"></a></h1>
		</form>
	</body>
</html>
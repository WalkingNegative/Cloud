<?php
	require "../config.php.ini";

	header("Content-Type: text/html; charset=utf-8");

	session_start();

	$referer = getenv("HTTP_REFERER");
	if ($referer == PAGE_START) {
		unset($_SESSION["error"]);
	}

	if (!empty($_SESSION["id_user"])) {
		header("location: ".PAGE_MYFILES);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Регистрация</title>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
		<script type="text/javascript" src="../js/sha1.js"></script>
		<script type="text/javascript" src="../js/handler.js"></script>
	</head>
	<body>
		<form action="createaccount.php" method="post" accept-charset="utf-8" id="form" role="form" style="width: 20%; margin: auto; margin-top: 10%;">
			<div class="form-group">
				<label for="name">Имя</label>
				<input type="text" name="name" placeholder="Введите имя" class="form-control" maxlength="30">
				<br>
				<label for="surname">Фамилия</label>
				<input type="text"  name="surname" placeholder="Введите фамилию" class="form-control" maxlength="35">
				<br>
				<label for="email">Эл. почта</label>
				<input type="text" name="email" id="email" placeholder="Введите эл. почту" class="form-control" maxlength="30">
				<br>
				<label for="password">Пароль</label>
				<input type="password"  name="password" placeholder="Введите пароль" class="form-control" maxlength="35">
				<div class="error">
			</div>
			<hr>
			<div id="error">
				<?php
					if (!empty($_SESSION["error"])) {
						echo "<div class=\"alert alert-danger\">".$_SESSION["error"]."</div>";
					}
				?>
			</div>
			<input type="submit" value="Регистрация" class="btn btn-primary" style="position: relative; float: left;">
			<h1>
				<a href="../index.php" title="Авторизация" class="navbar-brand glyphicon glyphicon-log-out" style="position: relative; float: right; margin-top: -5%;"></a>
			</h1>
		</form>
	</body>
</html>

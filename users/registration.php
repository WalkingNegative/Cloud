<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer == PAGE_START)
		unset($_SESSION["error"]);

	if (!empty($_SESSION["id_user"]))
		header("location: ".PAGE_FILES);

	if (!empty($_SESSION["email"]))
		header("location: ".PAGE_FILES);
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
		<form action="createaccount.php" method="post" accept-charset="utf-8" id="form" role="form" style="width: 20%; margin: auto; margin-top: 15%;" onsubmit="onSubmit()">
			<div class="form-group">
				<label for="email">Эл. почта</label>
				<input type="text" name="email" id="email" value="" placeholder="Введите эл. почту" class="form-control" maxlength="30">
				<br>
				<label for="password">Пароль</label>
				<input type="password"  id="pas" value="" placeholder="Введите пароль" class="form-control" maxlength="35">
				<input type="hidden" name="password" id="password" value="">
				<div class="error">
			</div>
			<hr>
			<div id="error">
				<?php
					if (!empty($_SESSION["error"]))
					{
						echo "<div class=\"alert alert-danger\">".$_SESSION["error"]."</div>";
					}
				?>
			</div>
			<input type="submit" name="" value="Регистрация" class="btn btn-primary" style="position: relative; float: left;">
			<h1>
				<a href="../index.php" title="Авторизация" class="navbar-brand glyphicon glyphicon-log-out" style="position: relative; float: right; margin-top: -5%;"></a>
			</h1>
		</form>
	</body>
</html>

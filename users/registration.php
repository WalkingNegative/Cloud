<?php
	header("Content-Type: text/html; charset=utf-8");

	require "../config.php.ini";

	session_start();

	$referer=getenv("HTTP_REFERER");
	if ($referer == "http://localhost/cloud/index.php")
		unset($_SESSION["error"]);

	if (!empty($_SESSION["email"]))
		header("location: files/files.php");

	$ip = $_SERVER['REMOTE_ADDR'];

	$hash = sha1(time() . rand() . $ip);

	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$db->query('delete from `loginhash` where `deadline` < now()');

	sleep(1);

	$db->query("insert into `loginhash` (`ip`,`hash`,`deadline`) VALUES('$ip', '$hash', ADDTIME(NOW(), '0:10:0'))");
	$db->close();
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
				<input type="hidden" name="hash" value="<?php echo $hash; ?>">;
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

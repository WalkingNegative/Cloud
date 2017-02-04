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
				<input type="text" name="email" value="" placeholder="Введите эл. почту" class="form-control">
				<br>
				<label for="password">Пароль</label>
				<input type="password" name="password" value="" placeholder="Введите пароль" class="form-control">
			</div>
			<input type="submit" name="" value="Регистрация" class="btn btn-primary">
		</body>
	</html>
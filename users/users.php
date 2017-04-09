<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	session_start();

	if (empty($_SESSION["id_user"])) {
			header("location: ".PAGE_START);
			exit;
	}

	User::checkUsersOnline();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Пользователи</title>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
</head>
<body>
	<?php 
		require DIR_RESOURSES."navbar.php";
		require DIR_RESOURSES."menu.php";
	?>
	<div style="margin: auto; width:40%;">
		<form action="" method="get" accept-charset="utf-8">
			<input type="text" name="search" placeholder="Начните вводить любое имя" class="form-control">
		</form>
		<br>
		<?php
			$user = new User();
			$users = $user->getAllUsers();
			while ($row = $users->fetch_array(MYSQLI_NUM)): ?>
				<div style="font-size: 14px; font-weight: 700;">
				<img src="<?= isset($user->getInfo($row[0])[4]) ? $user->getInfo($row[0])[4] : STANDART_PHOTO ?>" style="border-radius: 100%; box-shadow: 0 0 7px #666; width: 50px; height: 50px; margin: auto; margin-right: 10px">
					<a href="<?= PAGE_PROFILE.$user->getInfo($row[0])[0] ?>"><?= $user->getInfo($row[0])[1]. " ".$user->getInfo($row[0])[2]; ?></a>
				</div>
				<hr>
		<?php endwhile; ?>
	</div>
</body>
</html>
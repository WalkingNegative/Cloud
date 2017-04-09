<?php
	require "../config.php.ini";
	require "../classes/file.class.php";
	require "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

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
	<title>Профиль</title>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
</head>
<body>
	<?php 
		require DIR_RESOURSES."navbar.php";
		require DIR_RESOURSES."menu.php";
	?>
	<div style="margin: auto; margin-top: 1%; width: 40%">
	<?php
		$name = $user->getInfo($_GET["id"])[1];
		$surname = $user->getInfo($_GET["id"])[2];
		$photo = $user->getInfo($_GET["id"])[4];

	?>
	<h1><?= $name." ".$surname ?></h1>
	<img src="<?= isset($photo) ? $photo : STANDART_PHOTO ?>">
	<?php if ($_SESSION["id_user"] == $_GET["id"]) : ?>
		<form action="uploadphoto.php" method="post" enctype="multipart/form-data" id="upload" style="margin-top: 20px;">
			<label for="uploadbtn" class="label label-primary" style="font-size: 16px;">
				Загрузить фото
				<span class="glyphicon glyphicon-save"></span>
			</label>
				<input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="uploadphoto">
		<form>
	<?php endif; ?>
	</div>
</body>
</html>
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
	<div style="margin: auto; margin-top: 15%; width: 506px; height: 230px;">
		<a href="<?= PAGE_MYFILES ?>" title="Назад">
			<img src="http://static.wixstatic.com/media/081748_97b54296416545adb790a93b648d3ccc~mv2.png_srz_506_230_85_22_0.50_1.20_0.00_png_srz">
		</a>
	</div>
</body>
</html>
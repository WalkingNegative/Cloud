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
		<title>Файлы</title>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	</head>
	<body>
		<nav class="navbar navbar-light" style="background-color: #e3f2fd;">
			<div class="navbar-brand">
				Вы вошли, как
				<?php
					$user  = new User();
					echo $user->getEmail($_SESSION["id_user"]);
				?>
			</div>
			<a href="../users/logout.php" title="Выйти" class="navbar-brand glyphicon glyphicon-log-out"></a>
		</nav>
		<?php
			$file = new File();
			if ($file->countFiles($_SESSION["id_user"]) > 0): ?>
				<table class="table table-hover table-bordered" style="width: 40%; margin: auto;">
					<thead>
						<tr class="alert alert-info">
							<th>Имя файла</th>
							<th>Размер</th>
							<th><span class="glyphicon glyphicon-download-alt"></span></th>
							<th><span class="glyphicon glyphicon-trash"></span></th>
						</tr>
					</thead>
				<?php
					$stmt = $file->getFiles($_SESSION["id_user"]);
					while ($row = $stmt->fetch_array(MYSQLI_NUM)): ?>
						<tr>
						<td><?= $row[1] ?></td>
						<td><?= $row[2] ?></td>
						<td><a href="<?=PAGE_DOWNLOAD.$row[0]?>">Скачать </a></td>
						<td><a href="<?=PAGE_REMOVE.$row[0]?>"> Удалить </a></td>
						</tr>
					<?php endwhile; ?>
			<?php else: ?>
				<div class="alert alert-success">У вас ещё нет файлов</div>
			<?php endif; ?>
		</table>
		<form action="load.php" method="post" enctype="multipart/form-data" id="upload" style="position: relative; float: top; float: left; margin: auto; width: 20%">
			<h3><span class="label label-primary"><label for="uploadbtn">Загрузить файл</label></span></h3>
			<input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="load">
		</form>
		<div class="panel panel-primary" style="position: relative; float: right; width: 20%;">
			<div class="panel-heading">
				Пользователи онлайн
			</div>
  			<div class="panel-body">
    			<?php
					$users = $user->getUsersOnline();
					while ($arr = $users->fetch_assoc()) {
						echo $user->getEmail($arr["id_user"])."<br>";
					}
				?>
  			</div>
		</div>
	</body>
</html>
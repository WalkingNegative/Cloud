<?php
	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";

	header("Content-Type: text/html; charset=utf-8");

	session_start();

	if (empty($_SESSION["id_user"])) {
			header("location: ".PAGE_START);
	}
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
			if ($file->countFiles($_SESSION["id_user"]) > 0) {
				echo "<table class=\"table table-hover table-bordered\" style=\"width: 40%; margin: auto;\">
					<thead>
						<tr class=\alert alert-info\">
							<th>Имя файла</th>
							<th>Размер</th>
							<th><span class=\"glyphicon glyphicon-download-alt\"></span></th>
							<th><span class=\"glyphicon glyphicon-trash\"></span></th>
						</tr>
					</thead>";
				$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				settype($id, 'integer');
				$query = $db->query("select * from Files where id_user = ".$_SESSION["id_user"]." order by id_file DESC;");
				while ($files = $query->fetch_assoc()) {
					echo "<tr>";
					echo "<td>".$files['file_name']."</td><td>".$files['size']."</td>";
					echo "<td><a href=\"".PAGE_DOWNLOAD.$files['id_file']."\">Скачать </a></td>";
					echo "<td><a href=\"".PAGE_REMOVE.$files['id_file']."\"> Удалить </a></td>";
					echo "</tr>";
				}
				$db->close();
			} else {
				echo "<div class=\"alert alert-success\">У вас ещё нет файлов</div>";
			}
		?>
		</table>
		<form action="load.php" method="post" enctype="multipart/form-data" id="upload" style="margin: auto;">
			<h3><span class="label label-primary"><label for="uploadbtn">Загрузить файл</label></span></h3>
			<input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="load">
		</form>
		<?php
			if (!empty($_SESSION["error"])) {
				echo "<script charset=\"utf-8\">alert(\"".$_SESSION["error"]."\");</script>";
				unset($_SESSION["error"]);
			}
		?>
	</body>
</html>
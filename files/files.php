<?php
	header("Content-Type: text/html; charset=utf-8");

	session_start();

	if (empty($_SESSION["id_user"]))
			header("location: ".PAGE_MAIN);

	include "../config.php.ini";
	include "../classes/file.class.php";
	include "../classes/user.class.php";
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
					echo $user->get_email($_SESSION["id_user"]);
				?>
			</div>
			<a href="../users/logout.php" title="Выйти" class="navbar-brand glyphicon glyphicon-log-out"></a>
		</nav>
		
		<hr>
		<?php
			$file = new File();
			if ($file->count_files($_SESSION["id_user"]) > 0)
			{
				echo "<table class=\"table table-hover table-bordered\" style=\"width: 40%; margin: auto;\">
					<thead>
						<tr class=\alert alert-info\">
							<th>Имя файла</th>
							<th>Размер</th>
							<th><span class=\"glyphicon glyphicon-download-alt\"></span></th>
							<th><span class=\"glyphicon glyphicon-trash\"></span></th>
						</tr>
					</thead>";
				$file->show_files($user->get_id($_SESSION["email"]));
			}
			else
				echo "<div class=\"alert alert-success\">У вас ещё нет файлов</div>";
		?>
		</table>
		<hr>
		<form action="load.php" method="post" enctype="multipart/form-data" id="upload" style="margin: auto;">
			<h3><span class="label label-primary"><label for="uploadbtn">Загрузить файл</label></span></h3>
			<input type="file" name="filename" id="uploadbtn" onchange="document.getElementById('upload').submit()" style="opacity: 0; z-index: -1;" for="load">
		</form>
		<?php
			if (!empty($_SESSION["error"]))
			{
				echo "<script charset=\"utf-8\">alert(\"".$_SESSION["error"]."\");</script>";
				unset($_SESSION["error"]);
			}
		?>
	</body>
</html>
<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start();
	if (empty($_SESSION["email"]))
			header("location: ../index.php");
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
					echo $_SESSION["email"];
				?>
			</div>
			<a href="../users/logout.php" title="Выйти" class="navbar-brand glyphicon glyphicon-log-out"></a>
		</nav>
		
		<hr>
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
				function GetId($email){
					$db = @mysql_connect("localhost", "root", "20021");
					mysql_select_db("Cloud", $db);
					$query = mysql_query("select * from Users;");
					while($users = @mysql_fetch_array($query))
					{
						if ($users["email"] == $email)
						{
							return $users["id_user"];
							mysql_close($db);
						}
					}
				}
				function ShowFiles($id)
				{
					$db = @mysql_connect("localhost", "root", "20021");
					mysql_select_db("Cloud", $db);
					settype($id, 'integer');
					$query = mysql_query("select * from Files where id_user = ".$id." order by id_file DESC;");
					while($files = @mysql_fetch_array($query))
					{
						echo "<tr>";
								echo "<td>".$files['file_name']."</td><td>".$files['size']."</td>";
								echo "<td><a href=\"download.php/?path=".$files['path']."\"> Скачать </a></td>";
								echo "<td><a href=\"remove.php/?path=".$files['path']."\"> Удалить </a></td>";
						echo "</tr>";
					}
					mysql_close($db);
				}
					ShowFiles(GetId($_SESSION["email"]));
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
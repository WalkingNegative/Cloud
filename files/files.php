<?php 
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
		<div class="">
		<?php
			echo $_SESSION["email"];
		?>
		</div>
		<hr>
		<form action="load.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
			<input type="hidden" name="MAX_FILE_SIZE" value="209715200">
			<input name="filename" type="file">
			<input type="submit" value="Загрузить">
		</form>
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
					$query = mysql_query("select * from Users where email = '".$email."';");
					while($users = @mysql_fetch_array($query))
					{
						if ($users["email"] == $email)
						{
							return $users["id_user"];
							mysql_close($db);
						}
					}
				}

				function ShowFiles($id){
					$db = @mysql_connect("localhost", "root", "20021");
					mysql_select_db("Cloud", $db);
					$query = mysql_query("select * from Files where id_user = ".$id." order by id_file DESC;");
					while($files = @mysql_fetch_array($query))
					{
						echo "<tr>";
						echo "<td>".$files['file_name']."</td><td>".$files['size']."</td>"; 
						echo "<td><a href=\"download.php/?path=".$files['path']."\"> Скачать </a></td>";
						echo "<td><a href=\"remove.php?path=".$files['path']."\"> Удалить </a></td>";
						echo "</tr>";
					}
					mysql_close($db);
					}

					ShowFiles(GetId($_SESSION["email"]));
				?>
			</table>
			<hr>
			<form action="../users/logout.php" method="post" accept-charset="utf-8">
				<input type="submit" name="" value="Выйти">
			</form>
		</body>
	</html>
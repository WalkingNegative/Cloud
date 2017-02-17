<?php 
	class File
	{
		function is_owner($file, $id)
		{
			$db = new mysqli("localhost", "root", "20021", "Cloud");
			$query = $db->query("select * from Files;");
			while($files = $query->fetch_assoc())
			{
				if (($files["path"] == $file) && ($files["id_user"] == $id))
				{
					return true;
					$db->close();
				}
			}
			$db->close();
			return false;
		}

		function show_files($id)
		{
			$db =new mysqli("localhost", "root", "20021", "Cloud");
			settype($id, 'integer');
			$query = $db->query("select * from Files where id_user = ".$id." order by id_file DESC;");
			while($files = $query->fetch_assoc())
			{
				echo "<tr>";
				echo "<td>".$files['file_name']."</td><td>".$files['size']."</td>";
				echo "<td><a href=\"download.php/?path=".$files['path']."\"> Скачать </a></td>";
				echo "<td><a href=\"remove.php/?path=".$files['path']."\"> Удалить </a></td>";
				echo "</tr>";
			}
			$db->close();
		}

		function add_file($uploaddir, $id)
		{
			if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
			{
				$size = round($_FILES["filename"]["size"]/1048576, 2);
				$path = $uploaddir.$_FILES["filename"]["name"];
				echo $id;
				move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
				$db=new mysqli("localhost", "root", "20021", "Cloud");
				$db->query("insert into Files (file_name, path, id_user, size) values ('".$_FILES["filename"]["name"]."', '".$path."', ".$id.", ".$size.");");
				$db->close();
			} 
			else 
			{
				$_SESSION["error"] = "Ошибка загузки файла";
			}
		}

		function check_type($name)
		{
			$type = new SplFileInfo($name);
			$types = array ("com","bat","js","php","exe","cmd","vb","vbs");
			foreach ($types as $value) {
				if ($value == ($type->getExtension()))
					return true;
			}
			return false;
		}
	}
?>
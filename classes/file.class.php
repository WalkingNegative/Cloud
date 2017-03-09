<?php
	include_once("../config.php.ini");
	
	class File
	{
		function is_owner($id_user, $id_file)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("select * from Files;");
			while($files = $query->fetch_assoc())
				if (($files["id_file"] == $id_file) && ($files["id_user"] == $id_user))
				{
					$db->close();
					return true;
				}
			$db->close();
			return false;
		}

		function count_files($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			settype($id, 'integer');
			$query = $db->query("select * from Files where id_user = ".$id." order by id_file DESC;");
			$result = $query->num_rows;
			$db->close();
			return $result;
		}

		function get_path($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			settype($id, 'integer');
			$query = $db->query("select * from Files;");
			while($files = $query->fetch_assoc())
				if ($files["id_file"] == $id)
					return $files["path"];
			return false;
		}

		function show_files($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			settype($id, 'integer');
			$query = $db->query("select * from Files where id_user = ".$id." order by id_file DESC;");
			while($files = $query->fetch_assoc())
			{
				echo "<tr>";
				echo "<td>".$files['file_name']."</td><td>".$files['size']."</td>";
				echo "<td><a href=\"".PAGE_DOWNLOAD.$files['id_file']."\">Скачать </a></td>";
				echo "<td><a href=\"".PAGE_REMOVE.$files['id_file']."\"> Удалить </a></td>";
				echo "</tr>";
			}
			$db->close();
		}

		function file_download($file) 
		{
			if (file_exists($file))
			{
				if (ob_get_level())
				{
					ob_end_clean();
				}
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
	}

		function add_file($uploaddir, $id)
		{
			if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
			{
				$size = round($_FILES["filename"]["size"]/1048576, 2);
				$path = $uploaddir.$_FILES["filename"]["name"];
				move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
				$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$db->query("insert into Files (file_name, path, id_user, size) values ('".$_FILES["filename"]["name"]."', '".$path."', ".$id.", ".$size.");");
				$db->close();
			} 
			else
				$_SESSION["error"] = "Ошибка загузки файла";
		}

		function delete_file($path)
		{
			unlink($path);
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$db->query("delete from Files where path = '".$path."';");
			$db->close();
		}

		function delete_all_files($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$db->query("delete from Files where id_user = '".$id."';");
			$db->close();
		}

		function check_type($name)
		{
			$type = new SplFileInfo($name);
			$types = array ("com","bat","js","php","cmd","vb","vbs");
			foreach ($types as $value)
				if ($value == ($type->getExtension()))
					return true;
			return false;
		}
	}
?>
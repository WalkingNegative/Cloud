<?php
	include_once("../config.php.ini");
	
	class File 
	{
		public function isOwner($id_user, $id_file)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$query = $db->query("select * from Files;");
			while($files = $query->fetch_assoc()) {
				if (($files["id_file"] == $id_file) && ($files["id_user"] == $id_user)) {
					$db->close();
					return true;
				}
			}
			$db->close();
			return false;
		}

		public function countFiles($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			settype($id, 'integer');
			$query = $db->query("select * from Files where id_user = ".$id." order by id_file DESC;");
			$result = $query->num_rows;
			$db->close();
			return $result;
		}

		public function getPath($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			settype($id, 'integer');
			$query = $db->query("select * from Files;");
			while($files = $query->fetch_assoc()) {
				if ($files["id_file"] == $id) {
					return $files["path"];
				}
			}
			return false;
		}

		public function fileDownload($file)
		{
			if (file_exists($file)) {
				if (ob_get_level()) {
					ob_end_clean();
				}
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: '.filesize($file));
				readfile($file);
				exit;
			}
		}

		public function addFile($uploaddir, $id)
		{
			if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
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

		public function deleteFile($path)
		{
			unlink($path);
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$db->query("delete from Files where path = '".$path."';");
			$db->close();
		}

		public function deleteAllFiles($id)
		{
			$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			$db->query("delete from Files where id_user = '".$id."';");
			$db->close();
		}

		public function checkType($name)
		{
			$type = new SplFileInfo($name);
			$types = array ("com","bat","js","php","cmd","vb","vbs");
			foreach ($types as $value) {
				if ($value == ($type->getExtension()))
					return true;
			}
			return false;
		}

		public static function checkNavigation($page)
		{
			$referer = getenv("HTTP_REFERER");
			if ($referer != $page) {
				header("location: ".$page);
				exit;
			}
		}
	}

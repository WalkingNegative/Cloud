<?php
	require_once("../config.php.ini");
	require_once("abstractmodel.class.php");
	
	class File extends AbstractModel
	{
		protected $db;

		public function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		}

		public function isOwner($id_user, $id_file)
		{
			$query = $this->db->query("select * from Files;");
			while($files = $query->fetch_assoc()) {
				if (($files["id_file"] == $id_file) && ($files["id_user"] == $id_user)) {
					return true;
				}
			}
			return false;
		}

		public function countFiles($id)
		{
			$stmt = $this->db->prepare("select id_file from Files where id_user = ?;");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->store_result();
			$result = $stmt->num_rows;
			return $result;
		}

		public function getFiles($id)
		{
			$stmt = $this->db->prepare("select id_file, file_name, size from Files where id_user = ? order by id_file DESC;");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			return $result;
		}

		public function getPath($id)
		{
			settype($id, 'integer');
			$query = $this->db->query("select * from Files;");
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
				$stmt = $this->db->prepare("insert into Files (file_name, path, id_user, size) values (?, ?, ? ,?);");
				$stmt->bind_param("ssid", $_FILES["filename"]["name"], $path, $id, $size);
				$stmt->execute();
			} 
			else
				$_SESSION["error"] = "Ошибка загузки файла";
		}

		public function deleteFile($path)
		{
			unlink($path);
			$stmt = $this->db->prepare("delete from Files where path = ?;");
			$stmt->bind_param("s", $path);
			$stmt->execute();
		}

		public function deleteAllFiles($id)
		{
			$stmt = $this->db->prepare("delete from Files where id_user = ?;");
			$stmt->bind_param("i", $id);
			$stmt->execute();
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

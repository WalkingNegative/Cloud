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

		public function getInfo($id)
		{
			$stmt = $this->db->prepare("Select file_name, path, size, access From Files where id_file = ?;");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->bind_result($file_name, $path, $size, $access);
			$stmt->store_result();
			$stmt->fetch();
			if ($stmt->num_rows == 1) {
				return [$file_name, $path, $size, $access];
			}
			return false;
		}

		public function isOwner($user, $file)
		{
			$stmt = $this->db->prepare("select id_user from Files where id_file = ?;");
			$stmt->bind_param('i', $file);
			$stmt->execute();
			$stmt->bind_result($id_user);
			$stmt->fetch();
			if ($id_user == $user) {
				return true;
			}
			return false;
		}

		public function countFiles($id = 0)
		{
			if ($id > 0) {
				$stmt = $this->db->prepare("select id_file from Files where id_user = ? and access = 'private';");
				$stmt->bind_param("i", $id);
			} else {
				$stmt = $this->db->prepare("select id_file from Files where access = 'public';");
			}
			$stmt->execute();
			$stmt->store_result();
			$result = $stmt->num_rows;
			return $result;
		}

		public function getFiles($id = 0)
		{
			if ($id > 0) {
				$stmt = $this->db->prepare("select id_file, file_name, size from Files where id_user = ? and access = 'private' order by id_file DESC;");
				$stmt->bind_param("i", $id);
			} else {
				$stmt = $this->db->prepare("select id_file, file_name, size from Files where access = 'public' order by id_file DESC;");
			}
			$stmt->execute();
			return $stmt->get_result();
		}

		public function getPath($id_file)
		{
			$stmt = $this->db->prepare("select path from Files where id_file = ?;");
			$stmt->bind_param('i', $id_file);
			$stmt->execute();
			$stmt->bind_result($path);
			$stmt->fetch();
			return $path;
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
			$referer = getenv("HTTP_REFERER");
			$access = $referer == PAGE_MYFILES ? 'private' : 'public';
			if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
				$size = round($_FILES["filename"]["size"]/1048576, 2);
				$path = $uploaddir.$_FILES["filename"]["name"];
				move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
				$stmt = $this->db->prepare("insert into Files (file_name, path, id_user, size, access) values (?, ?, ? , ?, ?);");
				$stmt->bind_param("ssids", $_FILES["filename"]["name"], $path, $id, $size, $access);
				$stmt->execute();
			} 
			else
				return false;
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

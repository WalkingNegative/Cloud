<?php
	require_once("../config.php.ini");
	require_once("abstractmodel.class.php");

	class User extends AbstractModel
	{
		protected $db;
		
		public function __construct()
		{
			$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		}

		public function getId($email)
		{
			$stmt = $this->db->prepare("Select id_user From Users where email = ?;");
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->bind_result($id_user);
			$stmt->fetch();
			return $id_user;
		}

		public function getInfo($id)
		{
			$stmt = $this->db->prepare("Select id_user, name, surname, email, photo From Users where id_user = ?;");
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->bind_result($id_user, $name, $surname, $email, $photo);
			$stmt->store_result();
			$stmt->fetch();
			if ($stmt->num_rows == 1) {
				return [$id_user, $name, $surname, $email, $photo];
			}
			return false;
		}

		public function checkEmail($email)
		{
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
			}
			return false;
		}

		public function isExistEmail($email)
		{
			$stmt = $this->db->prepare("Select * From Users Where email = ?;");
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->store_result();
				if ($stmt->num_rows != 0) {
					return true;
				}
			return false;
		}

		public function authorization($email, $pas)
		{
			if (!$this->checkEmail($email) || (strlen($pas) < 8)) {
				return false;
			}
			$stmt = $this->db->prepare("Select password From Users where email = ?;");
			$stmt->bind_param('s', $email);
			$stmt->execute();
			$stmt->bind_result($password);
			$stmt->store_result();
			$stmt->fetch();
			if ($stmt->num_rows == 1) {
				if (password_verify($pas, $password)) {
			 		return true;
				}
			}
			return false;
		}

		public function newUser($email, $password, $name, $surname)
		{
			if (!$this->checkEmail($email) || (strlen($password) < 8) || $this->isExistEmail($email))
			{
				return false;
			}
			$password = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $this->db->prepare("Insert into Users (email, password, name, surname) values (?, ?, ?, ?);");
			$stmt->bind_param("ssss", $email, $password, $name, $surname);
			$stmt->execute();
			if (!file_exists(DIR_DISC.$email)) {
				mkdir(DIR_DISC.$email);
			}
			return true;
		}

		public static function checkUsersOnline()
		{
			$id_session = session_id();
			$db = parent::connectBd();  
			$stmt = $db->prepare("select * from Sessions where id_session = ?"); 
			$stmt->bind_param("s", $id_session);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) { 
				$stmt = $db->prepare("update Sessions set putdate = now(), id_user = ? WHERE id_session = ?");
				$stmt->bind_param("is", $_SESSION["id_user"], $id_session);
				$stmt->execute();
			} else { 
		        $stmt = $db->prepare("insert into Sessions values(?, NOW(), ?);");
				$stmt->bind_param("si", $id_session, $_SESSION["id_user"]);
				$stmt->execute();
			}  
			$db->query("delete from Sessions  where putdate < NOW() -  interval '15' minute"); 
		}

		public function getUsersOnline()
		{
			$stmt = $this->db->prepare("select id_user from Sessions order by id_user;");
			$stmt->execute();
			return $stmt->get_result();
		}

		public function getAllUsers()
		{
			$stmt = $this->db->prepare("select * from Users order by id_user;");
			$stmt->execute();
			return $stmt->get_result();
		}

		public function addPhoto($uploaddir, $id)
		{
			if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
				$path = $uploaddir.$_FILES["filename"]["name"];
				move_uploaded_file($_FILES["filename"]["tmp_name"], $uploaddir.$_FILES["filename"]["name"]);
				$stmt = $this->db->prepare("update Users set photo = ? where id_user = ?;");
				$stmt->bind_param("si", $path, $id);
				$stmt->execute();
			} 
			else
				return false;
		}

		public function deletePhoto($id)
		{	
			if (file_exists($this->getInfo($id)[4])) { 
				unlink($this->getInfo($id)[4]);
				$stmt = $this->db->prepare("update Users set photo = '' where id_user = ?;");
				$stmt->bind_param("i", $id);
				$stmt->execute();
			}
		}
	}
